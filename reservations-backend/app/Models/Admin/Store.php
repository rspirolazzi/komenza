<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class Store
 * @package App\Models\Admin
 * @version March 13, 2018, 1:10 pm UTC
 *
 * @property string name
 * @property integer brand_id
 * @property string address
 * @property string telephone
 */
class Store extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    public $table = 'stores';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'brand_id',
        'company_id',
        'address',
        'telephone',
        'text_schedule',
        'lat',
        'lon',
        'delivery_times',
        'max_persons',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'brand_id' => 'integer',
        'company_id' => 'integer',
        'address' => 'string',
        'telephone' => 'string',
        'text_schedule' => 'string',
        'lat' => 'string',
        'lon' => 'string',
        'delivery_times' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'brand_id' => 'required'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    /**
     * Link de Google Maps para llegar a la sucursal; no usa clave. Null si no hay ubicación.
     */
    public function getDirectionsUrlAttribute(){
        $destination = $this->lat && $this->lon ? $this->lat . ',' . $this->lon : $this->address;
        return $destination ? 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($destination) : null;
    }
    /**
     * Imagen de Google Static Maps. Null si no hay clave o coordenadas.
     */
    public function getStaticMapUrlAttribute(){
        $key = config('services.google_maps.key');
        if (!$key || !$this->lat || !$this->lon) {
            return null;
        }
        $point = rawurlencode($this->lat . ',' . $this->lon);
        return 'https://maps.googleapis.com/maps/api/staticmap?center=' . $point . '&zoom=15&size=500x385&markers=' . $point . '&key=' . rawurlencode($key);
    }
    public function getNameWithBrandAttribute(){
        return "{$this->brand->shortName}-{$this->name}";
    }

    public static function pluckStores(){
        $result =  Store::all()->mapWithKeys(function($row){
            return [$row->id=>"{$row->brand->shortName} -> {$row->name}"];
        });
        //dd($result, Store::all());
        return $result;
    }
    public static function pluckStoresByBrand($brand_id=null){
        $result =[];
        if($brand_id){
            $result =  Store::where('brand_id', $brand_id)->get()->mapWithKeys(function($row){
                return [$row->id=>"{$row->brand->shortName} -> {$row->name}"];
            });
        }
        return $result;
    }

    public function toArray(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand_id' => $this->brand_id,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'text_schedule' => $this->text_schedule,
            'lat' => $this->lat,
            'lon' => $this->lon,
            "delivery_times" => $this->delivery_times,
            "max_persons" => $this->max_persons,
            "max_persons_day" => 50,
        ];
    }
    public function toFrontArray(){
        return [
            'name' => $this->name,
            'brand_id' => $this->brand_id,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'text_schedule' => $this->text_schedule,
            'lat' => $this->lat,
            'lon' => $this->lon,
        ];
    }
    public function getDeliveryTimesAttribute($value)
    {
        try {
            if (empty($value)) {
                return [];
            }
            $json = json_decode($value, true);
            return $json;
        } catch (\Exception $ex) {

        }
        return [];
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Admin\Company::class);
    }
}
