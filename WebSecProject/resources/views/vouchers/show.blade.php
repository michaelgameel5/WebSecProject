@extends('layouts.master')
@section('title', 'Voucher Details')
@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-ticket-alt me-2"></i>Voucher Details</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Vouchers
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">Voucher Information</h5>
                    <table class="table">
                        <tr>
                            <th>Code:</th>
                            <td>
                                <span class="badge bg-primary">{{ $voucher->code }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Discount:</th>
                            <td>{{ $voucher->discount_percentage }}%</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($voucher->is_used)
                                    <span class="badge bg-danger">Used</span>
                                @elseif($voucher->expires_at && $voucher->expires_at->isPast())
                                    <span class="badge bg-warning">Expired</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Expires:</th>
                            <td>{{ $voucher->expires_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $voucher->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i>How to Use</h5>
                        <p class="mb-0">
                            Use this voucher code during checkout to get {{ $voucher->discount_percentage }}% off your purchase.
                            The voucher will be automatically applied when you enter the code.
                        </p>
                    </div>
                    @if(!$voucher->is_used && !$voucher->expires_at->isPast())
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle me-2"></i>Voucher is Valid</h5>
                            <p class="mb-0">
                                This voucher is active and ready to use. Add items to your cart and use this code during checkout.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 