<?php

namespace App\Providers;

use App\Models\Inventory;
use App\Models\Sale;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        Gate::define('manage-catalog', fn ($user) => $user->isAdmin());

        View::composer('layouts.app', function ($view) {
            $user = auth()->user();
            $notifications = collect();

            if ($user?->isAdmin()) {
                $lowStockItems = Inventory::with('product')
                    ->whereColumn('quantity', '<=', 'low_stock_threshold')
                    ->orderBy('quantity')
                    ->take(5)
                    ->get();

                foreach ($lowStockItems as $item) {
                    $status = $item->quantity <= 0 ? 'Stok habis' : 'Stok menipis';
                    $notifications->push([
                        'type' => $item->quantity <= 0 ? 'critical' : 'warning',
                        'title' => $status,
                        'message' => ($item->product?->name ?? 'Produk') . ' tersisa ' . $item->quantity . ' unit.',
                        'time' => $item->updated_at?->diffForHumans() ?? 'Baru saja',
                        'url' => route('inventory.index'),
                    ]);
                }
            }

            $recentSales = Sale::latest()->take(3)->get();
            foreach ($recentSales as $sale) {
                $notifications->push([
                    'type' => 'success',
                    'title' => 'Transaksi baru',
                    'message' => $sale->reference . ' senilai Rp' . number_format($sale->total, 0, ',', '.'),
                    'time' => $sale->created_at?->diffForHumans() ?? 'Baru saja',
                    'url' => route('reports.index'),
                ]);
            }

            $view->with([
                'topbarNotifications' => $notifications->take(8),
                'topbarNotificationCount' => $notifications->count(),
            ]);
        });
    }
}
