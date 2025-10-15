@extends('layouts.app')
@section('title', 'Cashier Dashboard')

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

    .action-card {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.15);
        padding: 20px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        color: white;
        text-decoration: none;
        display: block;
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 25px rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.12);
        text-decoration: none;
    }

    .action-card i {
        font-size: 1.5rem;
        margin-bottom: 10px;
        display: block;
    }

    .action-card span {
        font-size: 1.1rem;
        font-weight: 500;
    }

    .text-highlight {
        color: #f9d423;
    }
</style>

<div class="container dashboard-wrapper">
    <h3 class="mb-4 text-highlight"><i class="fas fa-cash-register"></i> Point of Sale</h3>
    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('pos.index') }}" class="action-card mb-3">
                <i class="fas fa-shopping-cart"></i>
                <span>Start Sale</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('products.index') }}" class="action-card mb-3">
                <i class="fas fa-search"></i>
                <span>Lookup Products</span>
            </a>
        </div>
    </div>
</div>
@endsection
