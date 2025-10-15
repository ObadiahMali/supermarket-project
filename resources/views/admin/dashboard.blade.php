@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4"><i class="fas fa-tools"></i> Admin Control Panel</h3>
    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('users.index') }}" class="btn btn-outline-success w-100 mb-3">
                <i class="fas fa-users"></i> Manage Users
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('products.index') }}" class="btn btn-outline-success w-100 mb-3">
                <i class="fas fa-boxes"></i> Manage Products
            </a>
        </div>
    </div>
</div>
@endsection