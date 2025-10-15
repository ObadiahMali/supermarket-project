<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Category;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        // 🔍 Search filter
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        // 📦 Paginated product list
        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        // 📊 Summary metrics
        $totalSales = Sale::sum('total');
        $totalProducts = Product::count();
        $totalStockQuantity = Product::sum('stock');
        $totalStockValue = Product::sum(DB::raw('stock * price'));
        $totalStockSold = SaleItem::sum('quantity');

        // 📅 Daily activity
        $productsSoldToday = SaleItem::whereDate('created_at', today())->sum('quantity');
        $todaysTransactions = Sale::whereDate('created_at', today())->get();

        // 🔔 Low stock alert
        $lowStockProducts = Product::where('stock', '<', 10)->get();

        // 📁 Category breakdown
        $categories = Category::withCount('products')->get();

        return view('manager.dashboard', compact(
            'products',
            'totalSales',
            'totalProducts',
            'totalStockQuantity',
            'totalStockValue',
            'totalStockSold',
            'productsSoldToday',
            'todaysTransactions',
            'lowStockProducts',
            'categories'
        ));
    }
}