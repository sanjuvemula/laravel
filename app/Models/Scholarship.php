<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model {
    use HasFactory;

    protected $fillable = [
        'student_id', 'tier_id', 'scholarship_name', 'amount', 'status', 'remarks', 'document_path'
    ];

    public function student() { return $this->belongsTo(Student::class); }
    public function tier() { return $this->belongsTo(SchemeTier::class, 'tier_id'); }
    public function verification() { return $this->hasOne(Verification::class); }
}
