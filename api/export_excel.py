import base64
import hashlib
import hmac
import json
import os
from datetime import datetime
from http.server import BaseHTTPRequestHandler
from io import BytesIO
from urllib.parse import quote
from urllib.request import Request, urlopen

import psycopg
from openpyxl import Workbook
from openpyxl.styles import Alignment, Font, PatternFill


def decode_token(token: str) -> dict:
    try:
        header, payload, signature = token.split('.')
        signing_input = f'{header}.{payload}'.encode()
        expected = base64.urlsafe_b64encode(
            hmac.new(os.environ['JWT_SECRET'].encode(), signing_input, hashlib.sha256).digest()
        ).rstrip(b'=').decode()
        if not hmac.compare_digest(expected, signature):
            raise ValueError('signature')
        padded = payload + '=' * (-len(payload) % 4)
        data = json.loads(base64.urlsafe_b64decode(padded))
        if data.get('exp', 0) < datetime.now().timestamp():
            raise ValueError('expired')
        return data
    except (KeyError, ValueError, json.JSONDecodeError):
        raise PermissionError('Invalid access token')


def active_session(payload: dict) -> bool:
    with psycopg.connect(os.environ['DATABASE_URL']) as connection:
        with connection.cursor() as cursor:
            cursor.execute(
                'SELECT 1 FROM tbl_user WHERE user_id = %s AND current_token = %s',
                (payload.get('user_id'), payload.get('session_token')),
            )
            return cursor.fetchone() is not None


def workbook_bytes(data: dict) -> bytes:
    workbook = Workbook()
    worksheet = workbook.active
    worksheet.title = 'Báo cáo tổng quan'
    worksheet.append(['BÁO CÁO TỔNG QUAN NHIỆM VỤ KHOA HỌC'])
    worksheet.merge_cells('A1:F1')
    worksheet['A1'].font = Font(bold=True, size=14, color='FFFFFF')
    worksheet['A1'].fill = PatternFill('solid', fgColor='1E3A8A')
    worksheet['A1'].alignment = Alignment(horizontal='center')
    overview = data.get('data', {}).get('overview', {})
    worksheet.append(['Ngày xuất', datetime.now().strftime('%d/%m/%Y %H:%M')])
    worksheet.append(['Tổng đề tài', overview.get('total_projects', 0)])
    worksheet.append(['Đang thực hiện', overview.get('running_projects', 0)])
    worksheet.append(['Đã hoàn thành', overview.get('completed_projects', 0)])
    worksheet.append([])
    worksheet.append(['Mã đề tài', 'Tên đề tài', 'Trạng thái', 'Kinh phí', 'Chủ nhiệm', 'Đơn vị'])
    for cell in worksheet[7]:
        cell.font = Font(bold=True, color='FFFFFF')
        cell.fill = PatternFill('solid', fgColor='2563EB')
    for project in data.get('data', {}).get('project_list', []):
        worksheet.append([
            project.get('project_code', ''), project.get('project_name', ''),
            project.get('status', ''), project.get('approved_budget', 0),
            project.get('leader_name', ''), project.get('leader_unit', ''),
        ])
    for column, width in {'A': 18, 'B': 48, 'C': 22, 'D': 18, 'E': 28, 'F': 32}.items():
        worksheet.column_dimensions[column].width = width
    for row in worksheet.iter_rows(min_row=8, min_col=4, max_col=4):
        row[0].number_format = '#,##0 "VNĐ"'
    output = BytesIO()
    workbook.save(output)
    return output.getvalue()


def put_blob(contents: bytes) -> str:
    pathname = f'exports/Bao_Cao_NCKH_{datetime.now():%Y%m%d_%H%M%S}.xlsx'
    request = Request(
        f'https://blob.vercel-storage.com/?pathname={quote(pathname)}',
        data=contents,
        method='PUT',
        headers={
            'Authorization': f"Bearer {os.environ['BLOB_READ_WRITE_TOKEN']}",
            'x-api-version': '7',
            'x-vercel-blob-access': 'public',
            'x-add-random-suffix': '1',
            'x-content-type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'x-content-disposition': 'attachment; filename="Bao_Cao_NCKH.xlsx"',
        },
    )
    with urlopen(request, timeout=30) as response:
        return json.loads(response.read())['url']


class handler(BaseHTTPRequestHandler):
    def respond_json(self, status: int, payload: dict):
        body = json.dumps(payload).encode()
        self.send_response(status)
        self.send_header('Content-Type', 'application/json; charset=utf-8')
        self.send_header('Content-Length', str(len(body)))
        self.end_headers()
        self.wfile.write(body)

    def do_POST(self):
        try:
            length = int(self.headers.get('Content-Length', '0'))
            data = json.loads(self.rfile.read(length))
            authorization = self.headers.get('Authorization', '')
            payload = decode_token(authorization.removeprefix('Bearer ').strip())
            if not active_session(payload):
                raise PermissionError('Session revoked')
            url = put_blob(workbook_bytes(data))
            self.respond_json(200, {'status': 'success', 'url': url})
        except PermissionError:
            self.respond_json(401, {'status': 'error', 'message': 'Phiên đăng nhập không hợp lệ.'})
        except Exception:
            self.respond_json(500, {'status': 'error', 'message': 'Không thể kết xuất Excel.'})
