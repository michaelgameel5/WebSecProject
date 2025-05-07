@extends('layouts.master')

@section('title', 'Create Voucher')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h1><i class="fas fa-ticket-alt me-2"></i>Create New Voucher</h1>

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <div class="card mt-4">
                <div class="card-body">
                    <form action="{{ route('vouchers.store') }}" method="POST" id="createVoucherForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Voucher Code</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code') }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="discount_percentage" class="form-label">Discount Percentage</label>
                            <input type="number" class="form-control @error('discount_percentage') is-invalid @enderror" 
                                   id="discount_percentage" name="discount_percentage" 
                                   value="{{ old('discount_percentage') }}" min="1" max="100" required>
                            @error('discount_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" value="{{ old('expires_at') }}" required>
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Customers</label>
                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                <div class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label" for="selectAll">
                                            Select All Customers
                                        </label>
                                    </div>
                                </div>
                                <hr>
                                @foreach($customers as $customer)
                                    <div class="form-check">
                                        <input class="form-check-input customer-checkbox" 
                                               type="checkbox" 
                                               name="customer_ids[]" 
                                               value="{{ $customer->id }}"
                                               id="customer{{ $customer->id }}"
                                               {{ in_array($customer->id, old('customer_ids', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="customer{{ $customer->id }}">
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('customer_ids')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Vouchers
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Create Voucher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const customerCheckboxes = document.querySelectorAll('.customer-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        customerCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Form validation
    const form = document.getElementById('createVoucherForm');
    form.addEventListener('submit', function(e) {
        const selectedCustomers = document.querySelectorAll('.customer-checkbox:checked');
        if (selectedCustomers.length === 0) {
            e.preventDefault();
            alert('Please select at least one customer.');
        }
    });
});
</script>
@endpush
@endsection 