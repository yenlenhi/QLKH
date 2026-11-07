import sys
import os
import json
import openpyxl
from openpyxl.styles import Font, PatternFill, Alignment, Border, Side

# Tham số: 1 là file JSON dữ liệu, 2 là file Excel xuất ra
json_path = sys.argv[1] if len(sys.argv) > 1 else ""
output_file = sys.argv[2] if len(sys.argv) > 2 else "Bao_Cao_NCKH.xlsx"

if not os.path.exists(json_path):
    sys.exit(1)

with open(json_path, 'r', encoding='utf-8') as f:
    data = json.load(f)

overview = data.get('overview', {})
matrix = data.get('matrix', [])
sub_systems = data.get('sub_systems', [])
projects = data.get('projects', [])
unit_name = data.get('unit_name', 'Toàn trường')

wb = openpyxl.Workbook()

# Style Palette
NAVY_HEADER = "1E3A8A"
NAVY_SUB = "2563EB"
BG_LIGHT = "F8FAFC"
ZEBRA = "F1F5F9"
BORDER_COLOR = "CBD5E1"
WHITE = "FFFFFF"

font_title = Font(name="Times New Roman", size=16, bold=True, color=NAVY_HEADER)
font_sub = Font(name="Times New Roman", size=11, italic=True, color="475569")
font_th = Font(name="Times New Roman", size=11, bold=True, color=WHITE)
font_td = Font(name="Times New Roman", size=11, color="000000")
font_bold = Font(name="Times New Roman", size=11, bold=True, color="000000")

thin_border = Border(
    left=Side(style='thin', color=BORDER_COLOR),
    right=Side(style='thin', color=BORDER_COLOR),
    top=Side(style='thin', color=BORDER_COLOR),
    bottom=Side(style='thin', color=BORDER_COLOR)
)

# ==========================================================
# SHEET 1: BÁO CÁO TỔNG QUAN & MA TRẬN
# ==========================================================
ws1 = wb.active
ws1.title = "Báo Cáo Tổng Quan"
ws1.views.sheetView[0].showGridLines = True

ws1["A1"] = "BỘ CÔNG AN"
ws1["A1"].font = Font(name="Times New Roman", size=10, bold=True)
ws1["A2"] = "TRƯỜNG ĐẠI HỌC AN NINH NHÂN DÂN"
ws1["A2"].font = Font(name="Times New Roman", size=10, bold=True)
ws1["A3"] = "PHÒNG QUẢN LÝ NCKH"
ws1["A3"].font = Font(name="Times New Roman", size=10, italic=True)

ws1["E1"] = "CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM"
ws1["E1"].font = Font(name="Times New Roman", size=10, bold=True)
ws1["E1"].alignment = Alignment(horizontal="center")
ws1["E2"] = "Độc lập - Tự do - Hạnh phúc"
ws1["E2"].font = Font(name="Times New Roman", size=10, bold=True)
ws1["E2"].alignment = Alignment(horizontal="center")

ws1.merge_cells("A5:G5")
ws1["A5"] = "BÁO CÁO TỔNG QUAN & ĐỐI CHIẾU NHIỆM VỤ NCKH"
ws1["A5"].font = font_title
ws1["A5"].alignment = Alignment(horizontal="center", vertical="center")
ws1.row_dimensions[5].height = 28

ws1.merge_cells("A6:G6")
ws1["A6"] = f"Phạm vi: {unit_name.upper()} - Hệ thống Quản lý NCKH"
ws1["A6"].font = font_sub
ws1["A6"].alignment = Alignment(horizontal="center", vertical="center")

# Thẻ KPI
kpi_items = [
    ("TỔNG ĐỀ TÀI", overview.get('total', 0), "A", "B", NAVY_HEADER),
    ("ĐANG TIẾN HÀNH", overview.get('running', 0), "C", "C", "D97706"),
    ("ĐÃ HOÀN THÀNH", overview.get('completed', 0), "D", "D", "16A34A"),
    ("CHỜ THANH TOÁN", overview.get('awaiting_payment', 0), "E", "E", "7C3AED"),
    ("TỔNG KINH PHÍ PHÊ DUYỆT", overview.get('total_budget', 0), "F", "G", NAVY_HEADER)
]

for title, val, c1, c2, color in kpi_items:
    if c1 != c2:
        ws1.merge_cells(f"{c1}8:{c2}8")
        ws1.merge_cells(f"{c1}9:{c2}9")
    th = ws1[f"{c1}8"]
    th.value = title
    th.font = Font(name="Times New Roman", size=10, bold=True, color=WHITE)
    th.fill = PatternFill(start_color=NAVY_HEADER, end_color=NAVY_HEADER, fill_type="solid")
    th.alignment = Alignment(horizontal="center", vertical="center")
    th.border = thin_border

    td = ws1[f"{c1}9"]
    td.value = val
    td.font = Font(name="Times New Roman", size=13, bold=True, color=color)
    td.alignment = Alignment(horizontal="center", vertical="center")
    td.fill = PatternFill(start_color=BG_LIGHT, end_color=BG_LIGHT, fill_type="solid")
    td.border = thin_border
    if c1 == "F":
        td.number_format = '#,##0 "VNĐ"'

ws1.row_dimensions[8].height = 22
ws1.row_dimensions[9].height = 26

# Ma trận so sánh
ws1["A11"] = "I. MA TRẬN ĐỐI CHIẾU SỐ LƯỢNG VÀ KINH PHÍ THEO KỲ"
ws1["A11"].font = font_bold

