<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarriersProduct extends Model {
    use HasFactory;

    public function carrier() {
        return $this->hasOne( Carrier::class, 'id', 'carrier_id' );
    }

    public function meta() {
        return $this->hasMany( CarriersProductsMetum::class, 'carrier_product_id', 'id' );
    }
}
