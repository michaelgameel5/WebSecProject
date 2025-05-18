@extends('layouts.master')
@section('title', 'Edit Credit')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Set Credit for {{ $user->name }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('credits.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="credit" class="form-label">Credit Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('credit') is-invalid @enderror" 
                                    id="credit" name="credit" value="{{ old('credit', $user->credit) }}" 
                                    step="0.01" min="0" max="10000" required>
                            </div>
                            @error('credit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('credits.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Credit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 