matrix_headers = ["Kỳ Thống Kê"] + [f"{s.get('key')}) {s.get('system_name')}" for s in sub_systems] + ["Tổng Đề Tài", "Kinh Phí (VNĐ)"]
ws1.row_dimensions[12].height = 25

for c_idx, h in enumerate(matrix_headers, 1):
    c = ws1.cell(row=12, column=c_idx, value=h)
    c.font = font_th
    c.fill = PatternFill(start_color=NAVY_HEADER, end_color=NAVY_HEADER, fill_type="solid")
    c.alignment = Alignment(horizontal="center", vertical="center", wrap_text=True)
    c.border = thin_border

row_start = 13
for idx, r in enumerate(matrix):
    r_idx = row_start + idx
    ws1.row_dimensions[r_idx].height = 22
    row_data = [r.get('period', '')] + [r.get(s.get('key'), 0) for s in sub_systems] + [r.get('total', 0), r.get('budget', 0)]
    
    for c_idx, val in enumerate(row_data, 1):
        c = ws1.cell(row=r_idx, column=c_idx, value=val)
        c.font = font_td
        c.border = thin_border
        if c_idx == 1:
            c.alignment = Alignment(horizontal="center", vertical="center")
            c.font = font_bold
        elif c_idx == len(row_data):
            c.alignment = Alignment(horizontal="right", vertical="center")
            c.number_format = '#,##0 "VNĐ"'
            c.font = font_bold
        else:
            c.alignment = Alignment(horizontal="center", vertical="center")

# ==========================================================
# SHEET 2: DANH SÁCH CHI TIẾT
# ==========================================================
ws2 = wb.create_sheet(title="Danh Sách Chi Tiết")
ws2.views.sheetView[0].showGridLines = True

ws2.merge_cells("A1:J1")
ws2["A1"] = "DANH SÁCH CHI TIẾT CÁC NHIỆM VỤ NCKH & ĐMST"
ws2["A1"].font = font_title
ws2["A1"].alignment = Alignment(horizontal="center", vertical="center")
ws2.row_dimensions[1].height = 28

headers_detail = ["STT", "Mã Nhiệm Vụ", "Tên Đề Tài / Nhiệm Vụ", "Loại Đề Tài", "Chủ Nhiệm", "Đơn Vị Công Tác", "Trạng Thái", "Kinh Phí Phê Duyệt", "Đã Thanh Toán", "Còn Phải Chi"]
ws2.row_dimensions[3].height = 25

for c_idx, h in enumerate(headers_detail, 1):
    c = ws2.cell(row=3, column=c_idx, value=h)
    c.font = font_th
    c.fill = PatternFill(start_color=NAVY_HEADER, end_color=NAVY_HEADER, fill_type="solid")
    c.alignment = Alignment(horizontal="center", vertical="center", wrap_text=True)
    c.border = thin_border

status_map = {
    'pending': ('Chờ duyệt', 'D97706'),
    'running': ('Đang tiến hành', '2563EB'),
    'completed': ('Đã hoàn thành', '16A34A'),
    'awaiting_payment': ('Chờ thanh toán', '7C3AED'),
    'awaiting_acceptance': ('Chờ nghiệm thu', '0284C7'),
    'rejected': ('Từ chối', 'DC2626')
}

for idx, p in enumerate(projects, 1):
    r_idx = 3 + idx
    ws2.row_dimensions[r_idx].height = 22
    is_zebra = (idx % 2 == 0)
    row_fill = PatternFill(start_color=ZEBRA if is_zebra else WHITE, end_color=ZEBRA if is_zebra else WHITE, fill_type="solid")

    st_text, st_color = status_map.get(p.get('status'), (p.get('status') or '---', '000000'))
    budget = float(p.get('approved_budget') or p.get('estimated_budget') or 0)
    paid = float(p.get('total_paid') or 0)

    row_data = [
        idx,
        p.get('project_code') or f"NV{p.get('project_id')}",
        p.get('project_name', '---'),
        p.get('type_name') or p.get('sub_system_name', '---'),
        p.get('leader_name') or p.get('action_user_name', 'Chưa phân công'),
        p.get('leader_unit', 'Trực thuộc trường'),
        st_text,
        budget,
        paid,
        max(0, budget - paid)
    ]

    for c_idx, val in enumerate(row_data, 1):
        cell = ws2.cell(row=r_idx, column=c_idx, value=val)
        cell.font = font_td
        cell.fill = row_fill
        cell.border = thin_border

        if c_idx in [1, 2]:
            cell.alignment = Alignment(horizontal="center", vertical="center")
            if c_idx == 2: cell.font = font_bold
        elif c_idx == 7:
            cell.alignment = Alignment(horizontal="center", vertical="center")
            cell.font = Font(name="Times New Roman", size=11, bold=True, color=st_color)
        elif c_idx in [8, 9, 10]:
            cell.alignment = Alignment(horizontal="right", vertical="center")
            cell.number_format = '#,##0 "VNĐ"'
        else:
            cell.alignment = Alignment(horizontal="left", vertical="center")

# Căn chỉnh kích thước cột
for col in ['A', 'B', 'C', 'D', 'E', 'F', 'G']:
    ws1.column_dimensions[col].width = 22
ws1.column_dimensions['A'].width = 16
ws1.column_dimensions['G'].width = 26

detail_widths = {'A': 8, 'B': 16, 'C': 45, 'D': 22, 'E': 26, 'F': 24, 'G': 18, 'H': 22, 'I': 20, 'J': 20}
for col, w in detail_widths.items():
    ws2.column_dimensions[col].width = w

wb.save(output_file)