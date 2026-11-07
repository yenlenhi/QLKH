import openpyxl
from openpyxl.styles import Font, PatternFill, Alignment, Border, Side
from openpyxl.worksheet.datavalidation import DataValidation
from openpyxl.formatting.rule import CellIsRule

wb = openpyxl.Workbook()

# Sheet 1: Bảng dữ liệu chính
ws = wb.active
ws.title = "Danh_Sach_Nhiem_Vu"
ws.views.sheetView[0].showGridLines = True

# Sheet 2: Danh mục nguồn để tạo Dropdown
ws_cat = wb.create_sheet(title="DanhMuc_Nguon")
ws_cat.views.sheetView[0].showGridLines = True

# Dữ liệu nguồn theo chuẩn CSDL db_qlnckh
hinh_thuc_dang_ky = ["Nhiệm vụ tài trợ", "Nhiệm vụ tài trợ đề xuất", "Nhiệm vụ đặt hàng giao trực tiếp", "Nhiệm vụ đặt hàng tuyển chọn"]
loai_nhiem_vu = [
    "Nhiệm vụ Khoa học công nghệ và Đổi mới sáng tạo Quốc gia đặc biệt",
    "Nhiệm vụ Khoa học công nghệ và Đổi mới sáng tạo Phát triển Công nghệ chiến lược",
    "Nhiệm vụ Khoa học công nghệ và Đổi mới sáng tạo Do BCA phê duyệt",
    "Nhiệm vụ Khoa học công nghệ và Đổi mới sáng tạo Đề nghị Bộ Công an công nhận",
    "Nhiệm vụ Khoa học Công nghệ và Đổi mới sáng tạo BCA giao cho Công an địa phương phối hợp đăng ký thực hiện với Bộ hoặc UBND tỉnh",
    "Nhiệm vụ Khoa học công nghệ và Đổi mới sáng tạo Cơ sở"
]
muc_do_mat = ["Không mật", "Mật", "Tối mật", "Tuyệt mật"]

# Ghi dữ liệu vào Sheet DanhMuc_Nguon
ws_cat["A1"] = "Hình thức đăng ký"
for idx, val in enumerate(hinh_thuc_dang_ky, start=2): ws_cat[f"A{idx}"] = val

ws_cat["B1"] = "Loại nhiệm vụ"
for idx, val in enumerate(loai_nhiem_vu, start=2): ws_cat[f"B{idx}"] = val

ws_cat["C1"] = "Mức độ mật"
for idx, val in enumerate(muc_do_mat, start=2): ws_cat[f"C{idx}"] = val

# Định dạng giao diện
HEADER_BG = "1B365D"
HEADER_TEXT = "FFFFFF"
BORDER_GRAY = "D1D5DB"

font_title = Font(name="Segoe UI", size=13, bold=True, color=HEADER_TEXT)
font_sub = Font(name="Segoe UI", size=9, italic=True, color="E2E8F0")
font_header = Font(name="Segoe UI", size=10, bold=True, color=HEADER_TEXT)
font_data = Font(name="Segoe UI", size=10, color="1A1A1A")
font_formula = Font(name="Segoe UI", size=10, bold=True, color="1B365D")

fill_title = PatternFill(start_color=HEADER_BG, end_color=HEADER_BG, fill_type="solid")
fill_header = PatternFill(start_color="2C4D75", end_color="2C4D75", fill_type="solid")
fill_zebra = PatternFill(start_color="F8FAFC", end_color="F8FAFC", fill_type="solid")
fill_white = PatternFill(start_color="FFFFFF", end_color="FFFFFF", fill_type="solid")

thin_border = Border(
    left=Side(style='thin', color=BORDER_GRAY),
    right=Side(style='thin', color=BORDER_GRAY),
    top=Side(style='thin', color=BORDER_GRAY),
    bottom=Side(style='thin', color=BORDER_GRAY)
)

# Tiêu đề bảng
ws.merge_cells('A1:H1')
t1 = ws['A1']
t1.value = "DANH SÁCH XUẤT BẢN & ĐĂNG KÝ NHIỆM VỤ KHOA HỌC CÔNG NGHỆ"
t1.font = font_title
t1.fill = fill_title
t1.alignment = Alignment(horizontal="center", vertical="center")
ws.row_dimensions[1].height = 32

ws.merge_cells('A2:H2')
t2 = ws['A2']
t2.value = "Lưu ý: Cột D, E, F có bộ chọn thả xuống. Cột H tự động cảnh báo đỏ 'Đã quá hạn đăng ký' nếu vượt quá thời hạn."
t2.font = font_sub
t2.fill = fill_title
t2.alignment = Alignment(horizontal="center", vertical="center")
ws.row_dimensions[2].height = 20

headers = [
    "STT\n(Tự động)", "Mã Nhiệm Vụ (*)", "Tên Nhiệm Vụ (*)", 
    "Hình Thức Đăng Ký (*)", "Loại Nhiệm Vụ (*)", "Mức Độ Mật (*)", 
    "Thời Hạn Đăng Ký (*)", "Trạng Thái Hạn\n(Tự động)"
]

