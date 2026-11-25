<?php

namespace App\Models\Admin;

use App\Admin;
use App\User;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Jenssegers\Date\Date;
/**
 * Class Reservation
 * @package App\Models\Admin
 * @version March 28, 2018, 6:21 pm UTC
 *
 * @property integer user_id
 * @property integer store_id
 * @property integer brand_id
 * @property integer people
 * @property date reservation_date
 * @property string reservation_time
 * @property longText comment
 * @property longText admin_notes
 * @property enum status
 */
class Reservation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    public static $STATUS_MAXIMUM_CAPACITY='maximum_capacity';
    public static $STATUS_OPEN='open';
    public static $STATUS_CANCELED='canceled';
    public static $STATUS_ATTENDED='attended';
    public static $STATUS_NOT_ATTENDED='not_attended';
    public static $STATUS_NOT_STATUS='s/e';

    public $table = 'reservations';
    

    //protected $dates = ['deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'reservation_date'];


    public $fillable = [
        'user_id',
        'store_id',
        'brand_id',
        'company_id',
        'people',
        'reservation_date',
        'reservation_time',
        'comment',
        'admin_notes',
        'status',
        'token',
        'admin_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'store_id' => 'integer',
        'brand_id' => 'integer',
        'people' => 'integer',
        'reservation_date' => 'datetime',
        'reservation_time' => 'string',
        'status' => 'string',
        'token' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'store_id' => 'required',
        'brand_id' => 'required',
        'people' => 'required',
        'reservation_date' => 'required',
        'reservation_time' => 'required',
        'status' => 'required',
        'token' => 'required',
    ];

    public function getReservationAttribute($reservation)
    {
        return new Date($reservation);
    }

    /**
     * @return mixed
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
    /**
     * @return mixed
     */
    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    /**
     * @return mixed
     */
    public function store(){
        return $this->belongsTo(Store::class);
    }

    /**
     * @return mixed
     */
    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function toArray(){
        return [
            'id'=>$this->id,
            'user'=>$this->user->toArray(),
            'store'=>$this->store->toArray(),
            'brand'=>$this->brand->toArray(),
            'people'=>$this->people,
            'reservation_date'=>$this->reservation_date,
            'reservation_time'=>$this->reservation_time,
            'comment'=>$this->comment,
            'admin_notes'=>$this->admin_notes,
            'status'=>$this->status,
            'token'=>$this->token,
            'status_message'=>__('messages.'.strtolower($this->status)),
        ];
    }
    public function getReservationTimeAttribute($value){
        return \App\Helper\Helper::getFormatTime($value);
    }

    public function toFrontArray(){
        return [
            'id'=>$this->id,
            'user'=>$this->user->toFrontArray(),
            'store'=>$this->store->toFrontArray(),
            'store_id'=>$this->store->id,
            'brand'=>$this->brand->toFrontArray(),
            'brand_id'=>$this->brand->id,
            'people'=>$this->people,
            'reservation_date'=>$this->reservation_date->format('Y-m-d'),
            'reservation_time'=>$this->reservation_time,
            'comment'=>$this->comment,
            'admin_notes'=>$this->admin_notes,
            'status'=>$this->status,
            'token'=>$this->token,
            'status_message'=>__('messages.'.strtolower($this->status)),
        ];
    }
    /**
     * URL de la web para modificar o cancelar; sin acción, para hacer una nueva reserva.
     */
    public function getWebUrl($action = null){
        $company = $this->brand->company ? $this->brand->company->name : 'Default';
        $url = rtrim(config('app.web_url'), '/') . '/' . rawurlencode($company) . '/reserva/' . rawurlencode($this->brand->slug);
        return $action ? $url . '/' . $action . '/' . rawurlencode($this->token) : $url;
    }
    public function company()
    {
        return $this->belongsTo(\App\Models\Admin\Company::class);
    }

}
