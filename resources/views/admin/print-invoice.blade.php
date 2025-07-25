<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn #{{ $invoiceNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        .invoice-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm;
            background: white;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .company-details {
            font-size: 12px;
            line-height: 1.6;
        }

        .invoice-title {
            text-align: center;
            flex: 1;
        }

        .invoice-title h1 {
            font-size: 28px;
            font-weight: bold;
            color: #d32f2f;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .invoice-number {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .invoice-date {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
        }

        .customer-info, .order-info {
            flex: 1;
            margin-right: 20px;
        }

        .customer-info:last-child, .order-info:last-child {
            margin-right: 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            min-width: 120px;
            color: #555;
        }

        .info-value {
            flex: 1;
            color: #333;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            font-size: 13px;
        }

        .products-table th {
            background: #2c5aa0;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #ddd;
        }

        .products-table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: top;
        }

        .products-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .product-name {
            text-align: left !important;
            font-weight: 500;
        }

        .product-code {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #666;
        }

        .price {
            text-align: right !important;
            font-weight: 500;
        }

        /* Serial Number Styles */
        .serial-section {
            text-align: left !important;
            padding: 8px !important;
            min-height: 60px;
        }

        .serial-box {
            border: 1px solid #ccc;
            border-radius: 3px;
            padding: 4px 6px;
            margin: 2px 0;
            min-height: 20px;
            background: white;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            display: flex;
            align-items: center;
        }

        .serial-label {
            font-size: 10px;
            color: #666;
            font-weight: bold;
            margin-right: 5px;
            min-width: 35px;
        }

        .serial-input-line {
            flex: 1;
            border-bottom: 1px solid #999;
            min-height: 16px;
            margin-left: 5px;
        }

        .total-section {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .total-table {
            width: 400px;
            border-collapse: collapse;
        }

        .total-table td {
            padding: 8px 15px;
            border: 1px solid #ddd;
        }

        .total-table .label {
            background: #f5f5f5;
            font-weight: bold;
            text-align: right;
            width: 60%;
        }

        .total-table .value {
            text-align: right;
            font-weight: 500;
        }

        .total-table .grand-total {
            background: #2c5aa0;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .footer-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 60px;
            text-transform: uppercase;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 10px;
            font-style: italic;
        }

        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #2c5aa0;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c5aa0;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-confirmed { background: #e3f2fd; color: #1976d2; }
        .status-shipping { background: #fff3e0; color: #f57c00; }
        .status-completed { background: #e8f5e8; color: #388e3c; }
        .status-cancelled { background: #ffebee; color: #d32f2f; }
        .status-pending { background: #fff8e1; color: #f9a825; }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2c5aa0;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .print-button:hover {
            background: #1e3d72;
        }

        /* Print Styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .invoice-container {
                max-width: none;
                margin: 0;
                padding: 15mm;
            }

            .no-print {
                display: none !important;
            }

            .products-table {
                page-break-inside: avoid;
            }

            .footer-section {
                page-break-inside: avoid;
            }

            .serial-box {
                border: 1px solid #000;
                background: white;
            }

            .serial-input-line {
                border-bottom: 1px solid #000;
            }
        }

        @page {
            size: A4;
            margin: 15mm;
        }
    </style>
</head>
<body>
<button class="print-button no-print" onclick="window.print()">
    🖨️ In Hóa Đơn
</button>

<div class="invoice-container">
    <!-- Header -->
    <div class="invoice-header">
        <div class="company-info">
            <div class="company-name">{{ $companyInfo['name'] }}</div>
            <div class="company-details">
                <div><strong>Địa chỉ:</strong> {{ $companyInfo['address'] }}</div>
                <div><strong>Điện thoại:</strong> {{ $companyInfo['phone'] }}</div>
                <div><strong>Email:</strong> {{ $companyInfo['email'] }}</div>
                <div><strong>Website:</strong> {{ $companyInfo['website'] }}</div>
                <div><strong>Mã số thuế:</strong> {{ $companyInfo['tax_code'] }}</div>
            </div>
        </div>

        <div class="invoice-title">
            <h1>Hóa Đơn Bán Hàng</h1>
            <div class="invoice-number">{{ $invoiceNumber }}</div>
            <div class="invoice-date">Ngày: {{ $invoiceDate }}</div>
        </div>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <div class="customer-info">
            <div class="section-title">Thông Tin Khách Hàng</div>
            <div class="info-row">
                <span class="info-label">Họ tên:</span>
                <span class="info-value">{{ $orderInfo->customer_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Điện thoại:</span>
                <span class="info-value">{{ $orderInfo->phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $orderInfo->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Địa chỉ:</span>
                <span class="info-value">{{ $orderInfo->address }}</span>
            </div>
        </div>

        <div class="order-info">
            <div class="section-title">Thông Tin Đơn Hàng</div>
            <div class="info-row">
                <span class="info-label">Mã đơn hàng:</span>
                <span class="info-value">#{{ $orderInfo->order_id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày đặt:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($orderInfo->order_date)->format('d/m/Y H:i') }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Thanh toán:</span>
                <span class="info-value">{{ $orderInfo->payment }}</span>
            </div>
            @if($orderInfo->landing_code)
                <div class="info-row">
                    <span class="info-label">Mã vận đơn:</span>
                    <span class="info-value">{{ $orderInfo->landing_code }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Products Table with Serial Number Boxes -->
    <table class="products-table">
        <thead>
        <tr>
            <th style="width: 5%">STT</th>
            <th style="width: 12%">Mã SP</th>
            <th style="width: 25%">Tên Sản Phẩm</th>
            <th style="width: 8%">SL</th>
            <th style="width: 12%">Đơn Giá</th>
            <th style="width: 25%">Số Seri</th>
            <th style="width: 13%">Thành Tiền</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div class="product-code">{{ $item->product_code }}</div>
                </td>
                <td class="product-name">{{ $item->product_name }}</td>
                <td>{{ number_format($item->quantity) }}</td>
                <td class="price">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                <td class="serial-section">
                    @for($i = 0; $i < $item->quantity; $i++)
                        <div class="serial-box">
                            <span class="serial-label">{{ $i + 1 }}:</span>
                            <div class="serial-input-line"></div>
                        </div>
                    @endfor
                </td>
                <td class="price">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} ₫</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Total Section -->
    <div class="total-section">
        <table class="total-table">
            <tr>
                <td class="label">Tổng số lượng:</td>
                <td class="value">{{ number_format($totalQuantity) }} sản phẩm</td>
            </tr>
            <tr>
                <td class="label">Tạm tính:</td>
                <td class="value">{{ number_format($totalAmount, 0, ',', '.') }} ₫</td>
            </tr>
            <tr>
                <td class="label">Phí vận chuyển:</td>
                <td class="value">0 ₫</td>
            </tr>
            <tr>
                <td class="label">Giảm giá:</td>
                <td class="value">0 ₫</td>
            </tr>
            <tr class="grand-total">
                <td class="label">TỔNG CỘNG:</td>
                <td class="value">{{ number_format($totalAmount, 0, ',', '.') }} ₫</td>
            </tr>
        </table>
    </div>

    @if($orderInfo->note)
        <!-- Notes Section -->
        <div class="notes-section">
            <div class="notes-title">Ghi Chú:</div>
            <div>{{ $orderInfo->note }}</div>
        </div>
    @endif

    <!-- Footer with Signatures -->
    <div class="footer-section">
        <div class="signature-box">
            <div class="signature-title">Khách Hàng</div>
            <div class="signature-line">(Ký và ghi rõ họ tên)</div>
        </div>

        <div class="signature-box">
            <div class="signature-title">Người Bán Hàng</div>
            <div class="signature-line">(Ký và ghi rõ họ tên)</div>
        </div>

        <div class="signature-box">
            <div class="signature-title">Thủ Kho</div>
            <div class="signature-line">(Ký và ghi rõ họ tên)</div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 40px; font-size: 12px; color: #666;">
        <p>Cảm ơn quý khách đã mua hàng tại {{ $companyInfo['name'] }}!</p>
        <p>Hóa đơn được in tự động vào {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</div>
</body>
</html>
