<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductsProfile;
use App\Models\ProductsInstance;

class Products extends Controller {
    public function get_all() {
        return response()->json(
            ProductsProfile::with(
                'meta',
                'basic',
                'basic.carrier',
                'annuitant_types',
                'annuitization_age_max',
                'cdsc_schedule',
                'fund_type',
                'gmv_increase_rate',
                'gmv_initial_rate',
                'initial_premium',
                'issue_age_annuitant',
                'issue_age_owner',
                'issue_age_joint_annuitant_rule',
                'issue_age_joint_owner_rule',
                'maximum_premium',
                'mva',
                'ownership_type',
                'riders_benefit',
                'rop',
                'states',
                'surrender_waiver',
                'withdrawals_free_rate'
                /*
                'instances',
                'instances.meta',
                'instances.strategies',
                'instances.strategies.index',
                'instances.strategies.fees_current',
                'instances.strategies.fees_maximum',
                'instances.strategies.rates',
                'instances.strategies.rates.substrategies',
                'instances.strategies.rates.substrategies.index',
                'instances.strategies.rates.substrategies.rates'
                */
            )->get()
        );
    }

    public function get_all_instances() {
        return response()->json(
            ProductsInstance::with(
                'meta',
                'strategies',
                'strategies.fees_current',
                'strategies.fees_maximum',
                'strategies.rates',
                'strategies.rates.substrategies',
                'strategies.rates.substrategies.rates'
            )->get()
        );
    }
}
