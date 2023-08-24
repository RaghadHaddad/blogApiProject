<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'admin';
    protected $fillable = [
        'name', 'email', 'password',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

/**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
