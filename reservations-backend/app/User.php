<?php

namespace App;

use App\Models\Admin\Reservation;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements AuthenticatableUserContract, Auditable
{
    use \OwenIt\Auditing\Auditable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'telephone', 'birthday', 'email', 'password', 'birthday', 'mobile', 'company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'role'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();  // Eloquent model method
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'id' => $this->id,
            ]
        ];
    }

    public function getFullNameAttribute()
    {
        $fullName = [$this->name, $this->last_name];
        return trim(implode(' ', $fullName));
    }

    public function getFullNameWithEmailAttribute()
    {
        $fullName = [$this->fullName, '('. $this->email. ')'];
        return trim(implode(' ', $fullName));
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function toFrontArray(){
        return [
            "name"=>$this->name,
            "last_name"=>$this->last_name,
            "telephone"=>$this->telephone,
            "birthday"=>$this->birthday,
            "mobile"=>$this->mobile,
            "email"=>$this->email,
            "company_id"=>$this->company_id,
        ];
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Admin\Company::class);
    }
}
