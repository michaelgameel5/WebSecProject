@extends('layouts.master')
@section('title', 'Add Credit')
@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Add Credit to {{ $user->name }}</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.add-credit', $user->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" required>
                </div>
                <button type="submit" class="btn btn-success">Add Credit</button>
            </form>
        </div>
    </div>
</div>
@endsection 