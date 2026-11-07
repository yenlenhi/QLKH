
// 1. Chuyển Tab ở Sidebar
function selectTab(element, tabName) {
    document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
    });
    element.classList.add('active');
    document.getElementById('pageTitle').innerText = 'QUẢN LÝ ' + tabName + ' CỦA TÔI';

    if (tabName !== 'ĐỀ TÀI') {
        alert('Mục ' + tabName + ' đang được cập nhật!');
    }
}

// 2. Mở / Đóng Modal
function openModal() {
    document.getElementById('modalOverlay').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modalOverlay').style.display = 'none';
}

// 3. Tìm kiếm trong bảng
function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("#projectTable tbody tr");

    rows.forEach(row => {
        let id = row.cells[0].textContent.toLowerCase();
        let title = row.cells[1].textContent.toLowerCase();

        if (id.includes(input) || title.includes(input)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

// 4. Xử lý Thêm Đề tài mới khi gửi Form
function handleFormSubmit(e) {
    e.preventDefault();
    let title = document.getElementById('inputTitle').value;
    let type = document.getElementById('selectType').value;
    let randomId = Math.floor(100000 + Math.random() * 900000);

    let table = document.getElementById('projectTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow(0);

    newRow.innerHTML = `
                <td>${randomId}</td>
                <td class="project-title">${title}</td>
                <td>${type}</td>
                <td>Đăng ký đề tài - Hoàn tất</td>
                <td><span class="badge badge-pending">Chờ duyệt</span></td>
                <td><button class="btn-action">Xem chi tiết</button></td>
            `;

    closeModal();
    document.getElementById('registerForm').reset();
    alert('Đã gửi đăng ký đề tài thành công!');
}
