<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Product;

class Products extends Controller {
    public function get_all() {
        return response()->json(
            Product::with(
                'meta',
                'profile',
                'instances',
                'instances.meta',
                'instances.strategies',
                'instances.strategies.fees_current',
                'instances.strategies.fees_maximum',
                'instances.strategies.rates',
                'instances.strategies.rates.substrategies',
                'instances.strategies.rates.substrategies.rates'
            )->get()
        );
    }
}
