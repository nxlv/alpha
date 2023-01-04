<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsInstancesStrategiesSubstrategy extends Model {
    use HasFactory;

    public function rates() {
        return $this->hasMany( ProductsInstancesStrategiesSubstrategiesRate::class, 'product_strategy_id', 'product_strategy_instance_id' );
    }
}
