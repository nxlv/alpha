<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsInstancesStrategy extends Model {
    use HasFactory;

    public function fees_current() {
        return $this->hasMany( ProductsInstancesStrategiesFeesCurrent::class, 'product_strategy_instance_id', 'instance_id' );
    }

    public function fees_maximum() {
        return $this->hasMany( ProductsInstancesStrategiesFeesMaximum::class, 'product_strategy_instance_id', 'instance_id' );
    }

    public function rates() {
        return $this->hasMany( ProductsInstancesStrategiesRate::class, 'product_strategy_instance_id', 'instance_id' );
    }
}
