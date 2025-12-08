<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'photo',
        'tagline',
    ];

    public function products()
    {
        //Relasi one to many dengan produk
        return $this->hasMany(Product::class);
    }

    //mengambil photo dari storage dan kembali berupa url      
    public function getPhotoAttribute($value)
    {
        if(!$value){
            return null;
        }
        
        return (Storage::url($value));
    }
}
