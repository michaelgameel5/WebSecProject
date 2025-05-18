@extends('layouts.master')
@section('title', 'My Cards')
@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h1><i class="fas fa-credit-card me-2"></i>Credit Requests</h1>

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