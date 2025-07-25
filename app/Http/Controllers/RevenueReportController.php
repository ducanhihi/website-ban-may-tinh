<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevenueReportController extends Controller
{
    public function index()
    {
        return view('/admin/revenue-statistics');
    }

    public function getRevenueData(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Validate dates
        $request->validate([
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date'
        ]);

        // Get revenue data from orders table (exclude cancelled orders)
        $revenueData = DB::table('orders')
            ->select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
//            ->where('status', '!=', 'Đã hủy')
            ->whereNotIn('orders.status', ['Đã hủy', 'Chờ thanh toán', 'Chờ xác nhận']) // Đã thêm điều kiện này

            ->whereBetween('order_date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(order_date)'))
            ->orderBy('date')
            ->get();

        // Calculate summary statistics
        $totalRevenue = $revenueData->sum('revenue');
        $totalOrders = $revenueData->sum('orders');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $averageDailyRevenue = $revenueData->count() > 0 ? $totalRevenue / $revenueData->count() : 0;

        // Get recent orders
        $recentOrders = DB::table('orders')
            ->select(
                'orders.id',
                'orders.name as customer_name',
                'orders.total',
                'orders.status',
                'orders.order_date',
                DB::raw('(SELECT COUNT(*) FROM orderdetails WHERE orderdetails.order_id = orders.id) as products_count')
            )
            ->where('orders.status', '!=', 'Đã hủy')
            ->orderBy('orders.order_date', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'revenue_data' => $revenueData,
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'average_order_value' => $averageOrderValue,
                'average_daily_revenue' => $averageDailyRevenue
            ],
            'recent_orders' => $recentOrders,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }

    public function getTopProducts(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));

        // Validate inputs
        $request->validate([
            'month' => 'integer|between:1,12',
            'year' => 'integer|min:2020'
        ]);

        // Calculate date range for the month
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Get top 5 products with images for the specified month
        $topProducts = DB::table('orderdetails')
            ->join('orders', 'orderdetails.order_id', '=', 'orders.id')
            ->join('products', 'orderdetails.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                DB::raw('SUM(orderdetails.quantity) as total_sold'),
                DB::raw('SUM(orderdetails.price * orderdetails.quantity) as total_revenue')
            )
            ->where('orders.status', '!=', 'Đã hủy')
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'top_products' => $topProducts,
            'period' => [
                'month' => $month,
                'year' => $year,
                'label' => "Tháng {$month}/{$year}"
            ]
        ]);
    }

    public function compareRevenue(Request $request)
    {
        $request->validate([
            'period1_month' => 'required|integer|between:1,12',
            'period1_year' => 'required|integer|min:2020',
            'period2_month' => 'required|integer|between:1,12',
            'period2_year' => 'required|integer|min:2020',
        ]);

        $period1Month = $request->input('period1_month');
        $period1Year = $request->input('period1_year');
        $period2Month = $request->input('period2_month');
        $period2Year = $request->input('period2_year');

        // Calculate date ranges for both periods
        $period1Start = Carbon::create($period1Year, $period1Month, 1)->startOfMonth();
        $period1End = Carbon::create($period1Year, $period1Month, 1)->endOfMonth();

        $period2Start = Carbon::create($period2Year, $period2Month, 1)->startOfMonth();
        $period2End = Carbon::create($period2Year, $period2Month, 1)->endOfMonth();

        // Get data for period 1
        $period1Data = DB::table('orders')
            ->select(
                DB::raw('COALESCE(SUM(total), 0) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('status', '!=', 'Đã hủy')
            ->whereBetween('order_date', [$period1Start, $period1End])
            ->first();

        // Get data for period 2
        $period2Data = DB::table('orders')
            ->select(
                DB::raw('COALESCE(SUM(total), 0) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('status', '!=', 'Đã hủy')
            ->whereBetween('order_date', [$period2Start, $period2End])
            ->first();

        // Calculate percentage change between two periods
        $period1Revenue = $period1Data->revenue ?? 0;
        $period2Revenue = $period2Data->revenue ?? 0;

        $percentageChange = 0;
        if ($period1Revenue > 0) {
            $percentageChange = (($period2Revenue - $period1Revenue) / $period1Revenue) * 100;
        } elseif ($period2Revenue > 0) {
            $percentageChange = 100; // 100% increase if period1 is 0 and period2 > 0
        }

        // Calculate differences
        $revenueDifference = $period2Revenue - $period1Revenue;
        $ordersDifference = ($period2Data->orders ?? 0) - ($period1Data->orders ?? 0);

        return response()->json([
            'period1' => [
                'label' => "Tháng {$period1Month}/{$period1Year}",
                'revenue' => $period1Revenue,
                'orders' => $period1Data->orders ?? 0
            ],
            'period2' => [
                'label' => "Tháng {$period2Month}/{$period2Year}",
                'revenue' => $period2Revenue,
                'orders' => $period2Data->orders ?? 0
            ],
            'comparison' => [
                'revenue_difference' => $revenueDifference,
                'orders_difference' => $ordersDifference,
                'percentage_difference' => round($percentageChange, 1),
                'winner' => $period2Revenue > $period1Revenue ? 'period2' : 'period1'
            ]
        ]);
    }
}
