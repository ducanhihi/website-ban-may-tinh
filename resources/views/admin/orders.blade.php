@extends('layout.app')

@section('content')
    <main id="main" class="main">
        <!-- Stats Overview Section -->
        <div class="stats-overview-section">
            <div class="stats-overview">
                <div class="stat-item pending">
                    <div class="stat-number">{{ count($pendingOrders) }}</div>
                    <div class="stat-label">Chờ xác nhận</div>
                </div>
                <div class="stat-item confirmed">
                    <div class="stat-number">{{ count($confirmedOrders) }}</div>
                    <div class="stat-label">Đã xác nhận</div>
                </div>
                <div class="stat-item shipping">
                    <div class="stat-number">{{ count($shippingOrders) }}</div>
                    <div class="stat-label">Đang giao</div>
                </div>
                <div class="stat-item completed">
                    <div class="stat-number">{{ count($completedOrders) }}</div>
                    <div class="stat-label">Đã giao</div>
                </div>
                <div class="stat-item canceled">
                    <div class="stat-number">{{ count($canceledOrders) }}</div>
                    <div class="stat-label">Đã hủy</div>
                </div>
                <div class="stat-item received">
                    <div class="stat-number">{{ count($receivedOrders) }}</div>
                    <div class="stat-label">Đã nhận hàng</div>
                </div>
                <div class="stat-item received">
                    <div class="stat-number">{{ count($wait_payment) }}</div>
                    <div class="stat-label">Chờ thanh toán</div>
                </div>

            </div>
        </div>

        <!-- Modern Tab Navigation -->
        <div class="modern-tabs-container">
            <ul class="nav nav-pills modern-nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active modern-tab-btn" id="pills-pending-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-pending" type="button" role="tab" aria-controls="pills-pending"
                            aria-selected="true">
                        <i class="bi bi-clock-history"></i>
                        <span style="color: black">Chờ xác nhận</span>
                        <span class="tab-badge">{{ count($pendingOrders) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link modern-tab-btn" id="pills-confirmed-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-confirmed" type="button" role="tab" aria-controls="pills-confirmed"
                            aria-selected="false">
                        <i class="bi bi-check-circle"></i>
                        <span style="color: black">Đã xác nhận</span>
                        <span class="tab-badge">{{ count($confirmedOrders) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link modern-tab-btn" id="pills-shipping-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-shipping" type="button" role="tab" aria-controls="pills-shipping"
                            aria-selected="false">
                        <i class="bi bi-truck"></i>
                        <span style="color: black">Đang giao</span>
                        <span class="tab-badge">{{ count($shippingOrders) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link modern-tab-btn" id="pills-completed-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-completed" type="button" role="tab" aria-controls="pills-completed"
                            aria-selected="false">
                        <i class="bi bi-check2-all"></i>
                        <span style="color: black">Đã giao</span>
                        <span class="tab-badge">{{ count($completedOrders) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link modern-tab-btn" id="pills-canceled-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-canceled" type="button" role="tab" aria-controls="pills-canceled"
                            aria-selected="false">
                        <i class="bi bi-x-circle"></i>
                        <span style="color: black">Đã hủy</span>
                        <span class="tab-badge">{{ count($canceledOrders) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link modern-tab-btn" id="pills-received-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-received" type="button" role="tab" aria-controls="pills-received"
                            aria-selected="false">
                        <i class="bi bi-hand-thumbs-up"></i>
                        <span style="color: black">Đã nhận hàng</span>
                        <span class="tab-badge">{{ count($receivedOrders) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link modern-tab-btn" id="pills-wait-pay-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-wait-pay" type="button" role="tab" aria-controls="pills-wait-pay"
                            aria-selected="false">
                        <i class="bi bi-hourglass-split"></i>
                        <span style="color: black">Chờ thanh toán</span>
                        <span class="tab-badge">{{ count($wait_payment) }}</span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content modern-tab-content" id="pills-tabContent">
            <!-- Pending Orders -->
            <div class="tab-pane fade show active" id="pills-pending" role="tabpanel" aria-labelledby="pills-pending-tab">
                <div class="modern-card">
                    <div class="card-header">
                        <h3><i class="bi bi-clock-history"></i> Đơn hàng chờ xác nhận</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orders_table" data-page-length='10' class="table modern-table">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%">ID</th>
                                    <th class="text-center" style="width: 18%">Tên khách hàng</th>
                                    <th class="text-center" style="width: 12%">Số điện thoại</th>
                                    <th class="text-center" style="width: 20%">Email</th>
                                    <th class="text-center" style="width: 22%">Địa chỉ</th>
                                    <th class="text-center" style="width: 10%">Trạng thái</th>
                                    <th class="text-center" style="width: 10%">Tổng giá</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($pendingOrders as $order)
                                    <tr class="order-row">
                                        <td class="text-center">
                                            <span class="order-id">#{{$order->id}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="customer-info">
                                                <span class="customer-name">{{$order->name}}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="phone-number">{{$order->phone}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->email}}">
                                            <span class="email-text">{{$order->email}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->address}}">
                                            <span class="address-text">{{ Str::limit($order->address, 30) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge pending">{{ $order->status ?? 'Chờ xác nhận' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="price-amount">{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.order-detail', $order->id) }}" class="btn btn-modern btn-primary">
                                                <i class="bi bi-eye"></i> Chi tiết
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

            <!-- Confirmed Orders -->
            <div class="tab-pane fade" id="pills-confirmed" role="tabpanel" aria-labelledby="pills-confirmed-tab">
                <div class="modern-card">
                    <div class="card-header">
                        <h3><i class="bi bi-check-circle"></i> Đơn hàng đã xác nhận</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orders_table2" data-page-length='10' class="table modern-table">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%">ID</th>
                                    <th class="text-center" style="width: 10%">Tên khách hàng</th>
                                    <th class="text-center" style="width: 12%">Số điện thoại</th>
                                    <th class="text-center" style="width: 13%">Email</th>
                                    <th class="text-center" style="width: 22%">Địa chỉ</th>
                                    <th class="text-center" style="width: 21%">Trạng thái</th>
                                    <th class="text-center" style="width: 14%">Tổng giá</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($confirmedOrders as $order)
                                    <tr class="order-row">
                                        <td class="text-center">
                                            <span class="order-id">#{{$order->id}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="customer-info">
                                                <span class="customer-name">{{$order->name}}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="phone-number">{{$order->phone}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->email}}">
                                            <span class="email-text">{{$order->email}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->address}}">
                                            <span class="address-text">{{ Str::limit($order->address, 30) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge confirmed">{{ $order->status ?? 'Đã xác nhận' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="price-amount">{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.order-detail', $order->id) }}" class="btn btn-modern btn-primary">
                                                <i class="bi bi-eye"></i> Chi tiết
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

            <!-- Shipping Orders -->
            <div class="tab-pane fade" id="pills-shipping" role="tabpanel" aria-labelledby="pills-shipping-tab">
                <div class="modern-card">
                    <div class="card-header">
                        <h3><i class="bi bi-truck"></i> Đơn hàng đang giao</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orders_table3" data-page-length='10' class="table modern-table">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%">ID</th>
                                    <th class="text-center" style="width: 18%">Tên khách hàng</th>
                                    <th class="text-center" style="width: 12%">Số điện thoại</th>
                                    <th class="text-center" style="width: 20%">Email</th>
                                    <th class="text-center" style="width: 22%">Địa chỉ</th>
                                    <th class="text-center" style="width: 10%">Trạng thái</th>
                                    <th class="text-center" style="width: 10%">Tổng giá</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($shippingOrders as $order)
                                    <tr class="order-row">
                                        <td class="text-center">
                                            <span class="order-id">#{{$order->id}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="customer-info">
                                                <span class="customer-name">{{$order->name}}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="phone-number">{{$order->phone}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->email}}">
                                            <span class="email-text">{{$order->email}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->address}}">
                                            <span class="address-text">{{ Str::limit($order->address, 30) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge shipping">{{ $order->status ?? 'Đang giao' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="price-amount">{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.order-detail', $order->id) }}" class="btn btn-modern btn-primary">
                                                <i class="bi bi-eye"></i> Chi tiết
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

            <!-- Completed Orders -->
            <div class="tab-pane fade" id="pills-completed" role="tabpanel" aria-labelledby="pills-completed-tab">
                <div class="modern-card">
                    <div class="card-header">
                        <h3><i class="bi bi-check2-all"></i> Đơn hàng đã giao</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orders_table4" data-page-length='10' class="table modern-table">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%">ID</th>
                                    <th class="text-center" style="width: 18%">Tên khách hàng</th>
                                    <th class="text-center" style="width: 12%">Số điện thoại</th>
                                    <th class="text-center" style="width: 20%">Email</th>
                                    <th class="text-center" style="width: 22%">Địa chỉ</th>
                                    <th class="text-center" style="width: 10%">Trạng thái</th>
                                    <th class="text-center" style="width: 10%">Tổng giá</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($completedOrders as $order)
                                    <tr class="order-row">
                                        <td class="text-center">
                                            <span class="order-id">#{{$order->id}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="customer-info">
                                                <span class="customer-name">{{$order->name}}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="phone-number">{{$order->phone}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->email}}">
                                            <span class="email-text">{{$order->email}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->address}}">
                                            <span class="address-text">{{ Str::limit($order->address, 30) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge completed">{{ $order->status ?? 'Đã giao' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="price-amount">{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.order-detail', $order->id) }}" class="btn btn-modern btn-primary">
                                                <i class="bi bi-eye"></i> Chi tiết
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

            <!-- Canceled Orders -->
            <div class="tab-pane fade" id="pills-canceled" role="tabpanel" aria-labelledby="pills-canceled-tab">
                <div class="modern-card">
                    <div class="card-header">
                        <h3><i class="bi bi-x-circle"></i> Đơn hàng đã hủy</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orders_table5" data-page-length='10' class="table modern-table">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%">ID</th>
                                    <th class="text-center" style="width: 18%">Tên khách hàng</th>
                                    <th class="text-center" style="width: 12%">Số điện thoại</th>
                                    <th class="text-center" style="width: 20%">Email</th>
                                    <th class="text-center" style="width: 22%">Địa chỉ</th>
                                    <th class="text-center" style="width: 10%">Trạng thái</th>
                                    <th class="text-center" style="width: 10%">Tổng giá</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($canceledOrders as $order)
                                    <tr class="order-row">
                                        <td class="text-center">
                                            <span class="order-id">#{{$order->id}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="customer-info">
                                                <span class="customer-name">{{$order->name}}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="phone-number">{{$order->phone}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->email}}">
                                            <span class="email-text">{{$order->email}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->address}}">
                                            <span class="address-text">{{ Str::limit($order->address, 30) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge canceled">{{ $order->status ?? 'Đã hủy' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="price-amount">{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.order-detail', $order->id) }}" class="btn btn-modern btn-primary">
                                                <i class="bi bi-eye"></i> Chi tiết
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

            <!-- Received Orders -->
            <div class="tab-pane fade" id="pills-received" role="tabpanel" aria-labelledby="pills-received-tab">
                <div class="modern-card">
                    <div class="card-header">
                        <h3><i class="bi bi-hand-thumbs-up"></i> Đơn hàng đã nhận</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orders_table6" data-page-length='10' class="table modern-table">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%">ID</th>
                                    <th class="text-center" style="width: 18%">Tên khách hàng</th>
                                    <th class="text-center" style="width: 12%">Số điện thoại</th>
                                    <th class="text-center" style="width: 20%">Email</th>
                                    <th class="text-center" style="width: 22%">Địa chỉ</th>
                                    <th class="text-center" style="width: 10%">Trạng thái</th>
                                    <th class="text-center" style="width: 10%">Tổng giá</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($receivedOrders as $order)
                                    <tr class="order-row">
                                        <td class="text-center">
                                            <span class="order-id">#{{$order->id}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="customer-info">
                                                <span class="customer-name">{{$order->name}}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="phone-number">{{$order->phone}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->email}}">
                                            <span class="email-text">{{$order->email}}</span>
                                        </td>
                                        <td class="text-center" title="{{$order->address}}">
                                            <span class="address-text">{{ Str::limit($order->address, 30) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge received">{{ $order->status ?? 'Đã nhận' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="price-amount">{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.order-detail', $order->id) }}" class="btn btn-modern btn-primary">
                                                <i class="bi bi-eye"></i> Chi tiết
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
        <!-- Wait Payment Orders -->
        <div class="tab-pane fade" id="pills-wait-pay" role="tabpanel" aria-labelledby="pills-wait-pay-tab">
            <div class="modern-card">
                <div class="card-header">
                    <h3><i class="bi bi-hourglass-split"></i> Đơn hàng chờ thanh toán</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="orders_table_wait_pay" data-page-length='10' class="table modern-table">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 8%">ID</th>
                                <th class="text-center" style="width: 18%">Tên khách hàng</th>
                                <th class="text-center" style="width: 12%">Số điện thoại</th>
                                <th class="text-center" style="width: 20%">Email</th>
                                <th class="text-center" style="width: 22%">Địa chỉ</th>
                                <th class="text-center" style="width: 10%">Trạng thái</th>
                                <th class="text-center" style="width: 10%">Tổng giá</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($wait_payment as $order)
                                <tr class="order-row">
                                    <td class="text-center">
                                        <span class="order-id">#{{$order->id}}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="customer-info">
                                            <span class="customer-name">{{$order->name}}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="phone-number">{{$order->phone}}</span>
                                    </td>
                                    <td class="text-center" title="{{$order->email}}">
                                        <span class="email-text">{{$order->email}}</span>
                                    </td>
                                    <td class="text-center" title="{{$order->address}}">
                                        <span class="address-text">{{ Str::limit($order->address, 30) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="status-badge wait-payment">{{ $order->status ?? 'Chờ thanh toán' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="price-amount">{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.order-detail', $order->id) }}" class="btn btn-modern btn-primary">
                                            <i class="bi bi-eye"></i> Chi tiết
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
        <!-- Edit Order Modal (preserved from original) -->
        <div id="editOrderModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    @if(isset($order) && $order)
                        <form method="POST" action="/admin/edit/orders/{{$order->id}}">
                            @csrf
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Employee</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" required="" value="{{$order->name}}">
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                                    <input type="submit" class="btn btn-info" value="Save">
                                </div>
                            </div>
                        </form>
                    @else
                        <p>dfđff</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- DataTable Initialization (preserved functionality) -->
        <script>
            // Initialize DataTables with Vietnamese language
            const dataTableConfig = {
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tất cả"]],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
                order: [[0, 'desc']]

            };

            // Initialize all DataTables
            let table = new DataTable('#orders_table', dataTableConfig);
            let table2 = new DataTable('#orders_table2', dataTableConfig);
            let table3 = new DataTable('#orders_table3', dataTableConfig);
            let table4 = new DataTable('#orders_table4', dataTableConfig);
            let table5 = new DataTable('#orders_table5', dataTableConfig);
            let table6 = new DataTable('#orders_table6', dataTableConfig);
            let table7 = new DataTable('#orders_table_wait_pay', dataTableConfig);

        </script>

        <style>
            /* Modern Design Variables */
            :root {
                --primary-color: #374151;
                --secondary-color: #4b5563;
                --success-color: #10b981;
                --warning-color: #f59e0b;
                --danger-color: #ef4444;
                --info-color: #06b6d4;
                --gray-50: #f9fafb;
                --gray-100: #f3f4f6;
                --gray-200: #e5e7eb;
                --gray-300: #d1d5db;
                --gray-400: #9ca3af;
                --gray-500: #6b7280;
                --gray-600: #4b5563;
                --gray-700: #374151;
                --gray-800: #1f2937;
                --gray-900: #111827;
                --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
                --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
                --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
                --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
                --sidebar-width: 224px; /* Sidebar width based on the image */
            }

            /* Reset and Base */
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                background-color: var(--gray-50);
                color: var(--gray-900);
                line-height: 1.6;
            }

            /* Main container - respect sidebar layout */
            #main {
                width: calc(100% - 280px) !important;
                max-width: none !important;
                padding: 1rem !important;
                margin-left: 280px !important; /* Match sidebar width exactly */
                margin-top: 0 !important;
                margin-right: 0 !important;
                margin-bottom: 0 !important;
                position: relative !important;
                box-sizing: border-box !important;
            }

            /* Ensure all containers respect the layout */
            .stats-overview-section,
            .modern-tabs-container,
            .modern-tab-content,
            .modern-card {
                width: 100% !important;
                max-width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                box-sizing: border-box !important;
            }

            /* Table responsive container */
            .table-responsive {
                border-radius: 0 0 20px 20px;
                overflow-x: auto;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            /* Modern Table */
            .modern-table {
                margin: 0 !important;
                width: 100% !important;
                min-width: 1200px !important; /* Reduced from 1400px to fit better */
                border-collapse: collapse;
                table-layout: auto;
            }

            /* DataTables wrapper */
            .dataTables_wrapper {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            /* Remove any forced full width that might conflict */
            .container,
            .container-fluid {
                width: 100% !important;
                max-width: 100% !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            .stat-item.wait-payment {
                border-color: #f59e0b; /* Màu sắc cho "Chờ thanh toán" */
                background: linear-gradient(135deg, #fef3c7, #fde68a);
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                #main {
                    width: 100% !important;
                    margin-left: 0 !important;
                    padding: 0.5rem !important;
                }

                .modern-table {
                    min-width: 1000px !important;
                }
            }

            @media (max-width: 480px) {
                .modern-table {
                    min-width: 800px !important;
                }
            }

            /* Stats Overview Section */
            .stats-overview-section {
                margin-bottom: 2rem;
                padding: 0;
                width: 100%;
            }

            .stats-overview {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 1rem;
                background: white;
                padding: 1.5rem;
                border-radius: 16px;
                box-shadow: var(--shadow);
                width: 100%;
            }

            .stat-item {
                background: var(--gray-50);
                border-radius: 12px;
                padding: 1.5rem;
                text-align: center;
                border: 2px solid transparent;
                transition: all 0.3s ease;
            }

            .stat-item:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }

            .stat-item.pending {
                border-color: #f59e0b;
                background: linear-gradient(135deg, #fef3c7, #fde68a);
            }

            .stat-item.confirmed {
                border-color: #10b981;
                background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            }

            .stat-item.shipping {
                border-color: #06b6d4;
                background: linear-gradient(135deg, #cffafe, #a5f3fc);
            }

            .stat-item.completed {
                border-color: #8b5cf6;
                background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            }

            .stat-item.canceled {
                border-color: #ef4444;
                background: linear-gradient(135deg, #fee2e2, #fecaca);
            }

            .stat-item.received {
                border-color: #10b981;
                background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            }

            .stat-number {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
                color: var(--gray-800);
            }

            .stat-label {
                font-size: 0.875rem;
                font-weight: 500;
                color: var(--gray-700);
            }

            /* Modern Tabs */
            .modern-tabs-container {
                margin-bottom: 2rem;
                padding: 0;
                width: 100%;
            }

            .modern-nav-pills {
                background: white;
                border-radius: 16px;
                padding: 0.5rem;
                box-shadow: var(--shadow);
                display: flex;
                gap: 0.5rem;
                overflow-x: auto;
                width: 100%;
            }

            .modern-tab-btn {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem 1.5rem;
                border: 2px solid var(--gray-200);
                background: white;
                border-radius: 12px;
                cursor: pointer;
                transition: all 0.3s ease;
                white-space: nowrap;
                font-weight: 500;
                color: var(--gray-600);
                text-decoration: none;
            }

            .modern-tab-btn:hover {
                background: var(--gray-50);
                color: var(--gray-900);
                border-color: var(--gray-300);
            }

            .modern-tab-btn.active {
                background: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
                box-shadow: var(--shadow-md);
            }

            .modern-tab-btn i {
                font-size: 1.25rem;
            }

            .tab-badge {
                background: var(--gray-200);
                color: var(--gray-700);
                padding: 0.25rem 0.5rem;
                border-radius: 8px;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .modern-tab-btn.active .tab-badge {
                background: rgba(255, 255, 255, 0.3);
                color: white;
            }

            /* Modern Tab Content */
            .modern-tab-content {
                padding: 0;
                width: 100%;
            }

            /* Modern Card */
            .modern-card {
                background: white;
                border-radius: 20px;
                box-shadow: var(--shadow);
                border: 1px solid var(--gray-200);
                overflow: hidden;
                margin-bottom: 2rem;
                width: 100%;
            }

            .modern-card .card-header {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
                color: white;
                padding: 1.5rem 2rem;
                border: none;
            }

            .modern-card .card-header h3 {
                margin: 0;
                font-size: 1.25rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .modern-card .card-body {
                padding: 0;
                width: 100%;
            }

            /* Modern Table */
            .table-responsive {
                border-radius: 0 0 20px 20px;
                overflow-x: auto;
                width: 100%;
            }

            .modern-table {
                margin: 0;
                width: 100%;
                min-width: 1200px; /* Minimum width for proper column display */
                border-collapse: collapse;
                table-layout: auto; /* Let table adjust naturally */
            }

            .modern-table thead th {
                background: #f8fafc;
                color: #1e293b;
                font-weight: 600;
                padding: 1rem;
                border: none;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border-bottom: 2px solid var(--gray-200);
            }

            .modern-table tbody td {
                padding: 1rem;
                border-bottom: 1px solid var(--gray-200);
                vertical-align: middle;
                font-size: 0.875rem;
            }

            .order-row {
                transition: all 0.2s ease;
            }

            .order-row:hover {
                background-color: var(--gray-50);
                transform: translateY(-1px);
                box-shadow: var(--shadow-sm);
            }

            .order-row:last-child td {
                border-bottom: none;
            }

            /* Table Cell Styling */
            .order-id {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                color: white;
                padding: 0.375rem 0.75rem;
                border-radius: 12px;
                font-size: 0.75rem;
                font-weight: 600;
                display: inline-block;
            }

            .customer-name {
                font-weight: 600;
                color: var(--gray-900);
            }

            .phone-number {
                color: var(--gray-700);
                font-family: monospace;
            }

            .email-text {
                color: var(--gray-600);
                font-size: 0.8rem;
            }

            .address-text {
                color: var(--gray-600);
                font-size: 0.8rem;
            }

            .price-amount {
                font-weight: 700;
                color: var(--success-color);
                font-size: 0.9rem;
            }

            /* Status Badges */
            .status-badge {
                padding: 0.5rem 1rem;
                border-radius: 12px;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border: none;
                color: var(--gray-800);
            }

            .status-badge.pending {
                background-color: #fef3c7;
            }

            .status-badge.confirmed {
                background-color: #d1fae5;
            }

            .status-badge.shipping {
                background-color: #dbeafe;
            }

            .status-badge.completed {
                background-color: #e0e7ff;
            }

            .status-badge.canceled {
                background-color: #fee2e2;
            }

            .status-badge.received {
                background-color: #d1fae5;
            }

            /* Modern Button */
            .btn-modern {
                padding: 0.5rem 1rem;
                border-radius: 8px;
                font-weight: 500;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                transition: all 0.2s ease;
                border: none;
                font-size: 0.875rem;
            }

            .btn-modern.btn-primary {
                background: var(--primary-color);
                color: white;
            }

            .btn-modern.btn-primary:hover {
                background: var(--secondary-color);
                color: white;
                text-decoration: none;
                transform: translateY(-1px);
                box-shadow: var(--shadow-md);
            }

            /* DataTables Styling */
            .dataTables_wrapper {
                width: 100%;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 1rem;
            }

            .dataTables_wrapper .dataTables_length {
                float: left;
            }

            .dataTables_wrapper .dataTables_filter {
                float: right;
            }

            .dataTables_wrapper .dataTables_info {
                float: left;
                margin-top: 1rem;
            }

            .dataTables_wrapper .dataTables_paginate {
                float: right;
                margin-top: 1rem;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.5rem 0.75rem;
                margin: 0 0.125rem;
                border-radius: 8px;
                border: 1px solid var(--gray-300);
                background: white;
                color: var(--gray-700);
                text-decoration: none;
                transition: all 0.2s ease;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                background: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
                transform: translateY(-1px);
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                background: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                #main {
                    width: 100% !important;
                    margin-left: 0 !important;
                    padding: 0.5rem !important;
                }

                .modern-table {
                    min-width: 1000px;
                }

                .stats-overview {
                    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                    padding: 1rem;
                }

                .stat-item {
                    padding: 1rem;
                }

                .stat-number {
                    font-size: 1.5rem;
                }

                .modern-tab-btn {
                    padding: 0.75rem 1rem;
                }

                .modern-tab-btn span:not(.tab-badge) {
                    display: none;
                }

                .modern-card .card-header,
                .modern-table thead th,
                .modern-table tbody td {
                    padding: 0.75rem 0.5rem;
                    font-size: 0.75rem;
                }
            }

            @media (max-width: 480px) {
                .stats-overview {
                    grid-template-columns: 1fr;
                }

                .modern-tab-btn {
                    padding: 0.5rem 0.75rem;
                    font-size: 0.8rem;
                }

                .stat-number {
                    font-size: 1.2rem;
                }

                .modern-card .card-header h3 {
                    font-size: 1rem;
                }

                .modern-table {
                    font-size: 0.75rem;
                    min-width: 800px;
                }

                .btn-modern {
                    padding: 0.375rem 0.75rem;
                    font-size: 0.75rem;
                }
            }
        </style>
    </main>
@endsection
