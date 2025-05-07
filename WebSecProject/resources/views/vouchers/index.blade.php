@extends('layouts.master')
@section('title', 'Vouchers')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Vouchers</h4>
                    <a href="{{ route('vouchers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create New Voucher
                    </a>
                </div>
                <div class="card-body">
                    @if($vouchers->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No vouchers found</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Discount</th>
                                        <th>Expires At</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vouchers as $voucher)
                                        <tr>
                                            <td>{{ $voucher->code }}</td>
                                            <td>{{ $voucher->discount_percentage }}%</td>
                                            <td>{{ $voucher->expires_at->format('Y-m-d') }}</td>
                                            <td>{{ $voucher->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-success" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#sendVoucherModal{{ $voucher->id }}">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                    <a href="{{ route('vouchers.show', $voucher) }}" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('vouchers.edit', $voucher) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('vouchers.destroy', $voucher) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this voucher?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Send Voucher Modal -->
                                                <div class="modal fade" id="sendVoucherModal{{ $voucher->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('vouchers.send', $voucher) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Send Voucher to Customers</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Select Customers</label>
                                                                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                                                            <div class="mb-2">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input select-all-{{ $voucher->id }}" type="checkbox">
                                                                                    <label class="form-check-label">
                                                                                        Select All Customers
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                            <hr>
                                                                            @foreach($customers as $customer)
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input customer-checkbox-{{ $voucher->id }}" 
                                                                                           type="checkbox" 
                                                                                           name="customer_ids[]" 
                                                                                           value="{{ $customer->id }}">
                                                                                    <label class="form-check-label">
                                                                                        {{ $customer->name }} ({{ $customer->email }})
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Send Voucher</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $vouchers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all select-all checkboxes
    document.querySelectorAll('[id^="select-all-"]').forEach(selectAllCheckbox => {
        const voucherId = selectAllCheckbox.id.split('-')[2];
        const customerCheckboxes = document.querySelectorAll(`.customer-checkbox-${voucherId}`);

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
    });

    // Handle form submission
    document.querySelectorAll('form[action*="vouchers/send"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const customerIds = Array.from(this.querySelectorAll('input[name="customer_ids[]"]:checked')).map(cb => cb.value);
            
            if (customerIds.length === 0) {
                alert('Please select at least one customer.');
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';

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
                    const modal = bootstrap.Modal.getInstance(this.closest('.modal'));
                    modal.hide();
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to send voucher');
                }
            })
            .catch(error => {
                alert(error.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Send Voucher';
            });
        });
    });
});
</script>
@endpush
@endsection 