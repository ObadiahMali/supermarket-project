@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h4><i class="fas fa-plus-circle"></i> Add New Product</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Fresh Milk 500ml" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price (UGX)</label>
                <input type="number" name="price" class="form-control" placeholder="e.g. 3500" required>
            </div>

            <div class="mb-3">
    <label for="category" class="form-label">Category</label>
    <select name="category" class="form-select" required>
        <option value="" disabled selected>Select a category</option>
        <option value="electronics">Electronics</option>
        <option value="home appliances">Home Appliances</option>
        <option value="groceries">Groceries</option>
        <option value="clothing">Clothing</option>
        <option value="furniture">Furniture</option>
        <option value="books">Books</option>
    </select>
</div>
<div class="mb-3">
    <label for="stock" class="form-label">Stock Quantity</label>
    <input type="number" name="stock" class="form-control" placeholder="e.g. 10" required>
</div>
            <div class="mb-3">
                <label for="barcode" class="form-label">Barcode / QR Code</label>
                <input type="text" name="barcode" class="form-control" placeholder="Auto-generated or scanned">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-admin">
                <i class="fas fa-save"></i> Save Product
            </button>
            
        </form>
    </div>
</div>
@endsection