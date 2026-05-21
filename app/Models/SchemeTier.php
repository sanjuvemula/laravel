<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheme_id',
        'tier_name',
        'criteria',
        'amount',
        'total_seats',
        'filled_seats',
        'deadline',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'deadline' => 'date',
        ];
    }

    public function scheme()
    {
        return $this->belongsTo(ScholarshipScheme::class, 'scheme_id');
    }

    public function scholarships()
    {
        return $this->hasMany(Scholarship::class, 'tier_id');
    }

    public function hasSeatsAvailable(): bool
    {
        return $this->filled_seats < $this->total_seats;
    }
}
