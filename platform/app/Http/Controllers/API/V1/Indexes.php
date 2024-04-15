<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Index;

class Indexes extends Controller {
    public function get_all() {
        return response()->json(
            Index::get()
        );
    }
}
