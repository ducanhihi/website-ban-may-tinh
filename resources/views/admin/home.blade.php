

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống Kê Doanh Thu Nâng Cao</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .tab-btn.active {
            border-bottom-color: #3b82f6 !important;
            color: #3b82f6 !important;
        }
    </style>
</head>
@extends('layout.app')

@section('content')
    <body class="bg-gray-50">
    <div class="min-h-screen p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Thống Kê Doanh Thu Nâng Cao</h1>
                <p class="text-gray-600">Theo dõi và so sánh doanh thu bán hàng chi tiết</p>
            </div>

            <!-- Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button class="tab-btn active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600" data-tab="overview">
                            <i class="fas fa-chart-line mr-2"></i>Tổng Quan
                        </button>
                        <button class="tab-btn py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="comparison">
                            <i class="fas fa-exchange-alt mr-2"></i>So Sánh
                        </button>
                        <button class="tab-btn py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="products">
                            <i class="fas fa-box mr-2"></i>Sản Phẩm & Đơn Hàng
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loading" class="hidden text-center py-8">
                <i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i>
                <p class="mt-2 text-gray-600">Đang tải dữ liệu...</p>
            </div>

            <!-- Overview Tab -->
            <div id="overview-tab" class="tab-content active">
                <!-- Date Filter -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                        Lọc Theo Thời Gian
                    </h2>
                    <div class="flex flex-col sm:flex-row gap-4 items-end">
                        <div class="flex-1">
                            <label for="start-date" class="block text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
                            <input type="date" id="start-date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="flex-1">
                            <label for="end-date" class="block text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
                            <input type="date" id="end-date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button id="filter-btn" class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                            <i class="fas fa-search mr-2"></i>Lọc
                        </button>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="stats-cards">
                    <!-- Cards will be populated by JavaScript -->
                </div>

                <!-- Revenue Chart -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold mb-4">Biểu Đồ Doanh Thu Theo Ngày</h2>
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>

                <!-- Detailed Revenue Table -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold mb-4">Chi Tiết Doanh Thu Theo Ngày</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse" id="revenue-table">
                            <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="text-left p-4 font-semibold">Ngày</th>
                                <th class="text-right p-4 font-semibold">Doanh Thu</th>
                                <th class="text-right p-4 font-semibold">Số Đơn Hàng</th>
                                <th class="text-right p-4 font-semibold">Giá Trị TB/Đơn</th>
                            </tr>
                            </thead>
                            <tbody id="revenue-table-body">
                            <!-- Table rows will be populated by JavaScript -->
                            </tbody>
                            <tfoot id="revenue-table-footer">
                            <!-- Footer will be populated by JavaScript -->
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Comparison Tab -->
            <div id="comparison-tab" class="tab-content">
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-exchange-alt mr-2 text-green-500"></i>
                        So Sánh Doanh Thu
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Period 1 -->
                        <div class="space-y-4">
                            <h3 class="font-semibold text-lg text-blue-600">Kỳ 1</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tháng</label>
                                    <select id="period1-month" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $i == 5 ? 'selected' : '' }}>Tháng {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Năm</label>
                                    <select id="period1-year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025" selected>2025</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Period 2 -->
                        <div class="space-y-4">
                            <h3 class="font-semibold text-lg text-green-600">Kỳ 2</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tháng</label>
                                    <select id="period2-month" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $i == 6 ? 'selected' : '' }}>Tháng {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Năm</label>
                                    <select id="period2-year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025" selected>2025</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button id="compare-btn" class="w-full px-6 py-3 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors font-semibold">
                        <i class="fas fa-chart-bar mr-2"></i>So Sánh
                    </button>
                </div>

                <!-- Comparison Results -->
                <div id="comparison-results" class="hidden space-y-6">
                    <!-- Results will be populated by JavaScript -->
                </div>
            </div>

            <!-- Products & Orders Tab -->
            <div id="products-tab" class="tab-content">
                <!-- Top Products with Time Filter -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                        Top 5 Sản Phẩm Bán Chạy
                    </h2>

                    <!-- Time Filter for Top Products -->
                    <div class="flex flex-col sm:flex-row gap-4 items-end mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tháng</label>
                            <select id="top-products-month" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>Tháng {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Năm</label>
                            <select id="top-products-year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025" selected>2025</option>
                            </select>
                        </div>
                        <button id="filter-top-products-btn" class="px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                            <i class="fas fa-filter mr-2"></i>Lọc
                        </button>
                    </div>

                    <div id="top-products-list" class="space-y-4">
                        <!-- Products will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                        5 Đơn Hàng Mới Nhất
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse" id="recent-orders-table">
                            <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="text-left p-4 font-semibold">Mã ĐH</th>
                                <th class="text-left p-4 font-semibold">Khách Hàng</th>
                                <th class="text-right p-4 font-semibold">Tổng Tiền</th>
                                <th class="text-center p-4 font-semibold">Trạng Thái</th>
                                <th class="text-center p-4 font-semibold">SP</th>
                                <th class="text-left p-4 font-semibold">Thời Gian</th>
                                <th class="text-left p-4 font-semibold">Xem</th>
                            </tr>
                            </thead>
                            <tbody id="recent-orders-body">
                            <!-- Orders will be populated by JavaScript -->

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let revenueChart = null;
        let currentRevenueData = [];

        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all buttons
                    tabBtns.forEach(b => {
                        b.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        b.classList.add('border-transparent', 'text-gray-500');
                    });

                    // Add active class to clicked button
                    this.classList.add('active', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                    });

                    // Show target tab content
                    document.getElementById(targetTab + '-tab').classList.add('active');
                });
            });

            // Set default dates
            const today = new Date();
            const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));

            document.getElementById('start-date').value = thirtyDaysAgo.toISOString().split('T')[0];
            document.getElementById('end-date').value = today.toISOString().split('T')[0];

            // Load initial data
            loadRevenueData();
            loadTopProducts();
        });

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(amount);
        }

        // Format date
        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('vi-VN');
        }

        // Format datetime
        function formatDateTime(dateString) {
            return new Date(dateString).toLocaleString('vi-VN');
        }

        // Get status color
        function getStatusColor(status) {
            switch (status) {
                case 'Đã giao': return 'text-green-600 bg-green-100';
                case 'Đang giao': return 'text-blue-600 bg-blue-100';
                case 'Đã xác nhận': return 'text-yellow-600 bg-yellow-100';
                case 'Chờ xác nhận': return 'text-orange-600 bg-orange-100';
                default: return 'text-gray-600 bg-gray-100';
            }
        }

        // Show loading
        function showLoading() {
            document.getElementById('loading').classList.remove('hidden');
        }

        // Hide loading
        function hideLoading() {
            document.getElementById('loading').classList.add('hidden');
        }

        // Load revenue data
        async function loadRevenueData() {
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;

            if (!startDate || !endDate) {
                alert('Vui lòng chọn ngày bắt đầu và ngày kết thúc');
                return;
            }

            showLoading();

            try {
                const response = await fetch(`/api/revenue-statistics?start_date=${startDate}&end_date=${endDate}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                currentRevenueData = data.revenue_data;

                // Update statistics cards
                updateStatsCards(data.summary);

                // Update chart
                updateChart(data.revenue_data);

                // Update detailed table
                updateDetailedTable(data.revenue_data, data.summary);

                // Update recent orders
                updateRecentOrders(data.recent_orders);

            } catch (error) {
                console.error('Error loading data:', error);
                alert('Có lỗi xảy ra khi tải dữ liệu');
            } finally {
                hideLoading();
            }
        }

        // Load top products
        async function loadTopProducts() {
            const month = document.getElementById('top-products-month').value;
            const year = document.getElementById('top-products-year').value;

            showLoading();

            try {
                const response = await fetch(`/api/top-products?month=${month}&year=${year}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                updateTopProducts(data.top_products, data.period.label);

            } catch (error) {
                console.error('Error loading top products:', error);
                alert('Có lỗi xảy ra khi tải dữ liệu sản phẩm');
            } finally {
                hideLoading();
            }
        }

        // Update statistics cards
        function updateStatsCards(summary) {
            const cardsContainer = document.getElementById('stats-cards');
            cardsContainer.innerHTML = `
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tổng Doanh Thu</p>
                            <p class="text-2xl font-bold text-green-600">${formatCurrency(summary.total_revenue)}</p>
                        </div>
                        <i class="fas fa-dollar-sign text-3xl text-green-500"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tổng Đơn Hàng</p>
                            <p class="text-2xl font-bold text-blue-600">${summary.total_orders.toLocaleString('vi-VN')}</p>
                        </div>
                        <i class="fas fa-shopping-cart text-3xl text-blue-500"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Giá Trị TB/Đơn</p>
                            <p class="text-2xl font-bold text-purple-600">${formatCurrency(summary.average_order_value)}</p>
                        </div>
                        <i class="fas fa-chart-line text-3xl text-purple-500"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Doanh Thu TB/Ngày</p>
                            <p class="text-2xl font-bold text-orange-600">${formatCurrency(summary.average_daily_revenue)}</p>
                        </div>
                        <i class="fas fa-calendar-day text-3xl text-orange-500"></i>
                    </div>
                </div>
            `;
        }

        // Update chart
        function updateChart(revenueData) {
            const ctx = document.getElementById('revenueChart').getContext('2d');

            if (revenueChart) {
                revenueChart.destroy();
            }

            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: revenueData.map(item => formatDate(item.date)),
                    datasets: [{
                        label: 'Doanh Thu',
                        data: revenueData.map(item => item.revenue),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Doanh thu: ' + formatCurrency(context.parsed.y);
                                }
                            }
                        }
                    }
                }
            });
        }

        // Update detailed table
        function updateDetailedTable(revenueData, summary) {
            const tbody = document.getElementById('revenue-table-body');
            const tfoot = document.getElementById('revenue-table-footer');

            tbody.innerHTML = revenueData.map(item => `
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">${formatDate(item.date)}</td>
                    <td class="p-4 text-right font-semibold text-green-600">${formatCurrency(item.revenue)}</td>
                    <td class="p-4 text-right">${item.orders}</td>
                    <td class="p-4 text-right">${formatCurrency(item.revenue / item.orders)}</td>
                </tr>
            `).join('');

            tfoot.innerHTML = `
                <tr class="border-t-2 bg-gray-50 font-semibold">
                    <td class="p-4">Tổng Cộng</td>
                    <td class="p-4 text-right text-green-600">${formatCurrency(summary.total_revenue)}</td>
                    <td class="p-4 text-right">${summary.total_orders}</td>
                    <td class="p-4 text-right">${formatCurrency(summary.average_order_value)}</td>
                </tr>
            `;
        }

        // Update top products
        function updateTopProducts(topProducts, periodLabel) {
            const container = document.getElementById('top-products-list');

            if (topProducts.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-4"></i>
                        <p>Không có dữ liệu sản phẩm cho ${periodLabel}</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = topProducts.map((product, index) => `
                <div class="flex items-center gap-4 p-4 border rounded-lg hover:bg-gray-50">
                    <div class="text-2xl font-bold text-gray-400 w-8">#${index + 1}</div>
                    <img src="${product.image ? '/storage/' + product.image : '/placeholder.svg?height=60&width=60'}"
                         alt="${product.name}"
                         class="w-16 h-16 object-cover rounded-lg border"
                         onerror="this.src='/placeholder.svg?height=60&width=60'">
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">${product.name}</h3>
                        <p class="text-sm text-gray-600">Đã bán: ${product.total_sold} sản phẩm</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-green-600">${formatCurrency(product.total_revenue)}</p>
                        <p class="text-sm text-gray-600">Doanh thu</p>
                    </div>
                </div>
            `).join('');
        }

        // Update recent orders
        function updateRecentOrders(recentOrders) {
            const tbody = document.getElementById('recent-orders-body');

            if (recentOrders.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                            <p>Không có đơn hàng nào</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = recentOrders.map(order => `
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 font-mono text-blue-600">#${order.id}</td>
                    <td class="p-4 font-medium">${order.customer_name}</td>
                    <td class="p-4 text-right font-semibold text-green-600">${formatCurrency(order.total)}</td>
                    <td class="p-4 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(order.status)}">
                            ${order.status}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                            ${order.products_count}
                        </span>
                    </td>
                    <td class="p-4 text-sm text-gray-600">${formatDateTime(order.order_date)}</td>
                </tr>
            `).join('');
        }

        // Compare revenue
        async function compareRevenue() {
            const period1Month = document.getElementById('period1-month').value;
            const period1Year = document.getElementById('period1-year').value;
            const period2Month = document.getElementById('period2-month').value;
            const period2Year = document.getElementById('period2-year').value;

            showLoading();

            try {
                const response = await fetch('/api/compare-revenue', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        period1_month: period1Month,
                        period1_year: period1Year,
                        period2_month: period2Month,
                        period2_year: period2Year
                    })
                });

                const data = await response.json();
                updateComparisonResults(data);

            } catch (error) {
                console.error('Error comparing data:', error);
                alert('Có lỗi xảy ra khi so sánh dữ liệu');
            } finally {
                hideLoading();
            }
        }

        // Update comparison results
        function updateComparisonResults(data) {
            const container = document.getElementById('comparison-results');
            const revenueDiff = data.comparison.revenue_difference;
            const percentageDiff = data.comparison.percentage_difference;

            container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                        <h3 class="text-xl font-semibold text-blue-600 mb-4">${data.period1.label}</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Doanh thu</p>
                                <p class="text-2xl font-bold">${formatCurrency(data.period1.revenue)}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Số đơn hàng</p>
                                <p class="text-xl font-semibold">${data.period1.orders}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                        <h3 class="text-xl font-semibold text-green-600 mb-4">${data.period2.label}</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Doanh thu</p>
                                <p class="text-2xl font-bold">${formatCurrency(data.period2.revenue)}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Số đơn hàng</p>
                                <p class="text-xl font-semibold">${data.period2.orders}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-4 text-center">Kết Quả So Sánh</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-sm text-gray-600">Chênh lệch doanh thu</p>
                            <p class="text-2xl font-bold ${revenueDiff >= 0 ? 'text-green-600' : 'text-red-600'}">
                                ${revenueDiff >= 0 ? '+' : ''}${formatCurrency(Math.abs(revenueDiff))}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tỷ lệ thay đổi</p>
                            <p class="text-2xl font-bold ${percentageDiff >= 0 ? 'text-green-600' : 'text-red-600'}">
                                ${percentageDiff >= 0 ? '+' : ''}${percentageDiff}%
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kết quả</p>
                            <p class="text-2xl font-bold ${data.comparison.winner === 'period2' ? 'text-green-600' : 'text-blue-600'}">
                                ${data.comparison.winner === 'period2' ? data.period2.label + ' hơn' : data.period1.label + ' hơn'}
                            </p>
                        </div>
                    </div>
                </div>
            `;

            container.classList.remove('hidden');
        }

        // Event listeners
        document.getElementById('filter-btn').addEventListener('click', loadRevenueData);
        document.getElementById('compare-btn').addEventListener('click', compareRevenue);
        document.getElementById('filter-top-products-btn').addEventListener('click', loadTopProducts);
    </script>
    </body>
@endsection
</html>
