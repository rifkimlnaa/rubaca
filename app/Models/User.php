<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable {
 use HasFactory, Notifiable;
 protected $fillable=['name','email','password','role','member_code','phone','is_active'];
 protected $hidden=['password','remember_token'];
 protected function casts(): array { return ['email_verified_at'=>'datetime','password'=>'hashed','is_active'=>'boolean']; }
 public function sendPasswordResetNotification($token): void { $this->notify(new ResetPasswordNotification($token)); }
 public function isAdmin(): bool { return $this->role === 'admin'; }
 public function isStaff(): bool { return $this->role === 'staff'; }
 public function isMember(): bool { return $this->role === 'member'; }
 public function loans(): HasMany { return $this->hasMany(Loan::class); }
 public function readingLists(): HasMany { return $this->hasMany(ReadingList::class); }
 public function readingProgress(): HasMany { return $this->hasMany(ReadingProgress::class); }
}