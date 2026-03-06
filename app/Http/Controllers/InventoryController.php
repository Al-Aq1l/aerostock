<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with('product.category')
            ->join('products', 'products.id', '=', 'inventory.product_id')
            ->select('inventory.*');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($qb) use ($q) {
                $qb->where('products.name', 'like', "%$q%")
                   ->orWhere('products.sku', 'like', "%$q%");
            });
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'low'  => $query->whereColumn('inventory.quantity', '<=', 'inventory.low_stock_threshold')->where('inventory.quantity', '>', 0),
                'out'  => $query->where('inventory.quantity', '<=', 0),
                'ok'   => $query->whereColumn('inventory.quantity', '>', 'inventory.low_stock_threshold'),
                default => null,
            };
        }

        $items = $query->orderBy('inventory.quantity')->paginate(20)->withQueryString();

        return view('inventory.index', compact('items'));
    }

    public function adjust(Request $request, Inventory $inventory)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);
        $inventory->update([
            'quantity'           => $request->quantity,
            'last_restocked_at'  => now(),
        ]);
        return back()->with('success', 'Stock updated for ' . $inventory->product->name);
    }
}
