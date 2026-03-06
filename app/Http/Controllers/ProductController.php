<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'inventory']);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('sku', 'like', "%$s%"));
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        $products   = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'cost'        => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|url',
            'stock'       => 'required|integer|min:0',
            'threshold'   => 'required|integer|min:0',
        ]);

        $product = Product::create([
            'name'        => $data['name'],
            'sku'         => 'SKU-' . strtoupper(Str::random(6)),
            'category_id' => $data['category_id'],
            'price'       => $data['price'],
            'cost'        => $data['cost'],
            'description' => $data['description'] ?? null,
            'image_url'   => $data['image_url'] ?? null,
        ]);

        Inventory::create([
            'product_id'          => $product->id,
            'quantity'            => $data['stock'],
            'low_stock_threshold' => $data['threshold'],
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.create', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'cost'        => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|url',
            'threshold'   => 'required|integer|min:0',
        ]);

        $product->update([
            'name'        => $data['name'],
            'category_id' => $data['category_id'],
            'price'       => $data['price'],
            'cost'        => $data['cost'],
            'description' => $data['description'] ?? null,
            'image_url'   => $data['image_url'] ?? null,
        ]);

        $product->inventory?->update(['low_stock_threshold' => $data['threshold']]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }
}
