<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary stats
        $todaySales     = Sale::whereDate('created_at', today())->where('status', 'completed')->count();
        $todayRevenue   = Sale::whereDate('created_at', today())->where('status', 'completed')->sum('total');
        $totalProducts  = Product::where('is_active', true)->count();
        $lowStockCount  = Inventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count();

        // Sales chart data — last 30 days
        $chartData = Sale::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels  = [];
        $chartRevenue = [];
        $chartOrders  = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $row  = $chartData->firstWhere('date', $date);
            $chartLabels[]  = now()->subDays($i)->format('M d');
            $chartRevenue[] = $row ? (float) $row->revenue : 0;
            $chartOrders[]  = $row ? (int)   $row->orders  : 0;
        }

        // Low stock alerts
        $lowStockItems = Inventory::with('product.category')
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->orderBy('quantity')
            ->take(8)
            ->get();

        // Recent transactions
        $recentSales = Sale::with('items.product')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'todaySales', 'todayRevenue', 'totalProducts', 'lowStockCount',
            'chartLabels', 'chartRevenue', 'chartOrders',
            'lowStockItems', 'recentSales'
        ));
    }
}
