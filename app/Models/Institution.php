<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id', 'institution_name', 'state',
        'city', 'address', 'registration_number', 'institution_type',
        'affiliated_university', 'contact_email', 'contact_phone', 'status'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function students() { return $this->hasMany(Student::class); }
    public function verifications() { return $this->hasMany(Verification::class); }
}
