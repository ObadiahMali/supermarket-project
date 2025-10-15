@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-wrapper {
        color: white;
        padding: 40px 20px;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.08);
        border: none;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.15);
        color: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 25px rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.12);
    }

    .glass-card h5,
    .glass-card p,
    .glass-card li {
        color: #ffffff;
    }

    .glass-card .list-group-item {
        background-color: rgba(255, 255, 255, 0.05);
        border: none;
        color: white;
    }

    .glass-card .list-group-item .text-muted {
        color: #d1d1d1 !important;
    }

    .btn-glass {
        background: #28a745;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 6px;
        padding: 10px 20px;
        transition: background 0.3s;
    }

    .btn-glass:hover {
        background: #218838;
    }

    .text-highlight {
        color: #f9d423;
    }
</style>

<div class="container dashboard-wrapper">
    <h1 class="mb-4 text-highlight">Manager Dashboard</h1>

    {{-- 🔔 Low Stock Alert --}}
    @include('partials.low-stock-alert', ['lowStockProducts' => $lowStockProducts])

    {{-- 📊 Summary Cards --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card glass-card mb-4">
                <div class="card-body">
                    <h5>Total Sales</h5>
                    <p class="fs-4">{{ number_format($totalSales) }} UGX</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card glass-card mb-4">
                <div class="card-body">
                    <h5>Total Products</h5>
                    <p class="fs-4">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card glass-card mb-4">
                <div class="card-body">
                    <h5>Stock Overview</h5>
                    <ul class="mb-0">
                        <li>Available Quantity: {{ $totalStockQuantity }}</li>
                        <li>Available Value: {{ number_format($totalStockValue) }} UGX</li>
                        <li>Sold Quantity: {{ $totalStockSold }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- 📅 Daily Activity --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card glass-card">
                <div class="card-body">
                    <h5>Today's Transactions</h5>
                    @if($todaysTransactions->count())
                        <ul class="list-group">
                            @foreach($todaysTransactions as $sale)
                                <li class="list-group-item">
                                    Sale #{{ $sale->id }} — {{ number_format($sale->total) }} UGX
                                    <span class="text-muted float-end">{{ $sale->created_at->format('H:i') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No transactions yet today.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card glass-card">
                <div class="card-body">
                    <h5>Products Sold Today</h5>
                    <p class="fs-4">
                        <i class="fas fa-boxes me-2"></i> {{ $productsSoldToday }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔗 View Products Button --}}
    <div class="text-center mt-5">
        <a href="{{ route('products.index') }}" class="btn btn-glass">
            <i class="fas fa-box-open me-2"></i> View Products
        </a>
    </div>
</div>
@endsection