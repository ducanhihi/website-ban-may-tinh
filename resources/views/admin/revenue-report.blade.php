@extends('layout.app')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-dark fw-bold">Báo cáo Doanh thu</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{asset('admin/home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Báo cáo Doanh thu</li>
                    </ol>
                </nav>
            </div>

            <!-- Date Range Picker -->
            <div class="d-flex gap-2 align-items-center">
                <div class="input-group" style="width: 300px;">
                    <input type="date" class="form-control border-0 shadow-sm" id="startDate" value="{{ $startDate->format('Y-m-d') }}">
                    <span class="input-group-text bg-white border-0">đến</span>
                    <input type="date" class="form-control border-0 shadow-sm" id="endDate" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <button class="btn btn-primary rounded-pill px-4" id="applyFilter">
                    <i class="bi bi-search me-1"></i>Lọc
                </button>
            </div>
        </div>

        <section class="section revenue-report">
            <!-- Quick Stats -->
            <div class="row g-4 mb-4">
                <!-- Tổng doanh thu -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 modern-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-box bg-success-light">
                                    <i class="bi bi-currency-dollar text-success"></i>
                                </div>
                                <div class="text-end">
                                    <h4 class="fw-bold mb-0 text-dark" id="totalRevenue">{{ number_format($reportData['totalRevenue'], 0, ',', '.') }} VND</h4>
                                </div>
                            </div>
                            <h6 class="text-muted mb-2 fw-normal">Tổng doanh thu dự kiến</h6>
                            <div class="d-flex align-items-center" id="revenueChange">
                                @if ($reportData['revenueChange'] > 0)
                                    <span class="badge bg-success-soft text-success me-2">
                                    <i class="bi bi-arrow-up-short"></i>+{{ $reportData['revenueChange'] }}%
                                </span>
                                    <small class="text-success">so với kỳ trước</small>
                                @elseif ($reportData['revenueChange'] < 0)
                                    <span class="badge bg-danger-soft text-danger me-2">
                                    <i class="bi bi-arrow-down-short"></i>{{ abs($reportData['revenueChange']) }}%
                                </span>
                                    <small class="text-danger">so với kỳ trước</small>
                                @else
                                    <span class="badge bg-secondary-soft text-secondary me-2">
                                    <i class="bi bi-dash"></i>0%
                                </span>
                                    <small class="text-muted">so với kỳ trước</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Số đơn hàng -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 modern-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-box bg-primary-light">
                                    <i class="bi bi-cart-check text-primary"></i>
                                </div>
                                <div class="text-end">
                                    <h2 class="fw-bold mb-0 text-dark" id="totalOrders">{{ number_format($reportData['totalOrders']) }}</h2>
                                </div>
                            </div>
                            <h6 class="text-muted mb-2 fw-normal">Đơn hàng hợp lệ</h6>
                            <div class="d-flex align-items-center" id="ordersChange">
                                @if ($reportData['ordersChange'] > 0)
                                    <span class="badge bg-success-soft text-success me-2">
                                    <i class="bi bi-arrow-up-short"></i>+{{ $reportData['ordersChange'] }}%
                                </span>
                                    <small class="text-success">so với kỳ trước</small>
                                @elseif ($reportData['ordersChange'] < 0)
                                    <span class="badge bg-danger-soft text-danger me-2">
                                    <i class="bi bi-arrow-down-short"></i>{{ abs($reportData['ordersChange']) }}%
                                </span>
                                    <small class="text-danger">so với kỳ trước</small>
                                @else
                                    <span class="badge bg-secondary-soft text-secondary me-2">
                                    <i class="bi bi-dash"></i>0%
                                </span>
                                    <small class="text-muted">so với kỳ trước</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Giá trị đơn hàng trung bình -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 modern-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-box bg-warning-light">
                                    <i class="bi bi-graph-up text-warning"></i>
                                </div>
                                <div class="text-end">
                                    <h6 class="fw-bold mb-0 text-dark" id="averageOrderValue">{{ number_format($reportData['averageOrderValue'], 0, ',', '.') }} VND</h6>
                                </div>
                            </div>
                            <h6 class="text-muted mb-2 fw-normal">Giá trị ĐH trung bình</h6>
                            <small class="text-muted">Từ <span id="orderCount">{{ $reportData['totalOrders'] }}</span> đơn hàng</small>
                        </div>
                    </div>
                </div>

                <!-- Trạng thái đơn hàng -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 modern-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-box bg-info-light">
                                    <i class="bi bi-check-circle text-info"></i>
                                </div>
                                <div class="text-end">
                                    <h6 class="fw-bold mb-0 text-dark">{{ count($reportData['validStatuses']) }}</h6>
                                </div>
                            </div>
                            <h6 class="text-muted mb-2 fw-normal">Trạng thái hợp lệ</h6>
                            <small class="text-muted">{{ implode(', ', $reportData['validStatuses']) }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables Row -->
            <div class="row g-4 mb-4">
                <!-- Revenue Chart -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm modern-card">
                        <div class="card-header bg-white border-0 p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">Biểu đồ Doanh thu theo Ngày</h5>
                                    <p class="text-muted small mb-0">Doanh thu dự kiến từ <span id="chartDateRange">{{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <canvas id="revenueChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Revenue by Status -->
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm modern-card">
                        <div class="card-header bg-white border-0 p-4">
                            <h5 class="fw-bold mb-1 text-dark">Doanh thu theo Trạng thái</h5>
                            <p class="text-muted small mb-0">Phân bổ theo trạng thái đơn hàng</p>
                        </div>
                        <div class="card-body p-4">
                            <canvas id="statusChart" height="250"></canvas>
                            <div class="mt-4" id="statusLegend">
                                @foreach($reportData['revenueByStatus'] as $status)
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="status-dot me-2" style="background-color: {{ $loop->index == 0 ? '#0d6efd' : ($loop->index == 1 ? '#198754' : ($loop->index == 2 ? '#ffc107' : '#dc3545')) }};"></div>
                                            <span class="small">{{ $status->status }}</span>
                                        </div>
                                        <span class="small fw-bold">{{ number_format($status->revenue, 0, ',', '.') }} VND</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tables Row -->
            <div class="row g-4 mb-4">
                <!-- Top Products -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm modern-card">
                        <div class="card-header bg-white border-0 p-4">
                            <h5 class="fw-bold mb-1 text-dark">Sản phẩm bán chạy</h5>
                            <p class="text-muted small mb-0">Top 10 sản phẩm có doanh thu cao nhất</p>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="topProductsTable">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="border-0 ps-4">#</th>
                                        <th class="border-0">Sản phẩm</th>
                                        <th class="border-0 text-center">Đã bán</th>
                                        <th class="border-0 text-end pe-4">Doanh thu</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reportData['topProducts'] as $index => $product)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-primary rounded-circle">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('image/'.$product->image) }}" alt="{{ $product->name }}" class="rounded me-3" width="40" height="40" style="object-fit: cover;">
                                                    <div>
                                                        <div class="fw-medium">{{ $product->name }}</div>
                                                        <small class="text-muted">{{ $product->category_name ?? 'Không phân loại' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success-soft text-success">{{ $product->total_sold }}</span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <span class="fw-bold">{{ number_format($product->total_revenue, 0, ',', '.') }} VND</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue by Category -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm modern-card">
                        <div class="card-header bg-white border-0 p-4">
                            <h5 class="fw-bold mb-1 text-dark">Doanh thu theo Danh mục</h5>
                            <p class="text-muted small mb-0">Phân bổ doanh thu theo từng danh mục</p>
                        </div>
                        <div class="card-body p-4" id="categoryRevenueContent">
                            @foreach($reportData['revenueByCategory'] as $index => $category)
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="me-3">
                                            <span class="badge bg-primary rounded-circle">{{ $index + 1 }}</span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-medium">{{ $category->category_name }}</div>
                                            <div class="progress mt-1" style="height: 6px;">
                                                <div class="progress-bar bg-primary" style="width: {{ ($category->revenue / $reportData['revenueByCategory']->max('revenue')) * 100 }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $category->orders_count }} đơn hàng</small>
                                        </div>
                                    </div>
                                    <div class="text-end ms-3">
                                        <div class="fw-bold">{{ number_format($category->revenue, 0, ',', '.') }} VND</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm modern-card">
                        <div class="card-header bg-white border-0 p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">Đơn hàng gần đây</h5>
                                    <p class="text-muted small mb-0">Danh sách đơn hàng hợp lệ trong khoảng thời gian được chọn</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="recentOrdersTable">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="border-0 ps-4">Mã đơn</th>
                                        <th class="border-0">Khách hàng</th>
                                        <th class="border-0 text-end">Giá trị</th>
                                        <th class="border-0 text-center">Trạng thái</th>
                                        <th class="border-0">Ngày đặt</th>
                                        <th class="border-0 text-center pe-4">Thao tác</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reportData['recentOrders'] as $order)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-bold text-primary">#{{ $order->id }}</span>
                                            </td>
                                            <td>
                                                <span class="text-dark">{{ $order->name ?? 'Khách hàng' }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold text-success">{{ number_format($order->total, 0, ',', '.') }} VND</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $statusClass = '';
                                                    switch($order->status) {
                                                        case 'Đã giao':
                                                        case 'Hoàn thành':
                                                            $statusClass = 'bg-success text-white';
                                                            break;
                                                        case 'Đang xử lý':
                                                            $statusClass = 'bg-warning text-dark';
                                                            break;
                                                        case 'Đã xác nhận':
                                                            $statusClass = 'bg-primary text-white';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-secondary text-white';
                                                    }
                                                @endphp
                                                <span class="badge {{ $statusClass }} rounded-pill">{{ $order->status }}</span>
                                            </td>
                                            <td>
                                                <div class="small">{{ \Carbon\Carbon::parse($order->order_date)->format('H:i d/m/Y') }}</div>
                                            </td>
                                            <td class="text-center pe-4">
                                                <a href="#" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let revenueChart, statusChart;

        document.addEventListener("DOMContentLoaded", function() {
            initializeCharts();

            // Event listeners
            document.getElementById('applyFilter').addEventListener('click', applyDateFilter);
            document.getElementById('startDate').addEventListener('change', validateDateRange);
            document.getElementById('endDate').addEventListener('change', validateDateRange);
        });

        function initializeCharts() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const dailyData = @json($reportData['dailyRevenue']);

            revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: dailyData.map(item => new Date(item.date).toLocaleDateString('vi-VN')),
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: dailyData.map(item => item.revenue),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#0d6efd',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#0d6efd',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VND';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', {
                                        notation: 'compact',
                                        compactDisplay: 'short'
                                    }).format(value) + ' VND';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusData = @json($reportData['revenueByStatus']);

            statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusData.map(item => item.status),
                    datasets: [{
                        data: statusData.map(item => item.revenue),
                        backgroundColor: [
                            '#0d6efd',
                            '#198754',
                            '#ffc107',
                            '#dc3545',
                            '#6f42c1'
                        ],
                        borderWidth: 0,
                        cutout: '60%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + new Intl.NumberFormat('vi-VN').format(context.parsed) + ' VND (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        function applyDateFilter() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                alert('Vui lòng chọn đầy đủ ngày bắt đầu và ngày kết thúc');
                return;
            }

            if (new Date(startDate) > new Date(endDate)) {
                alert('Ngày bắt đầu không thể lớn hơn ngày kết thúc');
                return;
            }

            showLoading();

            fetch(`/admin/sales-report-ajax?start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    updateDashboard(data);
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideLoading();
                    alert('Có lỗi xảy ra khi tải dữ liệu');
                });
        }

        function updateDashboard(data) {
            // Update stats cards
            document.getElementById('totalRevenue').textContent = new Intl.NumberFormat('vi-VN').format(data.totalRevenue) + ' VND';
            document.getElementById('totalOrders').textContent = new Intl.NumberFormat('vi-VN').format(data.totalOrders);
            document.getElementById('averageOrderValue').textContent = new Intl.NumberFormat('vi-VN').format(data.averageOrderValue) + ' VND';
            document.getElementById('orderCount').textContent = data.totalOrders;

            // Update change indicators
            updateChangeIndicator('revenueChange', data.revenueChange, 'so với kỳ trước');
            updateChangeIndicator('ordersChange', data.ordersChange, 'so với kỳ trước');

            // Update chart date range
            const startDate = new Date(data.startDate).toLocaleDateString('vi-VN');
            const endDate = new Date(data.endDate).toLocaleDateString('vi-VN');
            document.getElementById('chartDateRange').textContent = `${startDate} - ${endDate}`;

            // Update charts
            updateCharts(data);
        }

        function updateChangeIndicator(elementId, change, suffix) {
            const element = document.getElementById(elementId);
            let badgeClass, iconClass, textClass;

            if (change > 0) {
                badgeClass = 'bg-success-soft text-success';
                iconClass = 'bi-arrow-up-short';
                textClass = 'text-success';
            } else if (change < 0) {
                badgeClass = 'bg-danger-soft text-danger';
                iconClass = 'bi-arrow-down-short';
                textClass = 'text-danger';
            } else {
                badgeClass = 'bg-secondary-soft text-secondary';
                iconClass = 'bi-dash';
                textClass = 'text-muted';
            }

            element.innerHTML = `
            <span class="badge ${badgeClass} me-2">
                <i class="bi ${iconClass}"></i>${change > 0 ? '+' : ''}${Math.abs(change)}%
            </span>
            <small class="${textClass}">${suffix}</small>
        `;
        }

        function updateCharts(data) {
            // Update revenue chart
            revenueChart.data.labels = data.dailyRevenue.map(item => new Date(item.date).toLocaleDateString('vi-VN'));
            revenueChart.data.datasets[0].data = data.dailyRevenue.map(item => item.revenue);
            revenueChart.update();

            // Update status chart
            statusChart.data.labels = data.revenueByStatus.map(item => item.status);
            statusChart.data.datasets[0].data = data.revenueByStatus.map(item => item.revenue);
            statusChart.update();
        }

        function validateDateRange() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                document.getElementById('endDate').value = startDate;
            }
        }

        function showLoading() {
            const loadingOverlay = document.createElement('div');
            loadingOverlay.id = 'loadingOverlay';
            loadingOverlay.className = 'loading-overlay';
            loadingOverlay.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;
            document.body.appendChild(loadingOverlay);
        }

        function hideLoading() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                loadingOverlay.remove();
            }
        }
    </script>

    <style>
        /* Modern Dashboard Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .modern-card {
            border-radius: 16px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.1) !important;
        }

        /* Icon boxes */
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .bg-primary-light { background-color: rgba(13, 110, 253, 0.1); }
        .bg-success-light { background-color: rgba(25, 135, 84, 0.1); }
        .bg-warning-light { background-color: rgba(255, 193, 7, 0.1); }
        .bg-info-light { background-color: rgba(13, 202, 240, 0.1); }

        /* Soft badges */
        .bg-success-soft {
            background-color: rgba(25, 135, 84, 0.1) !important;
            color: #198754 !important;
            border: none;
        }

        .bg-danger-soft {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
            border: none;
        }

        .bg-secondary-soft {
            background-color: rgba(108, 117, 125, 0.1) !important;
            color: #6c757d !important;
            border: none;
        }

        /* Status dots */
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        /* Table improvements */
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .table-hover > tbody > tr:hover > * {
            background-color: rgba(13, 110, 253, 0.02);
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid rgba(0,0,0,0.05);
            font-weight: 600;
            color: #6c757d;
            font-size: 0.875rem;
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        /* Form controls */
        .form-control {
            border-radius: 8px;
            border-color: rgba(0,0,0,0.1);
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        }

        /* Progress bars */
        .progress {
            border-radius: 10px;
            background-color: rgba(13, 110, 253, 0.1);
        }

        .progress-bar {
            border-radius: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modern-card {
                border-radius: 12px;
            }

            .icon-box {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .card-body {
                padding: 1.5rem !important;
            }
        }
    </style>
@endsection
