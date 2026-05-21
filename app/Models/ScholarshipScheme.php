<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'scheme_name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function tiers()
    {
        return $this->hasMany(SchemeTier::class, 'scheme_id');
    }
}
