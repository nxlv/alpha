<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;

class AnalysisCache extends Model {
    use HasFactory;

    protected $table = 'analysis_cache';

    public function analysis() {
        return $this->belongsTo( Product::class, 'analysis_data_id', 'analysis_data_id' );
    }
}
