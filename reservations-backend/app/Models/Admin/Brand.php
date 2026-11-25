<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use \App\Models\Admin\Company;
/**
 * Class Brand
 * @package App\Models\Admin
 * @version March 13, 2018, 1:08 pm UTC
 *
 * @property string name
 * @property string logo
 */
class Brand extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    public $table = 'brands';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'logo',
        'company_id',
        'styles',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'logo' => 'string',
        'company_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
    ];

    public function stores(){
        return $this->hasMany(Store::class);
    }
    public function toArray(){
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'shortName'=>$this->shortName,
            'logo'=>$this->logo,
            'stores'=>$this->stores,
            'styles'=>$this->styles,
            'backgrounds'=>$this->backgrounds->map(function($background){
                return $background->toArray();
            }),
        ];
    }
    public function toFrontArray(){
        return [
            'name'=>$this->name,
            'shortName'=>$this->shortName,
            'logo'=>$this->logo,
        ];
    }

    public function getShortNameAttribute(){
        $names = explode(' ',$this->name, 2);
        $name='';
        if(isset($names[0])){
            $name = strtoupper(substr($names[0],0,1));
            if(isset($names[1])){
                $name .= strtoupper(substr($names[1],0,1));
            }
        }
        return $name;
    }
    /**
     * Color principal de la marca; si no definió uno válido, Albahaca de Komenza.
     */
    public function getPrimaryColorAttribute(){
        $styles = json_decode($this->styles, true);
        $color = is_array($styles) && isset($styles['primary']) ? $styles['primary'] : '';
        return is_string($color) && preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $color) ? $color : '#0F4C3A';
    }
    public function getSlugAttribute(){
        return str_replace(' ', '-', strtolower($this->name));
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function backgrounds()
    {
        return $this->hasMany(BrandBackground::class);
    }
}
