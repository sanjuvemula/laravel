<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function student() { return $this->hasOne(Student::class); }
    public function institution() { return $this->hasOne(Institution::class); }
    public function isAdmin() { return $this->role === 'admin'; }
    public function isStudent() { return $this->role === 'student'; }
    public function isInstitution() { return $this->role === 'institution'; }
}