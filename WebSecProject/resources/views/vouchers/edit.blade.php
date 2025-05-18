@extends('layouts.master')
@section('title', 'Edit Voucher')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Voucher</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('vouchers.update', $voucher) }}" method="POST" id="updateVoucherForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Voucher Code</label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code', $voucher->code) }}" 
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="discount_percentage" class="form-label">Discount Percentage</label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('discount_percentage') is-invalid @enderror" 
                                       id="discount_percentage" 
                                       name="discount_percentage" 
                                       value="{{ old('discount_percentage', $voucher->discount_percentage) }}" 
                                       min="1" 
                                       max="100" 
                                       required>
                                <span class="input-group-text">%</span>
                                @error('discount_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Expiry Date</label>
                            <input type="date" 
                                   class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" 
                                   name="expires_at" 
                                   value="{{ old('expires_at', $voucher->expires_at->format('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Customers to Send Voucher</label>
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
                                               id="customer{{ $customer->id }}">
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
                            <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="updateButton">Update Voucher</button>
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
    const form = document.getElementById('updateVoucherForm');
    const updateButton = document.getElementById('updateButton');
    const selectAllCheckbox = document.getElementById('selectAll');
    const customerCheckboxes = document.querySelectorAll('.customer-checkbox');

    // Handle select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        customerCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    customerCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(customerCheckboxes).every(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
        });
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const customerIds = Array.from(this.querySelectorAll('input[name="customer_ids[]"]:checked')).map(cb => cb.value);
        
        if (customerIds.length === 0) {
            alert('Please select at least one customer.');
            return;
        }

        // Disable submit button and show loading state
        updateButton.disabled = true;
        updateButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';

        // Submit form
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route('vouchers.index') }}';
            } else {
                let errorMessage = data.message;
                if (data.errors) {
                    errorMessage = Object.values(data.errors).flat().join('\n');
                }
                throw new Error(errorMessage);
            }
        })
        .catch(error => {
            alert(error.message);
            updateButton.disabled = false;
            updateButton.innerHTML = 'Update Voucher';
        });
    });
});
</script>
@endpush
@endsection 