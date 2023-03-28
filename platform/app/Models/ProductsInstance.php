<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsInstance extends Model {
    use HasFactory;

    public function product() {
        return $this->belongsTo( CarriersProduct::class, 'product_id', 'product_id' );
    }

    public function meta() {
        return $this->hasMany( ProductsInstancesMetum::class, 'product_instance_id', 'product_instance_id' );
    }

    public function strategies() {
        return $this->hasMany( ProductsInstancesStrategy::class, 'product_instance_id', 'product_instance_id' );
    }
}
