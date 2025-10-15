<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\SaleItem;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProductController extends Controller
{
    // 📦 Display product list with summary and search
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        $totalStockQuantity = Product::sum('stock');
        $totalStockValue = Product::sum(DB::raw('stock * price'));
        $totalStockSold = SaleItem::sum('quantity');

        return view('products.index', compact(
            'products',
            'totalStockQuantity',
            'totalStockValue',
            'totalStockSold'
        ));
    }

    // ➕ Show product creation form
    public function create()
    {
        return view('products.create');
    }

    // ✅ Handle product creation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string',
            'barcode' => 'nullable|string|unique:products,barcode',
            'image' => 'nullable|image|max:2048',
        ]);

        $barcode = $validated['barcode'] ?? uniqid('PROD-');
        $qrPath = 'qr/' . $barcode . '.svg';
        Storage::disk('public')->put($qrPath, QrCode::format('svg')->size(200)->generate($barcode));
        $imagePath = $request->file('image')?->store('products', 'public');

        Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => (int) $validated['stock'],
            'category' => $validated['category'],
            'barcode' => $barcode,
            'qr_code_path' => $qrPath,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    // ✏️ Show edit form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // 🔄 Handle product update
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'image' => 'nullable|image|max:2048',
        ]);

        $barcode = $validated['barcode'] ?? $product->barcode;

        // Regenerate QR if barcode changed
        if ($barcode !== $product->barcode) {
            $qrPath = 'qr/' . $barcode . '.svg';
            Storage::disk('public')->put($qrPath, QrCode::format('svg')->size(200)->generate($barcode));
            $product->qr_code_path = $qrPath;
            $product->barcode = $barcode;
        }

        // Replace image if new one uploaded
        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $product->image_path = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => (int) $validated['stock'],
            'category' => $validated['category'],
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // ❌ Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        if ($product->qr_code_path) {
            Storage::disk('public')->delete($product->qr_code_path);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    // 🔁 Restock product
    public function restock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);
        $product->stock += $request->quantity;
        $product->save();

        return redirect()->back()->with('success', 'Product restocked successfully.');
    }
}