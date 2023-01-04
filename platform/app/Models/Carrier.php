<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model {
    use HasFactory;

    public function meta() {
        return $this->hasMany( CarriersMetum::class, 'carrier_id', 'id' );
    }

    public function products() {
        return $this->hasMany( CarriersProduct::class, 'carrier_id', 'id' );
    }

    public function ratings() {
        return $this->hasMany( CarriersRating::class, 'carrier_id', 'id' );
    }
}
