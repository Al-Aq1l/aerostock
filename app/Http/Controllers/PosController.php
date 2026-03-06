<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $products = Product::with(['category', 'inventory'])
            ->where('is_active', true)
            ->whereHas('inventory', fn($q) => $q->where('quantity', '>', 0))
            ->get();

        return view('pos.index', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,ewallet',
        ]);

        $subtotal = 0;
        $lineItems = [];

        foreach ($request->items as $item) {
            $product  = Product::findOrFail($item['id']);
            $qty      = (int) $item['qty'];
            $lineSub  = $product->price * $qty;
            $subtotal += $lineSub;
            $lineItems[] = [
                'product'    => $product,
                'qty'        => $qty,
                'unit_price' => $product->price,
                'subtotal'   => $lineSub,
            ];
        }

        $tax   = round($subtotal * 0.10, 2);
        $total = $subtotal + $tax;

        $sale = Sale::create([
            'reference'      => 'INV-' . strtoupper(Str::random(8)),
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'total'          => $total,
            'payment_method' => $request->payment_method,
            'status'         => 'completed',
        ]);

        foreach ($lineItems as $line) {
            SaleItem::create([
                'sale_id'    => $sale->id,
                'product_id' => $line['product']->id,
                'quantity'   => $line['qty'],
                'unit_price' => $line['unit_price'],
                'subtotal'   => $line['subtotal'],
            ]);
            // Deduct from inventory
            $inv = $line['product']->inventory;
            if ($inv) {
                $inv->decrement('quantity', $line['qty']);
            }
        }

        return response()->json([
            'success'   => true,
            'reference' => $sale->reference,
            'total'     => $sale->total,
        ]);
    }
}
