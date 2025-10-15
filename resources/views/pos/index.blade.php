@extends('layouts.app')

@section('title', "Mali's Supermarket — POS")

@section('content')
<div class="card mt-4">
    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-cash-register"></i> Point of Sale</h4>
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-dark">
            <i class="fas fa-home"></i> Back to Dashboard
        </a>
    </div>

    <div class="card-body">
        {{-- Add to Cart Form --}}
        <form method="POST" action="{{ route('sales.store') }}">
            @csrf

            <div class="mb-3">
                <label for="barcode" class="form-label">Scan Barcode or Select Product</label>
                <input type="text" name="barcode" id="barcode" class="form-control mb-2" placeholder="Scan barcode..." autofocus>

                <select name="product_id" class="form-select">
                    <option value="">— Or select product manually —</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} (UGX {{ number_format($product->price) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="1" min="1">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add to Cart
            </button>
        </form>

        <hr>

        {{-- View Products Toggle --}}
        <button class="btn btn-outline-info mb-3" type="button" onclick="toggleProducts()">
            <i class="fas fa-boxes"></i> View Products
        </button>

        <div id="product-list" style="display: none;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>UGX {{ number_format($product->price) }}</td>
                        <td>{{ $product->stock }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Cart Table --}}
        <h5 class="mt-4">🛒 Current Cart</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['price']) }}</td>
                    <td>{{ number_format($item['price'] * $item['quantity']) }}</td>
                    <td>
                        <form method="POST" action="{{ route('cart.remove', $item['id']) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Checkout Form --}}
        <div class="text-end mt-4">
            <form method="POST" action="{{ route('sales.checkout') }}">
                @csrf

                <div class="mb-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="card">Card</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="amount_received" class="form-label">Amount Received</label>
                    <input type="number" name="amount_received" id="amount_received" class="form-control" min="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Change Due</label>
                    <input type="text" id="change_due" class="form-control" readonly>
                </div>

                <h5>Total: <strong>UGX {{ number_format($cartTotal) }}</strong></h5>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-receipt"></i> Complete Sale & Print Receipt
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
    function toggleProducts() {
        const list = document.getElementById('product-list');
        list.style.display = list.style.display === 'none' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const amountInput = document.getElementById('amount_received');
        const changeOutput = document.getElementById('change_due');
        const total = {{ $cartTotal }};

        amountInput.addEventListener('input', function () {
            const received = parseFloat(amountInput.value);
            if (!isNaN(received)) {
                const change = received - total;
                changeOutput.value = change >= 0
                    ? `UGX ${change.toLocaleString()}`
                    : 'Insufficient';
            } else {
                changeOutput.value = '';
            }
        });
    });
</script>
@endsection