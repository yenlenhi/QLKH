<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý NCKH - Admin Dashboard</title>
    <script src="../asset/script/auth_check.js"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/style_admin.css">
    <!-- Thư viện Chart.js & SheetJS để vẽ biểu đồ và xuất Excel -->
    <script src="../asset/script/chart.js"></script>
    <script src="../asset/script/xlsx.full.min.js"></script>
    <script src="../asset/script/exceljs.min.js"></script>
    <style>
        /* Khối hiển thị theo hàng dọc */
        .chart-legend-vertical {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 15px;
            padding: 14px 18px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .legend-tag-vertical {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
            font-size: 0.88rem;
            color: #1e293b;
        }

        .legend-tag-vertical:hover {
            background-color: #f1f5f9;
        }

        .legend-tag-vertical i.color-dot {
            width: 14px;
            height: 14px;
            border-radius: 3px;
            display: inline-block;
            flex-shrink: 0;
            transition: opacity 0.2s;
        }

        /* Trạng thái khi tắt một loại */
        .legend-tag-vertical.is-hidden {
            text-decoration: line-through;
            opacity: 0.45;
            background: transparent !important;
        }

        .legend-tag-vertical.is-hidden i.color-dot {
            background-color: #94a3b8 !important;
        }

        .chart-box-container {
            position: relative;
            height: 350px;
            width: 100%;
        }

        .filter-ctrl {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>

    <!-- Sidebar Menu -->
    <?php include '../Model/sidebar.php'; ?>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Topbar -->
        <?php include '../Model/r1_topbar.php'; ?>

        <!-- Tab Content -->
        <div id="adminTabContent">
            <div class="tab-pane fade show active" id="tab-dashboard" role="tabpanel">

                <!-- 1. GIỮ NGUYÊN: Stat Cards cũ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm p-3 bg-white border-0 rounded-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Tổng Đề Tài</p>
                                    <h3 class="fw-bold mb-0" id="statTotalProjects"><i class="fa-solid fa-spinner fa-spin fs-5"></i></h3>
                                </div>
                                <div class="card-icon bg-primary text-white"><i class="fa-solid fa-folder-open"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm p-3 bg-white border-0 rounded-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Đang Thực Hiện</p>
                                    <h3 class="fw-bold mb-0 text-warning" id="statRunningProjects"><i class="fa-solid fa-spinner fa-spin fs-5"></i></h3>
                                </div>
                                <div class="card-icon bg-warning text-white"><i class="fa-solid fa-spinner"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm p-3 bg-white border-0 rounded-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Đã Hoàn Thành</p>
                                    <h3 class="fw-bold mb-0 text-success" id="statCompletedProjects"><i class="fa-solid fa-spinner fa-spin fs-5"></i></h3>
                                </div>
                                <div class="card-icon bg-success text-white"><i class="fa-solid fa-circle-check"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm p-3 bg-white border-0 rounded-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Tổng Kinh Phí (VNĐ)</p>
                                    <h3 class="fw-bold mb-0 text-info fs-5" id="statTotalBudget"><i class="fa-solid fa-spinner fa-spin fs-5"></i></h3>
                                </div>
                                <div class="card-icon bg-info text-white"><i class="fa-solid fa-sack-dollar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. BIỂU ĐỒ CỘT SO SÁNH (KHOẢNG NĂM / TRONG NĂM / THEO NĂM) -->
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h6 class="m-0 fw-bold text-primary d-inline-block me-2">
                                <i class="fa-solid fa-chart-column me-2"></i>BÁO CÁO TỔNG QUAN & SO SÁNH THEO CÁC KỲ
                            </h6>
                            <span class="badge bg-light text-muted border" id="lblCompareSummary">0 nhiệm vụ</span>
                            <span class="badge bg-light text-primary border ms-1" id="scopeBadge">Toàn trường</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Kiểu thống kê thời gian -->
                            <select id="selCompareGran" class="form-select form-select-sm" style="width: 170px;" onchange="handleGranChange()">
                                <option value="nam" selected>So sánh các năm</option>
                                <option value="khoang_nam">Từ năm ... đến năm ...</option>
                                <option value="trong_nam">Trong năm (12 tháng)</option>
                                <option value="quy">Theo quý trong năm</option>
                            </select>

                            <!-- Khối chọn 1 năm cụ thể -->
                            <div id="boxSingleYear" class="d-none align-items-center gap-1">
                                <select id="selSingleYear" class="form-select form-select-sm" style="width: 95px;" onchange="loadDashboardData()"></select>
                            </div>

                            <!-- Khối chọn khoảng từ năm đến năm -->
                            <div id="boxRangeYear" class="d-none align-items-center gap-1">
                                <select id="selFromYear" class="form-select form-select-sm" style="width: 95px;"></select>
                                <span class="small text-muted">đến</span>
                                <select id="selToYear" class="form-select form-select-sm" style="width: 95px;"></select>
                                <button type="button" class="btn btn-sm btn-primary px-2" onclick="loadDashboardData()">Lọc</button>
                            </div>

                            <!-- Tính theo mốc ngày nộp hay ngày ký hợp đồng -->
                            <select id="selCompareBasis" class="form-select form-select-sm" style="width: 155px;" onchange="loadDashboardData()">
                                <option value="sub" selected>Ngày nộp hồ sơ</option>
                                <option value="start">Ngày ký hợp đồng</option>
                            </select>

                            <!-- Nút Xuất Excel -->
                            <button type="button" class="btn btn-outline-primary btn-sm fw-semibold" onclick="exportToExcel()">
                                <i class="fa-solid fa-file-excel me-1"></i> Xuất Excel
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Khung biểu đồ cột chồng -->
                        <div class="chart-box-container mb-3">
                            <canvas id="canvasCompareBar"></canvas>
                        </div>

                        <!-- Bảng đối chiếu số liệu -->
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered text-center align-middle mb-0" style="font-size: 0.85rem;" id="tableCompareMatrix">
                                <thead class="table-light" id="compareTableHead"></thead>
                                <tbody id="compareTableBody">
                                    <tr>
                                        <td colspan="10" class="text-muted py-3">Đang tải ma trận so sánh...</td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-secondary fw-bold" id="compareTableFooter"></tfoot>
                            </table>
                        </div>

                        <!-- KHỐI CHÚ GIẢI THEO HÀNG DỌC (BẬT / TẮT TỪNG LOẠI A, B, C...) -->
                        <div class="chart-legend-vertical" id="dynamicChartLegend"></div>
                    </div>
                </div>

                <!-- 3. GIỮ NGUYÊN: BẢNG THỐNG KÊ SỐ LƯỢNG THEO LOẠI ĐỀ TÀI -->
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-primary"><i class="fa-solid fa-layer-group me-2"></i>Thống Kê Tổng Số Đề Tài Theo Loại Đề Tài</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start">Loại Đề Tài</th>
                                        <th>Chờ Duyệt</th>
                                        <th>Đang Thực Hiện</th>
                                        <th>Chờ Thanh Toán</th>
                                        <th>Đã Hoàn Thành</th>
                                        <th>Tổng Số Đề Tài</th>
                                    </tr>
                                </thead>
                                <tbody id="tableTypeCountBody">
                                    <tr>
                                        <td colspan="6" class="text-muted py-4"><i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải dữ liệu thống kê...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 4. GIỮ NGUYÊN: KHỐI THỐNG KÊ THANH TOÁN & GIẢI NGÂN THEO LOẠI ĐỀ TÀI -->
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fa-solid fa-money-bill-wave me-2"></i>Thống Kê Thanh Toán & Giải Ngân Theo Loại Đề Tài
                        </h6>
                        <span class="badge bg-light text-dark border" id="currentYearBadge"></span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border">
                                <thead class="table-light">
                                    <tr>
                                        <th>Loại Đề Tài</th>
                                        <th class="text-end">Tổng Kinh Phí Phê Duyệt</th>
                                        <th class="text-end">Đã Giải Ngân / Thanh Toán</th>
                                        <th class="text-end">Còn Tồn Đọng / Tạm Giữ</th>
                                        <th style="width: 20%;">Tiến Độ Giải Ngân</th>
                                    </tr>
                                </thead>
                                <tbody id="tableFinancialBody">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4"><i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải dữ liệu tài chính...</td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-secondary fw-bold" id="tableFinancialFooter"></tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <script>
        const API_URL = '../index_api.php';
        let compareChartInstance = null;
        let cachedDashboardData = null;
        let hasInitializedYears = false;

        const COLOR_PALETTE = ['#2a78d6', '#eb6834', '#1baf7a', '#eda100', '#e87ba4', '#008300', '#6366f1', '#14b8a6', '#f43f5e'];

        document.addEventListener("DOMContentLoaded", function() {
            const thisYear = new Date().getFullYear();
            const badgeEl = document.getElementById('currentYearBadge');
            if (badgeEl) badgeEl.innerText = `Cập nhật: ${thisYear}`;

            loadDashboardData();
        });

        function handleGranChange() {
            const gran = document.getElementById('selCompareGran').value;
            const boxSingle = document.getElementById('boxSingleYear');
            const boxRange = document.getElementById('boxRangeYear');

            boxSingle.classList.remove('d-flex');
            boxSingle.classList.add('d-none');
            boxRange.classList.remove('d-flex');
            boxRange.classList.add('d-none');

            if (gran === 'trong_nam' || gran === 'quy') {
                boxSingle.classList.remove('d-none');
                boxSingle.classList.add('d-flex');
            } else if (gran === 'khoang_nam') {
                boxRange.classList.remove('d-none');
                boxRange.classList.add('d-flex');
            }

            loadDashboardData();
        }

        async function loadDashboardData() {
            const gran = document.getElementById('selCompareGran').value;
            const basis = document.getElementById('selCompareBasis').value;
            const singleYear = document.getElementById('selSingleYear').value;
            const fromYear = document.getElementById('selFromYear').value;
            const toYear = document.getElementById('selToYear').value;

            let query = `?detect=dashboard_stats&gran=${gran}&basis=${basis}`;
            if ((gran === 'trong_nam' || gran === 'quy') && singleYear) {
                query += `&year=${singleYear}`;
            } else if (gran === 'khoang_nam' && fromYear && toYear) {
                query += `&from_year=${fromYear}&to_year=${toYear}`;
            }

            try {
                const response = await fetchWithAuth(`${API_URL}${query}`, {
                    method: "GET",
                    headers: {
                        "Accept": "application/json"
                    }
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    cachedDashboardData = result;

                    // Khởi tạo các dropdown năm tự động từ CSDL một lần đầu
                    if (!hasInitializedYears && result.data && result.data.available_years) {
                        populateYearDropdowns(result.data.available_years);
                        hasInitializedYears = true;
                    }

                    renderDashboard(result);
                } else {
                    console.error("Lỗi lấy dữ liệu:", result.message);
                }
            } catch (err) {
                console.error("Lỗi kết nối Dashboard API:", err);
            }
        }

        function populateYearDropdowns(years) {
            if (!years || !years.length) return;
            const curY = new Date().getFullYear();

            const selSingle = document.getElementById('selSingleYear');
            const selFrom = document.getElementById('selFromYear');
            const selTo = document.getElementById('selToYear');

            selSingle.innerHTML = '';
            selFrom.innerHTML = '';
            selTo.innerHTML = '';

            years.forEach(y => {
                selSingle.innerHTML += `<option value="${y}" ${y === curY ? 'selected' : ''}>${y}</option>`;
                selFrom.innerHTML += `<option value="${y}" ${y === years[0] ? 'selected' : ''}>${y}</option>`;
                selTo.innerHTML += `<option value="${y}" ${y === curY ? 'selected' : ''}>${y}</option>`;
            });
        }

        function renderDashboard(res) {
            const scope = res.scope;
            const unitName = res.unit_name || '';
            const scopeBadge = document.getElementById('scopeBadge');
            if (scope === 'all') {
                scopeBadge.className = 'badge bg-primary text-white ms-1';
                scopeBadge.innerText = 'Toàn Học viện (P5)';
            } else {
                scopeBadge.className = 'badge bg-warning text-dark ms-1';
                scopeBadge.innerText = unitName;
            }

            const data = res.data || {};
            const overview = data.overview || {};
            const typeStats = data.type_stats || [];
            const subSystems = data.sub_systems || [];
            const matrix = data.comparison_matrix || [];

            // 1. Cập nhật Stat Cards
            document.getElementById('statTotalProjects').textContent = Number(overview.total_projects || 0).toLocaleString();
            document.getElementById('statRunningProjects').textContent = Number(overview.running_projects || 0).toLocaleString();
            document.getElementById('statCompletedProjects').textContent = Number(overview.completed_projects || 0).toLocaleString();
            document.getElementById('statTotalBudget').textContent = formatCurrency(overview.total_budget || 0);

            // 2. Render biểu đồ và danh sách chú giải hàng dọc
            renderCompareChart(matrix, subSystems);
            renderVerticalLegendWithToggle(subSystems);

            // 3. Render bảng ma trận
            renderCompareTable(matrix, subSystems);

            // 4. Render 2 bảng số liệu cũ
            renderOldTables(typeStats);
        }

        function renderCompareChart(matrix, subSystems) {
            const labels = matrix.map(m => m.period);

            const datasets = subSystems.map((item, idx) => {
                const color = COLOR_PALETTE[idx % COLOR_PALETTE.length];
                return {
                    label: `${item.key}) ${item.system_name}`,
                    data: matrix.map(m => m[item.key] || 0),
                    backgroundColor: color,
                    categoryKey: item.key
                };
            });

            const ctx = document.getElementById('canvasCompareBar').getContext('2d');
            if (compareChartInstance) compareChartInstance.destroy();

            const topNumberPlugin = {
                id: 'topNumberPlugin',
                afterDatasetsDraw(chart) {
                    const {
                        ctx,
                        scales: {
                            x,
                            y
                        }
                    } = chart;

                    chart.data.labels.forEach((label, idx) => {
                        let visibleTotal = 0;
                        chart.data.datasets.forEach((ds, dsIdx) => {
                            if (chart.isDatasetVisible(dsIdx)) {
                                visibleTotal += (ds.data[idx] || 0);
                            }
                        });

                        if (visibleTotal > 0) {
                            const xPos = x.getPixelForValue(idx);
                            const yPos = y.getPixelForValue(visibleTotal);
                            ctx.save();
                            ctx.font = 'bold 13px "Segoe UI", sans-serif';
                            ctx.fillStyle = '#0f172a';
                            ctx.textAlign = 'center';
                            ctx.fillText(visibleTotal, xPos, yPos - 8);
                            ctx.restore();
                        }
                    });
                }
            };

            compareChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    barPercentage: 0.38,
                    categoryPercentage: 0.8,
                    layout: {
                        padding: {
                            top: 32
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 13,
                                    weight: '600'
                                },
                                color: '#334155'
                            }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            grace: '20%',
                            grid: {
                                color: '#e2e8f0'
                            },
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                footer: (items) => {
                                    const idx = items[0].dataIndex;
                                    const b = (matrix[idx].budget / 1e9).toLocaleString('vi-VN', {
                                        maximumFractionDigits: 2
                                    });
                                    return `Tổng · kinh phí: ${matrix[idx].total} · ${b} tỷ đ`;
                                }
                            }
                        }
                    }
                },
                plugins: [topNumberPlugin]
            });
        }

        // TẠO CHÚ GIẢI HÀNG DỌC KÈM TÍNH NĂNG BẬT/TẮT TỪNG MỤC
        function renderVerticalLegendWithToggle(subSystems) {
            const legendBox = document.getElementById('dynamicChartLegend');
            if (!legendBox || !subSystems || subSystems.length === 0) return;

            let html = '<div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom">' +
                '<span class="fw-bold text-dark small"><i class="fa-solid fa-list-check me-1 text-primary"></i>Danh mục loại nhiệm vụ (Nhấp chuột để Bật/Tắt hiển thị trên biểu đồ):</span>' +
                '<button type="button" class="btn btn-link btn-sm text-decoration-none p-0 text-muted" style="font-size:0.78rem" onclick="toggleAllDatasets(true)">Hiện tất cả</button>' +
                '</div>';

            subSystems.forEach((item, idx) => {
                const color = COLOR_PALETTE[idx % COLOR_PALETTE.length];
                html += `
                    <div class="legend-tag-vertical" id="legend_tag_${idx}" onclick="handleToggleDataset(${idx})" title="Bấm để ẩn hoặc hiện loại này">
                        <i class="color-dot" style="background: ${color}"></i>
                        <span><b>${item.key}.</b> ${escapeHtml(item.system_name)} (${escapeHtml(item.system_code)})</span>
                    </div>
                `;
            });

            legendBox.innerHTML = html;
        }

        function handleToggleDataset(datasetIndex) {
            if (!compareChartInstance) return;

            const isVisible = compareChartInstance.isDatasetVisible(datasetIndex);
            compareChartInstance.setDatasetVisibility(datasetIndex, !isVisible);
            compareChartInstance.update();

            const tagEl = document.getElementById(`legend_tag_${datasetIndex}`);
            if (tagEl) {
                if (isVisible) {
                    tagEl.classList.add('is-hidden');
                } else {
                    tagEl.classList.remove('is-hidden');
                }
            }
        }

        function toggleAllDatasets(showAll) {
            if (!compareChartInstance) return;
            compareChartInstance.data.datasets.forEach((ds, idx) => {
                compareChartInstance.setDatasetVisibility(idx, showAll);
                const tagEl = document.getElementById(`legend_tag_${idx}`);
                if (tagEl) tagEl.classList.remove('is-hidden');
            });
            compareChartInstance.update();
        }

        function renderCompareTable(matrix, subSystems) {
            const thead = document.getElementById('compareTableHead');
            const tbody = document.getElementById('compareTableBody');
            const tfoot = document.getElementById('compareTableFooter');

            if (!matrix || matrix.length === 0 || !subSystems || subSystems.length === 0) {
                tbody.innerHTML = `<tr><td colspan="10" class="text-muted py-3">Không có dữ liệu thống kê.</td></tr>`;
                tfoot.innerHTML = '';
                return;
            }

            let thCols = '<th class="text-start" style="width: 140px;">Kỳ</th>';
            subSystems.forEach(item => {
                thCols += `<th style="width: 55px;" title="${escapeHtml(item.system_name)}">${item.key})</th>`;
            });
            thCols += '<th style="width: 80px;">Tổng</th><th class="text-end" style="width: 130px;">Kinh phí</th>';
            thead.innerHTML = `<tr>${thCols}</tr>`;

            let htmlBody = '';
            let colSums = {};
            subSystems.forEach(item => colSums[item.key] = 0);
            let grandTotal = 0;
            let grandBudget = 0;

            matrix.forEach(r => {
                grandTotal += r.total;
                grandBudget += r.budget;

                let rowCols = `<td class="text-start fw-bold">${escapeHtml(r.period)}</td>`;
                subSystems.forEach(item => {
                    const val = r[item.key] || 0;
                    colSums[item.key] += val;
                    rowCols += `<td>${val || '–'}</td>`;
                });

                const bTxt = r.budget > 0 ? (r.budget / 1e9).toLocaleString('vi-VN', {
                    maximumFractionDigits: 2
                }) + ' tỷ đ' : '–';
                rowCols += `<td class="fw-bold fs-6 text-primary">${r.total}</td><td class="text-end fw-semibold">${bTxt}</td>`;

                htmlBody += `<tr>${rowCols}</tr>`;
            });
            tbody.innerHTML = htmlBody;

            const budInBillion = (grandBudget / 1e9).toLocaleString('vi-VN', {
                maximumFractionDigits: 1
            });
            document.getElementById('lblCompareSummary').innerText = `${grandTotal} nhiệm vụ · ${budInBillion} tỷ đ`;

            let footerCols = '<td class="text-start">Cộng</td>';
            subSystems.forEach(item => {
                footerCols += `<td>${colSums[item.key]}</td>`;
            });
            footerCols += `<td class="fs-6 text-primary">${grandTotal}</td><td class="text-end text-primary">${(grandBudget / 1e9).toLocaleString('vi-VN', { maximumFractionDigits: 2 })} tỷ đ</td>`;
            tfoot.innerHTML = `<tr>${footerCols}</tr>`;
        }

        function renderOldTables(typeStats) {
            const countTbody = document.getElementById('tableTypeCountBody');
            const financialTbody = document.getElementById('tableFinancialBody');
            const financialFooter = document.getElementById('tableFinancialFooter');

            if (!typeStats || typeStats.length === 0) {
                countTbody.innerHTML = `<tr><td colspan="6" class="text-muted py-4">Chưa có dữ liệu thống kê loại đề tài.</td></tr>`;
                financialTbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">Chưa có dữ liệu tài chính.</td></tr>`;
                financialFooter.innerHTML = '';
                return;
            }

            let htmlCount = '';
            let htmlFinancial = '';
            let sumApproved = 0;
            let sumPaid = 0;

            typeStats.forEach(item => {
                const pending = parseInt(item.pending_count) || 0;
                const running = parseInt(item.running_count) || 0;
                const awaiting = parseInt(item.awaiting_payment_count) || 0;
                const completed = parseInt(item.completed_count) || 0;
                const totalCount = parseInt(item.total_count) || 0;

                const approved = parseFloat(item.total_approved_budget) || 0;
                const paid = parseFloat(item.total_paid_amount) || 0;
                const remaining = Math.max(0, approved - paid);
                const progressPercent = approved > 0 ? Math.min(Math.round((paid / approved) * 100), 100) : 0;

                sumApproved += approved;
                sumPaid += paid;

                htmlCount += `
                    <tr>
                        <td class="text-start fw-bold">${escapeHtml(item.type_name)}</td>
                        <td>${pending}</td>
                        <td><span class="text-warning fw-bold">${running}</span></td>
                        <td>${awaiting}</td>
                        <td><span class="text-success fw-bold">${completed}</span></td>
                        <td><span class="badge bg-primary fs-6">${totalCount}</span></td>
                    </tr>
                `;

                let barColor = 'bg-success';
                if (progressPercent < 50) barColor = 'bg-danger';
                else if (progressPercent < 80) barColor = 'bg-warning';

                htmlFinancial += `
                    <tr>
                        <td class="fw-bold">${escapeHtml(item.type_name)}</td>
                        <td class="text-end fw-semibold">${formatCurrency(approved)}</td>
                        <td class="text-end text-success fw-bold">${formatCurrency(paid)}</td>
                        <td class="text-end text-danger">${formatCurrency(remaining)}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar ${barColor}" style="width: ${progressPercent}%;"></div>
                                </div>
                                <span class="small fw-bold">${progressPercent}%</span>
                            </div>
                        </td>
                    </tr>
                `;
            });

            countTbody.innerHTML = htmlCount;
            financialTbody.innerHTML = htmlFinancial;

            const totalRemaining = Math.max(0, sumApproved - sumPaid);
            const totalProgress = sumApproved > 0 ? Math.min(Math.round((sumPaid / sumApproved) * 100), 100) : 0;

            financialFooter.innerHTML = `
                <tr>
                    <td>TỔNG CỘNG HOẠT ĐỘNG:</td>
                    <td class="text-end text-primary">${formatCurrency(sumApproved)}</td>
                    <td class="text-end text-success">${formatCurrency(sumPaid)}</td>
                    <td class="text-end text-danger">${formatCurrency(totalRemaining)}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                <div class="progress-bar bg-primary" style="width: ${totalProgress}%;"></div>
                            </div>
                            <span class="small">${totalProgress}%</span>
                        </div>
                    </td>
                </tr>
            `;
        }

        // =========================================================================
        // HÀM XUẤT EXCEL THIẾT KẾ ĐẲNG CẤP DÙNG EXCELJS (CHUẨN .XLSX 100%)
        // =========================================================================
        async function exportToExcel() {
            if (!cachedDashboardData || !cachedDashboardData.data) {
                alert("Chưa có dữ liệu thống kê để xuất!");
                return;
            }

            if (typeof ExcelJS === 'undefined') {
                alert("Đang tải thư viện tạo bảng tính ExcelJS, vui lòng thử lại sau 2 giây!");
                return;
            }

            const btn = event.currentTarget || document.querySelector('button[onclick="exportToExcel()"]');
            const oldHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Đang kết xuất .xlsx...`;
            }

            try {
                const data = cachedDashboardData.data;
                const matrix = data.comparison_matrix || [];
                const subSystems = data.sub_systems || [];
                const projectList = data.project_list || [];
                const overview = data.overview || {};
                const unitName = cachedDashboardData.unit_name || 'Toàn Học viện';

                const granEl = document.getElementById('selCompareGran');
                const granText = granEl ? granEl.options[granEl.selectedIndex].text : 'So sánh các năm';
                const basisEl = document.getElementById('selCompareBasis');
                const basisText = basisEl ? basisEl.options[basisEl.selectedIndex].text : 'Ngày nộp hồ sơ';

                const curDate = new Date();
                const dateStr = `ngày ${curDate.getDate()} tháng ${curDate.getMonth() + 1} năm ${curDate.getFullYear()}`;

                // Khởi tạo Workbook
                const workbook = new ExcelJS.Workbook();
                workbook.creator = "Hệ thống QLNCKH";
                workbook.created = new Date();

                // Định nghĩa bảng màu và đường viền dùng chung
                const COLOR_NAVY = 'FF1E3A8A';
                const COLOR_BLUE_ACCENT = 'FF2563EB';
                const COLOR_BG_LIGHT = 'FFF8FAFC';
                const COLOR_ZEBRA = 'FFF1F5F9';
                const COLOR_BORDER = 'FFCBD5E1';
                const COLOR_WHITE = 'FFFFFFFF';

                const thinBorder = {
                    top: {
                        style: 'thin',
                        color: {
                            argb: COLOR_BORDER
                        }
                    },
                    left: {
                        style: 'thin',
                        color: {
                            argb: COLOR_BORDER
                        }
                    },
                    bottom: {
                        style: 'thin',
                        color: {
                            argb: COLOR_BORDER
                        }
                    },
                    right: {
                        style: 'thin',
                        color: {
                            argb: COLOR_BORDER
                        }
                    }
                };

                // =========================================================
                // SHEET 1: BÁO CÁO TỔNG QUAN & MA TRẬN ĐỐI CHIẾU
                // =========================================================
                const ws1 = workbook.addWorksheet('Báo Cáo Tổng Hợp', {
                    views: [{
                        showGridLines: true
                    }]
                });

                const totalCols = subSystems.length + 3;

                // Quốc hiệu & Tên đơn vị
                ws1.mergeCells('A1:C1');
                ws1.getCell('A1').value = 'BỘ CÔNG AN';
                ws1.getCell('A1').font = {
                    name: 'Times New Roman',
                    size: 10,
                    bold: true
                };

                ws1.mergeCells('A2:C2');
                ws1.getCell('A2').value = 'TRƯỜNG ĐẠI HỌC AN NINH NHÂN DÂN';
                ws1.getCell('A2').font = {
                    name: 'Times New Roman',
                    size: 10,
                    bold: true
                };

                ws1.mergeCells('A3:C3');
                ws1.getCell('A3').value = 'PHÒNG QUẢN LÝ NCKH';
                ws1.getCell('A3').font = {
                    name: 'Times New Roman',
                    size: 10,
                    italic: true
                };

                const startColLetter = String.fromCharCode(65 + Math.max(3, totalCols - 4));
                const endColLetter = String.fromCharCode(65 + totalCols - 1);

                ws1.mergeCells(`${startColLetter}1:${endColLetter}1`);
                ws1.getCell(`${startColLetter}1`).value = 'CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM';
                ws1.getCell(`${startColLetter}1`).font = {
                    name: 'Times New Roman',
                    size: 10,
                    bold: true
                };
                ws1.getCell(`${startColLetter}1`).alignment = {
                    horizontal: 'center'
                };

                ws1.mergeCells(`${startColLetter}2:${endColLetter}2`);
                ws1.getCell(`${startColLetter}2`).value = 'Độc lập - Tự do - Hạnh phúc';
                ws1.getCell(`${startColLetter}2`).font = {
                    name: 'Times New Roman',
                    size: 10,
                    bold: true
                };
                ws1.getCell(`${startColLetter}2`).alignment = {
                    horizontal: 'center'
                };

                ws1.mergeCells(`${startColLetter}3:${endColLetter}3`);
                ws1.getCell(`${startColLetter}3`).value = '------------------------';
                ws1.getCell(`${startColLetter}3`).alignment = {
                    horizontal: 'center'
                };

                // Tiêu đề báo cáo
                ws1.mergeCells(`A5:${endColLetter}5`);
                const titleCell = ws1.getCell('A5');
                titleCell.value = 'BÁO CÁO TỔNG QUAN & ĐỐI CHIẾU NHIỆM VỤ NCKH';
                titleCell.font = {
                    name: 'Times New Roman',
                    size: 16,
                    bold: true,
                    color: {
                        argb: COLOR_NAVY
                    }
                };
                titleCell.alignment = {
                    horizontal: 'center',
                    vertical: 'middle'
                };
                ws1.getRow(5).height = 30;

                ws1.mergeCells(`A6:${endColLetter}6`);
                const subTitleCell = ws1.getCell('A6');
                subTitleCell.value = `(Phạm vi: ${unitName.toUpperCase()} — Kỳ phân tích: ${granText} — Tiêu chí: ${basisText})`;
                subTitleCell.font = {
                    name: 'Times New Roman',
                    size: 11,
                    italic: true,
                    color: {
                        argb: 'FF475569'
                    }
                };
                subTitleCell.alignment = {
                    horizontal: 'center',
                    vertical: 'middle'
                };
                ws1.getRow(6).height = 20;

                // Thẻ KPI tổng quan (Dòng 8 - 9)
                const kpiCols = [{
                        title: 'TỔNG ĐỀ TÀI',
                        val: overview.total_projects || 0,
                        color: 'FF1E3A8A',
                        format: '#,##0'
                    },
                    {
                        title: 'ĐANG THỰC HIỆN',
                        val: overview.running_projects || 0,
                        color: 'FFD97706',
                        format: '#,##0'
                    },
                    {
                        title: 'ĐÃ HOÀN THÀNH',
                        val: overview.completed_projects || 0,
                        color: 'FF16A34A',
                        format: '#,##0'
                    },
                    {
                        title: 'TỔNG KINH PHÍ PHÊ DUYỆT',
                        val: parseFloat(overview.total_budget || 0),
                        color: 'FF1E3A8A',
                        format: '#,##0 "VNĐ"'
                    }
                ];

                ws1.getRow(8).height = 24;
                ws1.getRow(9).height = 28;

                // Render 4 khối thẻ KPI
                const colSpans = ['A', 'B', 'C', 'D'];
                kpiCols.forEach((kpi, idx) => {
                    const cLetter = colSpans[idx];
                    const cellTh = ws1.getCell(`${cLetter}8`);
                    cellTh.value = kpi.title;
                    cellTh.font = {
                        name: 'Times New Roman',
                        size: 10,
                        bold: true,
                        color: {
                            argb: COLOR_WHITE
                        }
                    };
                    cellTh.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: COLOR_NAVY
                        }
                    };
                    cellTh.alignment = {
                        horizontal: 'center',
                        vertical: 'middle'
                    };
                    cellTh.border = thinBorder;

                    const cellVal = ws1.getCell(`${cLetter}9`);
                    cellVal.value = kpi.val;
                    cellVal.font = {
                        name: 'Times New Roman',
                        size: 14,
                        bold: true,
                        color: {
                            argb: kpi.color
                        }
                    };
                    cellVal.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: COLOR_BG_LIGHT
                        }
                    };
                    cellVal.alignment = {
                        horizontal: 'center',
                        vertical: 'middle'
                    };
                    cellVal.numFmt = kpi.format;
                    cellVal.border = thinBorder;
                });

                // Bảng ma trận đối chiếu (Dòng 12 trở đi)
                ws1.getCell('A11').value = 'I. MA TRẬN ĐỐI CHIẾU SỐ LƯỢNG VÀ KINH PHÍ THEO TỪNG KỲ';
                ws1.getCell('A11').font = {
                    name: 'Times New Roman',
                    size: 12,
                    bold: true,
                    color: {
                        argb: COLOR_NAVY
                    }
                };

                const matrixHeaders = ['Kỳ Thống Kê', ...subSystems.map(s => `${s.key}) ${s.system_name}`), 'Tổng Đề Tài', 'Tổng Kinh Phí'];
                const rowHeader12 = ws1.getRow(12);
                rowHeader12.height = 28;

                matrixHeaders.forEach((hText, idx) => {
                    const cell = rowHeader12.getCell(idx + 1);
                    cell.value = hText;
                    cell.font = {
                        name: 'Times New Roman',
                        size: 11,
                        bold: true,
                        color: {
                            argb: COLOR_WHITE
                        }
                    };
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: COLOR_NAVY
                        }
                    };
                    cell.alignment = {
                        horizontal: 'center',
                        vertical: 'middle',
                        wrapText: true
                    };
                    cell.border = thinBorder;
                });

                // Nạp dữ liệu ma trận
                matrix.forEach((r, idx) => {
                    const rowNum = 13 + idx;
                    const row = ws1.getRow(rowNum);
                    row.height = 22;
                    const isZebra = (idx % 2 === 1);
                    const rowFill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: isZebra ? COLOR_ZEBRA : COLOR_WHITE
                        }
                    };

                    const rowData = [
                        r.period,
                        ...subSystems.map(s => r[s.key] || 0),
                        r.total || 0,
                        parseFloat(r.budget || 0)
                    ];

                    rowData.forEach((val, cIdx) => {
                        const cell = row.getCell(cIdx + 1);
                        cell.value = (typeof val === 'number' && val === 0 && cIdx > 0 && cIdx <= subSystems.length) ? '–' : val;
                        cell.font = {
                            name: 'Times New Roman',
                            size: 11,
                            color: {
                                argb: 'FF000000'
                            }
                        };
                        cell.fill = rowFill;
                        cell.border = thinBorder;

                        if (cIdx === 0) {
                            cell.alignment = {
                                horizontal: 'center',
                                vertical: 'middle'
                            };
                            cell.font = {
                                name: 'Times New Roman',
                                size: 11,
                                bold: true
                            };
                        } else if (cIdx === rowData.length - 1) {
                            cell.alignment = {
                                horizontal: 'right',
                                vertical: 'middle'
                            };
                            cell.numFmt = '#,##0 "VNĐ"';
                            cell.font = {
                                name: 'Times New Roman',
                                size: 11,
                                bold: true
                            };
                        } else if (cIdx === rowData.length - 2) {
                            cell.alignment = {
                                horizontal: 'center',
                                vertical: 'middle'
                            };
                            cell.font = {
                                name: 'Times New Roman',
                                size: 11,
                                bold: true,
                                color: {
                                    argb: COLOR_BLUE_ACCENT
                                }
                            };
                        } else {
                            cell.alignment = {
                                horizontal: 'center',
                                vertical: 'middle'
                            };
                        }
                    });
                });

                // Dòng tổng cộng ma trận
                const totalRowNum = 13 + matrix.length;
                const totalRow = ws1.getRow(totalRowNum);
                totalRow.height = 24;

                totalRow.getCell(1).value = 'TỔNG CỘNG';
                totalRow.getCell(1).font = {
                    name: 'Times New Roman',
                    size: 11,
                    bold: true,
                    color: {
                        argb: COLOR_NAVY
                    }
                };
                totalRow.getCell(1).alignment = {
                    horizontal: 'center',
                    vertical: 'middle'
                };
                totalRow.getCell(1).fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: {
                        argb: COLOR_ZEBRA
                    }
                };
                totalRow.getCell(1).border = thinBorder;

                subSystems.forEach((s, sIdx) => {
                    const colLetter = String.fromCharCode(66 + sIdx);
                    const cell = totalRow.getCell(sIdx + 2);
                    cell.value = {
                        formula: `SUM(${colLetter}13:${colLetter}${totalRowNum - 1})`
                    };
                    cell.font = {
                        name: 'Times New Roman',
                        size: 11,
                        bold: true
                    };
                    cell.alignment = {
                        horizontal: 'center',
                        vertical: 'middle'
                    };
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: COLOR_ZEBRA
                        }
                    };
                    cell.border = thinBorder;
                });

                const totalColLetter = String.fromCharCode(66 + subSystems.length);
                const budColLetter = String.fromCharCode(67 + subSystems.length);

                const cellGrandTotal = totalRow.getCell(subSystems.length + 2);
                cellGrandTotal.value = {
                    formula: `SUM(${totalColLetter}13:${totalColLetter}${totalRowNum - 1})`
                };
                cellGrandTotal.font = {
                    name: 'Times New Roman',
                    size: 12,
                    bold: true,
                    color: {
                        argb: COLOR_NAVY
                    }
                };
                cellGrandTotal.alignment = {
                    horizontal: 'center',
                    vertical: 'middle'
                };
                cellGrandTotal.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: {
                        argb: COLOR_ZEBRA
                    }
                };
                cellGrandTotal.border = thinBorder;

                const cellGrandBud = totalRow.getCell(subSystems.length + 3);
                cellGrandBud.value = {
                    formula: `SUM(${budColLetter}13:${budColLetter}${totalRowNum - 1})`
                };
                cellGrandBud.font = {
                    name: 'Times New Roman',
                    size: 11,
                    bold: true,
                    color: {
                        argb: COLOR_NAVY
                    }
                };
                cellGrandBud.alignment = {
                    horizontal: 'right',
                    vertical: 'middle'
                };
                cellGrandBud.numFmt = '#,##0 "VNĐ"';
                cellGrandBud.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: {
                        argb: COLOR_ZEBRA
                    }
                };
                cellGrandBud.border = thinBorder;

                // Căn chỉnh độ rộng cột Sheet 1
                ws1.getColumn(1).width = 18;
                subSystems.forEach((s, idx) => {
                    ws1.getColumn(idx + 2).width = 22;
                });
                ws1.getColumn(subSystems.length + 2).width = 14;
                ws1.getColumn(subSystems.length + 3).width = 24;

                // =========================================================
                // SHEET 2: DANH SÁCH CHI TIẾT TỪNG NHIỆM VỤ
                // =========================================================
                const ws2 = workbook.addWorksheet('Danh Sách Chi Tiết Đề Tài', {
                    views: [{
                        showGridLines: true
                    }]
                });

                ws2.mergeCells('A1:J1');
                const s2Title = ws2.getCell('A1');
                s2Title.value = 'DANH SÁCH CHI TIẾT CÁC NHIỆM VỤ NGHIÊN CỨU KHOA HỌC & ĐMST';
                s2Title.font = {
                    name: 'Times New Roman',
                    size: 15,
                    bold: true,
                    color: {
                        argb: COLOR_NAVY
                    }
                };
                s2Title.alignment = {
                    horizontal: 'center',
                    vertical: 'middle'
                };
                ws2.getRow(1).height = 28;

                ws2.mergeCells('A2:J2');
                const s2Sub = ws2.getCell('A2');
                s2Sub.value = `Dữ liệu kết xuất từ Hệ thống Quản lý NCKH - Ngày ${curDate.toLocaleDateString('vi-VN')}`;
                s2Sub.font = {
                    name: 'Times New Roman',
                    size: 11,
                    italic: true,
                    color: {
                        argb: 'FF475569'
                    }
                };
                s2Sub.alignment = {
                    horizontal: 'center',
                    vertical: 'middle'
                };

                const headersDetail = ['STT', 'Mã Đề Tài', 'Tên Nhiệm Vụ Nghiên Cứu', 'Loại Đề Tài', 'Chủ Nhiệm / Phụ Trách', 'Khoa / Đơn Vị', 'Trạng Thái', 'Kinh Phí Phê Duyệt', 'Đã Thanh Toán', 'Còn Lại'];
                const rHeader4 = ws2.getRow(4);
                rHeader4.height = 26;

                headersDetail.forEach((h, idx) => {
                    const cell = rHeader4.getCell(idx + 1);
                    cell.value = h;
                    cell.font = {
                        name: 'Times New Roman',
                        size: 11,
                        bold: true,
                        color: {
                            argb: COLOR_WHITE
                        }
                    };
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: COLOR_NAVY
                        }
                    };
                    cell.alignment = {
                        horizontal: 'center',
                        vertical: 'middle'
                    };
                    cell.border = thinBorder;
                });

                projectList.forEach((p, idx) => {
                    const rowNum = 5 + idx;
                    const row = ws2.getRow(rowNum);
                    row.height = 22;
                    const isZebra = (idx % 2 === 1);
                    const rowFill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: isZebra ? COLOR_ZEBRA : COLOR_WHITE
                        }
                    };

                    const approved = parseFloat(p.approved_budget || p.estimated_budget || 0);
                    const paid = parseFloat(p.total_paid || 0);

                    let stText = 'Chờ duyệt',
                        stColor = 'FFD97706';
                    const st = (p.status || '').toLowerCase();
                    if (st === 'running') {
                        stText = 'Đang tiến hành';
                        stColor = 'FF2563EB';
                    } else if (st === 'completed') {
                        stText = 'Đã hoàn thành';
                        stColor = 'FF16A34A';
                    } else if (st === 'awaiting_payment') {
                        stText = 'Chờ thanh toán';
                        stColor = 'FF7C3AED';
                    }

                    const rowData = [
                        idx + 1,
                        p.project_code || ('NV' + p.project_id),
                        p.project_name || '---',
                        p.type_name || p.sub_system_name || '---',
                        p.leader_name || p.action_user_name || 'Chưa phân công',
                        p.leader_unit || 'Trực thuộc trường',
                        stText,
                        approved,
                        paid,
                        Math.max(0, approved - paid)
                    ];

                    rowData.forEach((val, cIdx) => {
                        const cell = row.getCell(cIdx + 1);
                        cell.value = val;
                        cell.font = {
                            name: 'Times New Roman',
                            size: 11
                        };
                        cell.fill = rowFill;
                        cell.border = thinBorder;

                        if (cIdx === 0 || cIdx === 1) {
                            cell.alignment = {
                                horizontal: 'center',
                                vertical: 'middle'
                            };
                            if (cIdx === 1) cell.font = {
                                name: 'Times New Roman',
                                size: 11,
                                bold: true,
                                color: {
                                    argb: COLOR_NAVY
                                }
                            };
                        } else if (cIdx === 6) {
                            cell.alignment = {
                                horizontal: 'center',
                                vertical: 'middle'
                            };
                            cell.font = {
                                name: 'Times New Roman',
                                size: 11,
                                bold: true,
                                color: {
                                    argb: stColor
                                }
                            };
                        } else if (cIdx >= 7) {
                            cell.alignment = {
                                horizontal: 'right',
                                vertical: 'middle'
                            };
                            cell.numFmt = '#,##0 "VNĐ"';
                        } else {
                            cell.alignment = {
                                horizontal: 'left',
                                vertical: 'middle'
                            };
                        }
                    });
                });

                // Set độ rộng cột Sheet 2
                const colWidths = [6, 16, 42, 22, 26, 24, 18, 22, 20, 20];
                colWidths.forEach((w, idx) => {
                    ws2.getColumn(idx + 1).width = w;
                });

                // Tạo Buffer và tải file về máy
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                const downloadUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = downloadUrl;
                a.download = `Bao_Cao_Tong_Hop_NCKH_${new Date().toISOString().slice(0, 10)}.xlsx`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(downloadUrl);

            } catch (err) {
                console.error("Lỗi xuất Excel:", err);
                alert("Lỗi xuất Excel: " + err.message);
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = oldHtml;
                }
            }
        }

        function formatCurrency(amount) {
            if (!amount) return '0 VNĐ';
            return Number(amount).toLocaleString('vi-VN') + ' VNĐ';
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>
</body>

</html>