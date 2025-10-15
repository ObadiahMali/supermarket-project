@extends('layouts.app')

@section('title', 'Entry Clerk Dashboard')

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
        background-color: rgba(0, 123, 255, 0.9);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-add {
        background-color: #28a745;
        color: white;
        font-weight: bold;
        border-radius: 6px;
        padding: 10px 20px;
        transition: background 0.3s;
    }

    .btn-add:hover {
        background-color: #218838;
    }

    .table {
        color: white;
    }

    .table thead th {
        background-color: rgba(255, 255, 255, 0.2);
        color: #000;
    }

    .badge {
        font-size: 0.9em;
        padding: 6px 10px;
    }
</style>

<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-box-open"></i> Product Entry</h4>
            <a href="{{ route('products.create') }}" class="btn btn-add">
                <i class="fas fa-plus-circle"></i> Add Product
            </a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>QR Code</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr>
                        <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection