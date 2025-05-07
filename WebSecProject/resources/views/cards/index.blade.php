@extends('layouts.master')
@section('title', 'My Cards')
@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h1><i class="fas fa-credit-card me-2"></i>My Cards</h1>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if($cards->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>You haven't added any cards yet.
                </div>
            @else
                <div class="row">
                    @foreach($cards as $card)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-credit-card me-2"></i>Card ending in {{ substr($card->card_number, -4) }}
                                        </h5>
                                        @if($card->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </div>
                                    
                                    <p class="card-text">
                                        <strong>Expiry Date:</strong> {{ $card->expiry_date->format('m/Y') }}<br>
                                        <strong>Balance:</strong> ${{ number_format($card->credit_balance, 2) }}
                                    </p>

                                    @if($card->billingAddress)
                                        <p class="card-text">
                                            <strong>Billing Address:</strong><br>
                                            {{ $card->billingAddress->street_address }}<br>
                                            {{ $card->billingAddress->city }}, {{ $card->billingAddress->state }} {{ $card->billingAddress->postal_code }}<br>
                                            {{ $card->billingAddress->country }}
                                        </p>
                                    @endif

                                    <div class="d-flex justify-content-between">
                                        @if($card->is_active)
                                            <form action="{{ route('cards.deactivate', $card) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to deactivate this card?')">
                                                    <i class="fas fa-ban me-1"></i>Deactivate
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestCreditModal{{ $card->id }}">
                                            <i class="fas fa-plus me-1"></i>Request Credit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Request Credit Modal -->
                        <div class="modal fade" id="requestCreditModal{{ $card->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Request Credit</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('cards.request-credit', $card) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="amount{{ $card->id }}" class="form-label">Amount ($)</label>
                                                <input type="number" class="form-control" id="amount{{ $card->id }}" name="amount" step="0.01" min="0.01" required>
                                            </div>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>Your credit request will be reviewed by an employee.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Submit Request</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-plus-circle me-2"></i>Add New Card</h5>
                    <form action="{{ route('cards.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Card Number</label>
                            <input type="text" class="form-control @error('card_number') is-invalid @enderror" 
                                   id="card_number" name="card_number" maxlength="16" required>
                            @error('card_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                       id="expiry_date" name="expiry_date" required>
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control @error('cvv') is-invalid @enderror" 
                                       id="cvv" name="cvv" maxlength="4" required>
                                @error('cvv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="street_address" class="form-label">Street Address</label>
                            <input type="text" class="form-control @error('street_address') is-invalid @enderror" 
                                   id="street_address" name="street_address" required>
                            @error('street_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state" required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" required>
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" required>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i>Add Card
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format card number input
    const cardInputs = document.querySelectorAll('input[name="card_number"]');
    cardInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').substring(0, 16);
        });
    });

    // Format CVV input
    const cvvInputs = document.querySelectorAll('input[name="cvv"]');
    cvvInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').substring(0, 4);
        });
    });
});
</script>
@endpush
@endsection 