@extends('layouts.app')

@section('title', 'Restock Product')

@section('content')
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h4><i class="fas fa-truck-loading"></i> Restock Product</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('products.restock', $product->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" class="form-control" value="{{ $product->name }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Stock</label>
                <input type="number" class="form-control" value="{{ $product->stock }}" disabled>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Add Quantity</label>
                <input type="number" name="quantity" class="form-control" placeholder="e.g. 20" required min="1">
            </div>

            <button type="submit" class="btn btn-manager">
                <i class="fas fa-plus-circle"></i> Update Stock
            </button>
        </form>
    </div>
</div>
@endsection