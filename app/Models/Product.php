<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use SoftDeletes;

    //kolom yang ada pada migration
    protected $fillable = [
        'name',
        'thumbnail',
        'about',
        'price',
        'category_id',
        'is_popular',
    ];

    public function category()
    {
        //Relasi many to one dengan category
        return $this->belongsTo(Category::class);
    }

    public function mercahants()
    {
        //Relasi one to many dengan merchant
        return $this->belongsToMany(Merchant::class, 'merchant_products')->withPivot('stock')->withTimestamps();
    }

    public function warehouses()
    {
        //Relasi one to many dengan warehouse
        return $this->belongsToMany(Warehouse::class, 'warehouse_products')->withPivot('stock')->withTimestamps();
    }

    public function transaction()
    {
        //Relasi one to many dengan transaction product
        return $this->hasMany(TransactionProduct::class);
    }

    public function getWarehouseProductStock()
    {
        //Menghitung total stock dari warehouse
        return $this->warehouses()->sum('stock');
    }

    public function getMerchantProductStock()
    {
        //Menghitung total stock dari merchant
        return $this->mercahants()->sum('stock');
    }

    public function getThumbnailAttribute($value)
    {
        if(!$value){
            return null;
        }
        
        return (Storage::url($value));
    }

}