@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h1 class="text-danger">403 - Unauthorized Role</h1>
    <p class="mb-4">Your account doesn't have access to this dashboard.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-danger">Logout</button>
    </form>
</div>
@endsection