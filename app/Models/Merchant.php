<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Merchant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'photo',
        'keeper_id', //relasi dengan user
    ];

    public function keeper()
    {
        //Relasi many to one dengan user
        return $this->belongsTo(User::class, 'keeper_id');
    }

    public function products()
    {
        //Relasi man to many dengan produk, melalui tabel pivot merchant_products
        return $this->belongsToMany(Product::class, 'merchant_products')->withPivot(['stock', 'warehouse_id'])->withTimestamps();
    }

    public function transactions()
    {
        //relasi one to many dengan transaksi
        return $this->hasMany(Transaction::class);
    }

    public function getPhotoAttribute($value)
    {
        if(!$value){
            return null;
        }
        
        return (Storage::url($value));
    }


}
