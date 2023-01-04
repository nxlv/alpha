<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsInstancesStrategiesRate extends Model {
    use HasFactory;

    public function substrategies() {
        return $this->hasMany( ProductsInstancesStrategiesSubstrategy::class, 'product_strategy_instance_id', 'instance_id' );
    }
}
