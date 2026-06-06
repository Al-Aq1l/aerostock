<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier', 'inventory']);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('sku', 'like', "%$s%"));
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }
        $products   = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();
        $suppliers = Supplier::orderBy('name')->get();
        return view('products.index', compact('products', 'categories', 'suppliers'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'price'       => 'required|numeric|min:0',
            'cost'        => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'stock'       => 'required|integer|min:0',
            'threshold'   => 'required|integer|min:0',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imageUrl = '/storage/' . $path;
        }

        $product = Product::create([
            'name'        => $data['name'],
            'sku'         => 'SKU-' . strtoupper(Str::random(6)),
            'category_id' => $data['category_id'],
            'supplier_id' => $data['supplier_id'] ?? null,
            'price'       => $data['price'],
            'cost'        => $data['cost'],
            'description' => $data['description'] ?? null,
            'image_url'   => $imageUrl,
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
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        return view('products.create', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'price'       => 'required|numeric|min:0',
            'cost'        => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'threshold'   => 'required|integer|min:0',
        ]);

        $imageUrl = $product->image_url;
        if ($request->hasFile('image')) {
            $this->deleteProductImage($product->image_url);
            $path = $request->file('image')->store('products', 'public');
            $imageUrl = '/storage/' . $path;
        }

        $product->update([
            'name'        => $data['name'],
            'category_id' => $data['category_id'],
            'supplier_id' => $data['supplier_id'] ?? null,
            'price'       => $data['price'],
            'cost'        => $data['cost'],
            'description' => $data['description'] ?? null,
            'image_url'   => $imageUrl,
        ]);

        $product->inventory?->update(['low_stock_threshold' => $data['threshold']]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->deleteProductImage($product->image_url);
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    private function deleteProductImage(?string $imageUrl): void
    {
        if (! $imageUrl || ! str_contains($imageUrl, '/storage/')) {
            return;
        }

        $path = Str::after($imageUrl, '/storage/');
        Storage::disk('public')->delete($path);
    }
}
