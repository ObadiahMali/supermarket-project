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

    .dashboard-header {
        background: rgba(40, 167, 69, 0.9);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .dashboard-header h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .dashboard-header i {
        margin-right: 10px;
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

    .btn-dashboard {
        background-color: white;
        color: #28a745;
        border-radius: 6px;
        padding: 6px 12px;
        font-weight: bold;
        transition: background 0.3s;
        text-decoration: none;
    }

    .btn-dashboard:hover {
        background-color: #28a745;
        color: white;
        text-decoration: none;
    }
</style>

@php
    $user = auth()->user();
    $dashboardRoute = match(true) {
        $user->hasRole('manager') => route('manager.dashboard'),
        $user->hasRole('cashier') => route('cashier.dashboard'),
        $user->hasRole('entry_clerk') => route('entryClerk.dashboard'),
        default => route('dashboard'),
    };
@endphp

<div class="container dashboard-wrapper">
    <div class="dashboard-header">
        <h3><i class="fas fa-cash-register"></i> Cashier Dashboard</h3>
        {{-- <a href="{{ $dashboardRoute }}" class="btn-dashboard">
            <i class="fas fa-home"></i> Back to Dashboard
        </a> --}}
    </div>

    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('pos.index') }}" class="action-card mb-3">
                <i class="fas fa-shopping-cart"></i>
                <span>Start Sale</span>
            </a>
        </div>
    </div>
</div>
@endsection