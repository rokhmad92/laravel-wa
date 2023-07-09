<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'username',
        'email',
        'nomer',
        'otp',
        'password',
        'otp_verify',
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
        'password' => 'hashed',
    ];

    public function scopeToken(Builder $query, $request): void
    {
        $query->where('otp', $request->token)->where('otp_verify', false);
    }

    public function scopeUser(Builder $query,array $request): void
    {
        $query->when(isset($request['nomer']) ? $request['nomer'] : false, function($query, $nomer) {
            return $query->where('nomer', $nomer);
        });

        $query->when(isset($request['username']) ? $request['username'] : false, function($query, $username) {
            return $query->where('username', $username);
        });
    }

    public function scopeVerify(Builder $query): void
    {
        $query->where('otp_verify', true);
    }
}
