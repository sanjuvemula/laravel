@extends('layouts.app')

@section('page-title', 'Apply for Scholarship')

@section('content')
<div class="form-shell">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Apply for Scholarship</h3>
            <p class="text-muted mb-0">{{ $student->institution?->institution_name ?? 'Institution not assigned' }}</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Dashboard
        </a>
    </div>

    @if($schemes->isEmpty())
        <div class="status-banner pending">
            <div class="fw-bold mb-1">No active schemes available</div>
            <div class="text-muted">Your institution has not opened any scholarship schemes yet.</div>
        </div>
    @else
        <div class="neo-form-card">
            <div class="neo-form-header">
                <i class="fas fa-file-circle-plus"></i>
                <div>
                    <div class="fw-bold">Apply for Scholarship</div>
                    <div class="small opacity-75">Choose a scheme, tier, and supporting document</div>
                </div>
            </div>

            <div class="neo-form-body">
                <form method="POST" action="{{ route('student.apply.submit') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="scheme_id" class="form-label">Scheme</label>
                            <select id="scheme_id" name="scheme_id" class="form-select @error('scheme_id') is-invalid @enderror" required>
                                <option value="">Select scheme</option>
                                @foreach($schemes as $scheme)
                                    <option value="{{ $scheme->id }}" {{ old('scheme_id') == $scheme->id ? 'selected' : '' }}>
                                        {{ $scheme->scheme_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('scheme_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tier_id" class="form-label">Tier</label>
                            <select id="tier_id" name="tier_id" class="form-select @error('tier_id') is-invalid @enderror" required disabled>
                                <option value="">Select scheme first</option>
                            </select>
                            @error('tier_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input id="amount" type="text" class="form-control" value="" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="supporting_document" class="form-label">Supporting Document</label>
                            <input id="supporting_document" type="file" name="supporting_document" class="form-control @error('supporting_document') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="form-text text-muted">PDF, JPG, JPEG or PNG. Maximum size 2 MB.</div>
                            @error('supporting_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div id="tierDetails" class="neo-inset mt-4 p-3 d-none">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-muted small">Available Seats</div>
                                <div id="tierSeats" class="fw-semibold">-</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Deadline</div>
                                <div id="tierDeadline" class="fw-semibold">-</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Criteria</div>
                                <div id="tierCriteria" class="fw-semibold">-</div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4">
                        <i class="fas fa-paper-plane me-2"></i>Submit Application
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const schemeSelect = document.getElementById('scheme_id');
    const tierSelect = document.getElementById('tier_id');
    const amountInput = document.getElementById('amount');
    const tierDetails = document.getElementById('tierDetails');
    const tierSeats = document.getElementById('tierSeats');
    const tierDeadline = document.getElementById('tierDeadline');
    const tierCriteria = document.getElementById('tierCriteria');
    const oldTierId = @json(old('tier_id'));
    const tierBaseUrl = @json(url('/schemes'));

    function resetTierFields(message = 'Select scheme first') {
        tierSelect.innerHTML = `<option value="">${message}</option>`;
        tierSelect.disabled = true;
        amountInput.value = '';
        tierDetails.classList.add('d-none');
    }

    function updateTierDetails() {
        const selectedOption = tierSelect.options[tierSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            amountInput.value = '';
            tierDetails.classList.add('d-none');
            return;
        }

        amountInput.value = Number(selectedOption.dataset.amount || 0).toFixed(2);
        tierSeats.textContent = selectedOption.dataset.seats || '-';
        tierDeadline.textContent = selectedOption.dataset.deadline || '-';
        tierCriteria.textContent = selectedOption.dataset.criteria || '-';
        tierDetails.classList.remove('d-none');
    }

    async function loadTiers(schemeId, selectedTierId = null) {
        resetTierFields('Loading tiers...');

        if (!schemeId) {
            resetTierFields();
            return;
        }

        try {
            const response = await fetch(`${tierBaseUrl}/${schemeId}/tiers`, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                throw new Error('Unable to load tiers');
            }

            const tiers = await response.json();
            tierSelect.innerHTML = '<option value="">Select tier</option>';

            tiers.forEach((tier) => {
                const option = document.createElement('option');
                option.value = tier.id;
                option.textContent = `${tier.tier_name} - Rs. ${Number(tier.amount).toFixed(2)} (${tier.available_seats} seats)`;
                option.dataset.amount = tier.amount;
                option.dataset.criteria = tier.criteria;
                option.dataset.deadline = tier.deadline;
                option.dataset.seats = tier.available_seats;
                option.disabled = !tier.has_seats_available;

                if (String(selectedTierId) === String(tier.id)) {
                    option.selected = true;
                }

                tierSelect.appendChild(option);
            });

            tierSelect.disabled = tiers.length === 0;

            if (tiers.length === 0) {
                resetTierFields('No open tiers');
            } else {
                updateTierDetails();
            }
        } catch (error) {
            resetTierFields('Unable to load tiers');
        }
    }

    schemeSelect?.addEventListener('change', () => loadTiers(schemeSelect.value));
    tierSelect?.addEventListener('change', updateTierDetails);

    if (schemeSelect?.value) {
        loadTiers(schemeSelect.value, oldTierId);
    }
</script>
@endpush
