<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantProduct extends Model
{
    use SoftDeletes;

    //protected $table = 'merchant_products';

    //mass assignable = kolom yang boleh diisi secara massal
    protected $fillable = [
        'merchant_id',
        'product_id',
        'stock',
        'warehouse_id',
    ];

    public function merchant()
    {
        //Relasi many to one dengan merchant
        return $this->belongsTo(Merchant::class);
    }

    public function product()
    {
        //Relasi many to one dengan product
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        //Relasi many to one dengan warehouse
        return $this->belongsTo(Warehouse::class);
    }


}
