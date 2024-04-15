<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Carrier;

class Carriers extends Controller {
    public function get_all() {
        return response()->json(
            Carrier::with(
                'meta',
                'products',
                'products.meta',
                'ratings'
            )->get()
        );
    }
}
