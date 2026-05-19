<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id', 'enrollment_number', 'home_state',
        'studying_state', 'institution_id', 'course', 'year', 'phone'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function institution() { return $this->belongsTo(Institution::class); }
    public function scholarships() { return $this->hasMany(Scholarship::class); }
}