ws.row_dimensions[4].height = 30
for col_idx, h_text in enumerate(headers, start=1):
    cell = ws.cell(row=4, column=col_idx, value=h_text)
    cell.font = font_header
    cell.fill = fill_header
    cell.alignment = Alignment(horizontal="center", vertical="center", wrap_text=True)
    cell.border = thin_border

# Gán Data Validation (Dropdown)
dv_hinh_thuc = DataValidation(type="list", formula1=f"=DanhMuc_Nguon!$A$2:$A${len(hinh_thuc_dang_ky)+1}", allow_blank=True)
ws.add_data_validation(dv_hinh_thuc)
dv_hinh_thuc.add("D5:D300")

dv_loai_nv = DataValidation(type="list", formula1=f"=DanhMuc_Nguon!$B$2:$B${len(loai_nhiem_vu)+1}", allow_blank=True)
ws.add_data_validation(dv_loai_nv)
dv_loai_nv.add("E5:E300")

dv_mat = DataValidation(type="list", formula1=f"=DanhMuc_Nguon!$C$2:$C${len(muc_do_mat)+1}", allow_blank=True)
ws.add_data_validation(dv_mat)
dv_mat.add("F5:F300")

# Thiết lập sẵn các dòng nhập liệu có công thức tự động (từ dòng 5 đến 50)
for r in range(5, 51):
    ws.row_dimensions[r].height = 24
    idx = r - 4
    
    # Cột A: Tự nhảy STT khi cột B có mã
    c_stt = ws.cell(row=r, column=1, value=f'=IF(B{r}<>"","{idx}","")')
    c_stt.alignment = Alignment(horizontal="center", vertical="center")
    c_stt.font = font_formula
    
    # Cột G: Định dạng ngày
    c_dl = ws.cell(row=r, column=7)
    c_dl.number_format = "yyyy-mm-dd"
    c_dl.alignment = Alignment(horizontal="center", vertical="center")
    
    # Cột H: Tự động so sánh với ngày hiện tại TODAY()
    c_status = ws.cell(row=r, column=8, value=f'=IF(G{r}="","",IF(G{r}<TODAY(),"Đã quá hạn đăng ký","Còn hạn đăng ký"))')
    c_status.alignment = Alignment(horizontal="center", vertical="center")
    c_status.font = Font(name="Segoe UI", size=10, bold=True)
    
    for c_i in range(1, 9):
        cell_obj = ws.cell(row=r, column=c_i)
        cell_obj.border = thin_border
        cell_obj.fill = fill_zebra if r % 2 == 0 else fill_white
        if c_i not in [1, 8]:
            cell_obj.font = font_data

# Dữ liệu mẫu ban đầu ở dòng 5, 6, 7
sample_rows = [
    ("NV2026-01", "Nghiên cứu ứng dụng trí tuệ nhân tạo trong xử lý hình ảnh", "Nhiệm vụ đặt hàng", loai_nhiem_vu[1], "Không mật", "2026-11-30"),
    ("NV2026-02", "Nâng cao hiệu quả công tác quản lý dữ liệu đối tượng nghiệp vụ", "Nhiệm vụ tài trợ", loai_nhiem_vu[2], "Mật", "2026-10-15"),
    ("NV2026-03", "Khảo sát thực trạng và đề xuất giải pháp bảo đảm an toàn mạng", "Nhiệm vụ đặt hàng", loai_nhiem_vu[5], "Tối mật", "2026-12-15")
]

for idx, (code, name, ht, loai, mat, dl) in enumerate(sample_rows, start=5):
    ws.cell(row=idx, column=2, value=code)
    ws.cell(row=idx, column=3, value=name)
    ws.cell(row=idx, column=4, value=ht)
    ws.cell(row=idx, column=5, value=loai)
    ws.cell(row=idx, column=6, value=mat)
    ws.cell(row=idx, column=7, value=dl)

# Cấu hình CONDITIONAL FORMATTING Cảnh báo đỏ khi "Đã quá hạn đăng ký"
red_fill = PatternFill(start_color="FEE2E2", end_color="FEE2E2", fill_type="solid")
red_font = Font(name="Segoe UI", size=10, bold=True, color="B91C1C")
ws.conditional_formatting.add("H5:H50", CellIsRule(operator='equal', formula=['"Đã quá hạn đăng ký"'], stopIfTrue=True, fill=red_fill, font=red_font))

# Cảnh báo xanh khi "Còn hạn đăng ký"
green_fill = PatternFill(start_color="DCFCE7", end_color="DCFCE7", fill_type="solid")
green_font = Font(name="Segoe UI", size=10, bold=True, color="15803D")
ws.conditional_formatting.add("H5:H50", CellIsRule(operator='equal', formula=['"Còn hạn đăng ký"'], stopIfTrue=True, fill=green_fill, font=green_font))

# Căn chỉnh độ rộng cột
ws.column_dimensions['A'].width = 11
ws.column_dimensions['B'].width = 18
ws.column_dimensions['C'].width = 46
ws.column_dimensions['D'].width = 22
ws.column_dimensions['E'].width = 50
ws.column_dimensions['F'].width = 16
ws.column_dimensions['G'].width = 20
ws.column_dimensions['H'].width = 22

wb.save("Danh_Sach_Nhiem_Vu_Dang_Ky_Mau.xlsx")
print("Đã tạo file thành công!")