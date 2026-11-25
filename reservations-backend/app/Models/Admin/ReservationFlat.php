<?php

namespace App\Models\Admin;

use App\Traits\DatesTranslator;
use Dirape\Token\DirapeToken;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Date\Date;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class ReservationFlat
 * @package App\Models\Admin
 * @version March 13, 2018, 1:00 pm UTC
 *
 * @property string name
 * @property string last_name
 * @property string email
 * @property date birthday
 * @property string mobile
 * @property integer store
 * @property integer people
 * @property date reservation_date
 * @property string reservation_time
 * @property integer brand
 */
class ReservationFlat extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, DatesTranslator;
    use DirapeToken;

    public $table = 'reservation_flats';


    protected $dates = ['deleted_at'];
    protected $DT_Column='token';


    public $fillable = [
        'name',
        'last_name',
        'email',
        'birthday',
        'mobile',
        'store',
        'store_id',
        'people',
        'reservation_date',
        'reservation_time',
        'reservation',
        'brand',
        'brand_id',
        'company_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'birthday' => 'date',
        'mobile' => 'string',
        'store_id' => 'integer',
        'company_id' => 'integer',
        'store' => 'string',
        'people' => 'integer',
        'reservation_date' => 'date',
        'reservation_time' => 'string',
        'reservation' => 'datetime',
        'brand_id' => 'integer',
        'brand' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'last_name' => 'required',
        'email' => 'required',
        'birthday' => 'required',
        'mobile' => 'required',
        'store_id' => 'required',
        'people' => 'required',
        'reservation_date' => 'required',
        'reservation_time' => 'required',
        'reservation' => 'required',
        'brand_id' => 'required'
    ];

    public function getReservationAttribute($reservation)
    {
        return new Date($reservation);
    }

    public function entity(){
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Admin\Company::class);
    }
}