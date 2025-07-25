<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doanh Thu Tháng {{ $currentMonth }} Năm {{ $currentYear }}</title>
</head>
<body>
<h1>Thống Kê Doanh Thu Tháng {{ $currentMonth }} Năm {{ $currentYear }}</h1>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if(isset($message))
    <p>{{ $message }}</p>
@else
    <table border="1">
        <thead>
        <tr>
            <th>Ngày</th>
            <th>Doanh thu (VNĐ)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($dailyRevenues as $revenue)
            <tr>
                <td>{{ \Carbon\Carbon::parse($revenue->date)->format('d/m/Y') }}</td>
                <td>{{ number_format($revenue->total_amount, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <form action="{{ route('revenue.close') }}" method="POST">
        @csrf
        <button type="submit">Chốt Doanh Thu</button>
    </form>
@endif
</body>
</html>
