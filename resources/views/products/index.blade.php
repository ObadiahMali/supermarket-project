@extends('layouts.app')

@section('title', 'Product List')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        font-family: 'Poppins', sans-serif;
    }

    .card {
        background: rgba(255, 255, 255, 0.08);
        border: none;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.15);
        color: white;
    }

    .card-header {
        background-color: rgba(40, 167, 69, 0.9);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .card-body {
        color: white;
    }

    .table {
        color: white;
    }

    .table thead th {
        background-color: rgba(255, 255, 255, 0.2);
        color: #000;
    }

    .btn-admin {
        background-color: #28a745;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        transition: background 0.3s;
    }

    .btn-admin:hover {
        background-color: #218838;
    }

    .btn-manager {
        background-color: #007bff;
        color: white;
        border-radius: 4px;
        padding: 4px 8px;
    }

    .btn-manager:hover {
        background-color: #0056b3;
    }

    .btn-danger {
        border-radius: 4px;
        padding: 4px 8px;
    }

    .badge {
        font-size: 0.9em;
        padding: 6px 10px;
    }

    .form-control {
        border-radius: 6px;
    }

    .input-group .btn {
        border-radius: 6px;
    }

    .alert {
        background-color: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
    }
</style>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-boxes"></i> All Products</h4>
        <form method="GET" action="{{ route('manager.dashboard') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by name or category" value="{{ request('search') }}">
            <button class="btn btn-light" type="submit">Search</button>
        </form>
    </div>

    <div class="card-body">
        {{-- 🔢 Summary --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="alert alert-info">
                    <strong>Total Stock Quantity:</strong> {{ $totalStockQuantity }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-warning">
                    <strong>Total Stock Value:</strong> {{ number_format($totalStockValue) }} UGX
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success">
                    <strong>Total Stock Sold:</strong> {{ $totalStockSold }}
                </div>
            </div>
        </div>

        {{-- ➕ Add Product --}}
        @if(auth()->user()->hasRole('entry_clerk') || auth()->user()->hasRole('manager'))
            <a href="{{ route('products.create') }}" class="btn btn-admin mb-3">
                <i class="fas fa-plus-circle"></i> Add Product
            </a>
        @endif

        {{-- 📦 Product Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price (UGX)</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>QR Code</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr>
                        <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ number_format($product->price) }}</td>
                        <td>{{ ucfirst($product->category) }}</td>
                        <td>
                            @if($product->stock <= 5)
                                <span class="badge bg-danger">Low ({{ $product->stock }})</span>
                            @else
                                <span class="badge bg-success">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->qr_code_path)
                                <img src="{{ asset('storage/' . $product->qr_code_path) }}" alt="QR" width="60">
                            @endif
                        </td>
                        <td>
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="Image" width="60">
                            @endif
                        </td>
                        <td>
                            @if(auth()->user()->hasRole('manager'))
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-manager">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 📄 Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection