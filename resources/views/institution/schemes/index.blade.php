@extends('layouts.app')

@section('page-title', 'Scholarship Schemes')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Scholarship Schemes</h3>
        <p class="text-muted mb-0">{{ $institution->institution_name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('institution.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Dashboard
        </a>
        @if($institution->status === 'approved')
            <a href="{{ route('institution.schemes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Scheme
            </a>
        @endif
    </div>
</div>

@if($institution->status !== 'approved')
    <div class="status-banner pending">
        <div class="fw-bold mb-1">Institution approval pending</div>
        <div class="text-muted">Schemes can be created after your institution profile is approved.</div>
    </div>
@endif

@if($schemes->isEmpty())
    <div class="neo-raised p-5 text-center" style="border-radius:20px">
        <div class="stat-icon mx-auto mb-3" style="background:#4f46e5">
            <i class="fas fa-layer-group"></i>
        </div>
        <h5 class="fw-bold">No schemes found</h5>
        <p class="text-muted mb-3">Create scholarship schemes with tiered benefits for your students.</p>
        @if($institution->status === 'approved')
            <a href="{{ route('institution.schemes.create') }}" class="btn btn-primary">Create Scheme</a>
        @endif
    </div>
@else
    <div class="row g-4">
        @foreach($schemes as $scheme)
            @php
                $collapseId = 'schemeTiers' . $scheme->id;
                $totalSeats = $scheme->tiers->sum('total_seats');
                $filledSeats = $scheme->tiers->sum('filled_seats');
            @endphp
            <div class="col-md-6 col-xl-4">
                <div class="neo-raised scheme-card h-100">
                    <div class="scheme-strip"></div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $scheme->scheme_name }}</h5>
                                <div class="text-muted small">{{ $scheme->updated_at?->format('d M Y') }}</div>
                            </div>
                            <a href="{{ route('institution.schemes.edit', $scheme->id) }}" class="btn btn-outline-primary btn-sm" aria-label="Edit scheme">
                                <i class="fas fa-pen-to-square"></i>
                            </a>
                        </div>

                        <p class="text-muted mb-3" style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; min-height:42px;">
                            {{ $scheme->description ?: 'No description provided.' }}
                        </p>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="neo-pill">
                                <i class="fas fa-circle {{ $scheme->is_active ? 'text-success' : 'text-muted' }}"></i>
                                {{ $scheme->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="neo-pill">
                                <i class="fas fa-layer-group text-primary"></i>
                                {{ $scheme->tiers->count() }} Tiers
                            </span>
                            <span class="neo-pill">
                                <i class="fas fa-chair text-primary"></i>
                                {{ $filledSeats }}/{{ $totalSeats }}
                            </span>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm flex-fill" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                                <i class="fas fa-chevron-down me-1"></i>Expand
                            </button>
                            @if($scheme->is_active)
                                <form method="POST" action="{{ route('institution.schemes.destroy', $scheme->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Deactivate this scheme?')" aria-label="Deactivate scheme">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="collapse mt-4" id="{{ $collapseId }}">
                            @if($scheme->tiers->isEmpty())
                                <p class="text-muted small mb-0">No tiers added.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>Tier</th>
                                                <th>Amount</th>
                                                <th>Seats</th>
                                                <th>Deadline</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($scheme->tiers as $tier)
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold">{{ $tier->tier_name }}</div>
                                                        <div class="small text-muted">{{ $tier->criteria }}</div>
                                                    </td>
                                                    <td>Rs. {{ number_format((float) $tier->amount, 2) }}</td>
                                                    <td>{{ $tier->filled_seats }}/{{ $tier->total_seats }}</td>
                                                    <td>{{ $tier->deadline?->format('d M Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
