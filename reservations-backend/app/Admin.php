<?php

namespace App;

use App\Models\Admin\Brand;
use App\Models\Admin\Store;
use App\Models\Admin\Company;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;
class Admin extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use Notifiable;

    public static $ROLE_ADMIN= 'role_admin';
    public static $ROLE_BRAND= 'role_brand';
    public static $ROLE_STORE= 'role_store';

    protected $table = 'users';
	protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    public static $rules = [
        'name' => 'required',
        'email' => 'required'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function store(){
        return $this->belongsTo(Store::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
}
