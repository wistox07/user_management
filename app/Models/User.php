<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
        'password' => 'hashed',
    ];

    //Modelo a relacionar
    //Tabla a relacionar
    //llave foranea del modelo actual
    //llave foranea del modelo relacionado
    /*public function systems()
    {
        return $this->belongsToMany(System::class, "user_system_roles", "user_id", "system_id")
            ->withPivot('role_id', 'id', 'user_id', 'role_id');
    }
    */

    public function userSystemRoles()
    {
        return $this->hasMany(UserSystemRole::class, "user_id", "id");
    }

    public function profile(){
        return $this->hasOne(Profile::class, "user_id","id");
    }
}
