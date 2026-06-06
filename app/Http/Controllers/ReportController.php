<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
            'payment_method' => 'nullable|in:cash,card,ewallet',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $query = Sale::query();

        if ($request->filled('search')) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('items.product', fn ($productQuery) => $productQuery->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $summaryQuery = clone $query;
        $saleIdsQuery = (clone $query)->select('id');

        $summary = [
            'revenue' => (clone $summaryQuery)->sum('total'),
            'transactions' => (clone $summaryQuery)->count(),
            'items_sold' => SaleItem::whereIn('sale_id', $saleIdsQuery)->sum('quantity'),
            'average_order' => (clone $summaryQuery)->avg('total') ?? 0,
        ];

        $sales = $query->with('items.product')->latest()->paginate(10)->withQueryString();

        return view('reports.index', compact('sales', 'summary'));
    }
}
