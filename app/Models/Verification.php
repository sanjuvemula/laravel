<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model {
    use HasFactory;

    protected $fillable = [
        'scholarship_id', 'institution_id', 'status', 'remarks', 'verified_at'
    ];

    public function scholarship() { return $this->belongsTo(Scholarship::class); }
    public function institution() { return $this->belongsTo(Institution::class); }
}