<?php
// ...existing code...

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class BrandBackground extends Model
{
    protected $table = 'brand_backgrounds';
    protected $fillable = [
        'brand_id',
        'image',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function toArray(){
        return [
            'id'=>$this->id,
            'image'=>$this->image,
        ];
    }
}
