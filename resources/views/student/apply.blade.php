@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Apply for Scholarship</h4>
                    <p class="text-muted mb-0">Select a scholarship type and upload your supporting document.</p>
                </div>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>

            <form method="POST" action="{{ route('student.apply.submit') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Scholarship Type</label>
                    <select id="scholarship_name" name="scholarship_name" class="form-select @error('scholarship_name') is-invalid @enderror" required>
                        <option value="">Select scholarship</option>
                        @foreach($scholarshipAmounts as $type => $amount)
                            <option value="{{ $type }}" data-amount="{{ $amount }}" {{ old('scholarship_name') === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                    @error('scholarship_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <div class="input-group">
                        <span class="input-group-text">Rs.</span>
                        <input id="amount" type="number" name="amount" class="form-control" value="{{ old('amount') }}" readonly>
                    </div>
                    <div class="form-text">Amount is auto-filled based on the selected scholarship type.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Supporting Document</label>
                    <input type="file" name="supporting_document" class="form-control @error('supporting_document') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
                    <div class="form-text">Accepted formats: PDF, JPG, JPEG, PNG. Maximum size: 2 MB.</div>
                    @error('supporting_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-paper-plane me-2"></i>Submit Application
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const scholarshipSelect = document.getElementById('scholarship_name');
    const amountInput = document.getElementById('amount');

    function updateAmount() {
        const selectedOption = scholarshipSelect.options[scholarshipSelect.selectedIndex];
        amountInput.value = selectedOption?.dataset.amount || '';
    }

    scholarshipSelect.addEventListener('change', updateAmount);
    updateAmount();
</script>
@endpush
