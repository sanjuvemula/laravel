@extends('layouts.app')

@section('page-title', 'Edit Scheme')

@section('content')
@php
    $tiers = old('tiers', $scheme->tiers->map(fn ($tier) => [
        'tier_name' => $tier->tier_name,
        'criteria' => $tier->criteria,
        'amount' => $tier->amount,
        'total_seats' => $tier->total_seats,
        'deadline' => optional($tier->deadline)->format('Y-m-d'),
    ])->values()->toArray());

    if (empty($tiers)) {
        $tiers = [
            ['tier_name' => '', 'criteria' => '', 'amount' => '', 'total_seats' => '', 'deadline' => ''],
        ];
    }
@endphp

<div class="form-shell">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Edit Scholarship Scheme</h3>
            <p class="text-muted mb-0">{{ $institution->institution_name }}</p>
        </div>
        <a href="{{ route('institution.schemes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Schemes
        </a>
    </div>

    <form method="POST" action="{{ route('institution.schemes.update', $scheme->id) }}">
        @csrf
        @method('PUT')

        <div class="neo-form-card">
            <div class="neo-form-header">
                <i class="fas fa-pen-to-square"></i>
                <div>
                    <div class="fw-bold">Edit Scholarship Scheme</div>
                    <div class="small opacity-75">Update details, eligibility, and tier limits</div>
                </div>
            </div>

            <div class="neo-form-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label for="scheme_name" class="form-label">Scheme Name</label>
                        <input id="scheme_name" type="text" name="scheme_name" class="form-control @error('scheme_name') is-invalid @enderror" value="{{ old('scheme_name', $scheme->scheme_name) }}" required>
                        @error('scheme_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-block">Status</label>
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check form-switch pt-2">
                            <input id="is_active" type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $scheme->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label fw-semibold text-muted">Active</label>
                        </div>
                        @error('is_active')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $scheme->description) }}</textarea>
                        @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Tiers</h5>
                        <div class="text-muted small">Each scheme needs at least one tier.</div>
                    </div>
                </div>

                <div id="tierRows" class="d-grid gap-3">
                    @foreach($tiers as $index => $tier)
                        <div class="tier-card position-relative" data-tier-row>
                            <button type="button" class="btn btn-outline-danger btn-sm position-absolute top-0 end-0 mt-3 me-3 px-3" data-remove-tier aria-label="Remove tier">
                                <i class="fas fa-xmark"></i>
                            </button>

                            <div class="pe-5 mb-3">
                                <h6 class="fw-bold mb-0">Tier <span data-tier-number>{{ $loop->iteration }}</span></h6>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tier Name</label>
                                    <input type="text" name="tiers[{{ $index }}][tier_name]" class="form-control @error('tiers.' . $index . '.tier_name') is-invalid @enderror" value="{{ $tier['tier_name'] ?? '' }}" required>
                                    @error('tiers.' . $index . '.tier_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Criteria</label>
                                    <input type="text" name="tiers[{{ $index }}][criteria]" class="form-control @error('tiers.' . $index . '.criteria') is-invalid @enderror" value="{{ $tier['criteria'] ?? '' }}" required>
                                    @error('tiers.' . $index . '.criteria')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Amount</label>
                                    <input type="number" step="0.01" min="0" name="tiers[{{ $index }}][amount]" class="form-control @error('tiers.' . $index . '.amount') is-invalid @enderror" value="{{ $tier['amount'] ?? '' }}" required>
                                    @error('tiers.' . $index . '.amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Total Seats</label>
                                    <input type="number" min="1" name="tiers[{{ $index }}][total_seats]" class="form-control @error('tiers.' . $index . '.total_seats') is-invalid @enderror" value="{{ $tier['total_seats'] ?? '' }}" required>
                                    @error('tiers.' . $index . '.total_seats')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Deadline</label>
                                    <input type="date" name="tiers[{{ $index }}][deadline]" class="form-control @error('tiers.' . $index . '.deadline') is-invalid @enderror" value="{{ $tier['deadline'] ?? '' }}" required>
                                    @error('tiers.' . $index . '.deadline')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('tiers')<div class="text-danger small fw-semibold mt-3">{{ $message }}</div>@enderror

                <button type="button" id="addTier" class="add-tier-card mt-3" style="border: 2px dashed rgba(79, 70, 229, 0.3);">
                    <i class="fas fa-plus me-2"></i>Add Tier
                </button>

                <button type="submit" class="btn btn-primary w-100 mt-4">
                    <i class="fas fa-floppy-disk me-2"></i>Update Scheme
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const tierRows = document.getElementById('tierRows');
    const addTierButton = document.getElementById('addTier');
    let tierIndex = tierRows.querySelectorAll('[data-tier-row]').length;

    function tierTemplate(index) {
        return `
            <div class="tier-card position-relative" data-tier-row>
                <button type="button" class="btn btn-outline-danger btn-sm position-absolute top-0 end-0 mt-3 me-3 px-3" data-remove-tier aria-label="Remove tier">
                    <i class="fas fa-xmark"></i>
                </button>

                <div class="pe-5 mb-3">
                    <h6 class="fw-bold mb-0">Tier <span data-tier-number></span></h6>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tier Name</label>
                        <input type="text" name="tiers[${index}][tier_name]" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Criteria</label>
                        <input type="text" name="tiers[${index}][criteria]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" min="0" name="tiers[${index}][amount]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Seats</label>
                        <input type="number" min="1" name="tiers[${index}][total_seats]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Deadline</label>
                        <input type="date" name="tiers[${index}][deadline]" class="form-control" required>
                    </div>
                </div>
            </div>
        `;
    }

    function refreshTierNumbers() {
        tierRows.querySelectorAll('[data-tier-row]').forEach((row, index) => {
            row.querySelector('[data-tier-number]').textContent = index + 1;
        });
    }

    addTierButton.addEventListener('click', () => {
        tierRows.insertAdjacentHTML('beforeend', tierTemplate(tierIndex));
        tierIndex += 1;
        refreshTierNumbers();
    });

    tierRows.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-remove-tier]');
        if (!removeButton) return;

        const rows = tierRows.querySelectorAll('[data-tier-row]');
        if (rows.length === 1) return;

        removeButton.closest('[data-tier-row]').remove();
        refreshTierNumbers();
    });

    refreshTierNumbers();
</script>
@endpush
