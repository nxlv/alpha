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
}
