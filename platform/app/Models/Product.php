<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use HasFactory;

    public function meta() {
        return $this->hasMany( ProductsMetum::class, 'analysis_data_id', 'analysis_data_id' );
    }

    public function profile() {
        return $this->hasMany( ProductsProfile::class, 'product_profile_id', 'product_profile_id' );
    }

    public function instances() {
        return $this->hasMany( ProductsInstance::class, 'product_instance_id', 'product_instance_id' );
    }

    public function carrier_product() {
        return $this->belongsTo( CarriersProduct::class, 'product_id', 'product_id' );
    }

    public function strategy() {
        return $this->hasOne( ProductsInstancesStrategy::class, 'instance_id', 'strategy_details_instance_id' );
    }

    public function rules() {
        return $this->hasOne( Rule::class, 'rule_id', 'rule_id' );
    }

    public function death_benefit() {
        return $this->hasOne( DeathBenefit::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }

    public function income_benefit() {
        return $this->hasOne( IncomeBenefit::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
}
