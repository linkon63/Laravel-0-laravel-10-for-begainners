<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

// use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function isAdmin(): Attribute
    {
        $admins = ['m.alinkon10@gmail.com'];
        return Attribute::make(
            get: fn () => in_array($this->email, $admins)
        );
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // protected function isAdmin(): Attribute
    // {
    //     $admins = ['sarthak@bitfumes.com'];
    //     return Attribute::make(
    //         get: fn () => in_array($this->email, $admins)
    //     );
    // }

    // mutaion of passworf bcrypted
}
