<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsProfile extends Model {
    use HasFactory;

    public function basic() {
        return $this->hasOne( CarriersProduct::class, 'product_id', 'product_id' );
    }

    public function meta() {
        return $this->hasMany( ProductsProfilesMetum::class, 'product_profile_id', 'product_profile_id' );
    }

    public function annuitant_types() {
        return $this->hasMany( ProductsProfilesAnnuitantType::class, 'product_profile_id', 'product_profile_id' );
    }

    public function annuitization_age_max() {
        return $this->hasMany( ProductsProfilesAnnuitizationAgeMaximum::class, 'product_profile_id', 'product_profile_id' );
    }

    public function cdsc_schedule() {
        return $this->hasMany( ProductsProfilesCdscSchedule::class, 'product_profile_id', 'product_profile_id' );
    }

    public function fund_type() {
        return $this->hasMany( ProductsProfilesFundType::class, 'product_profile_id', 'product_profile_id' );
    }

    public function gmv_increase_rate() {
        return $this->hasMany( ProductsProfilesGmvIncreaseRate::class, 'product_profile_id', 'product_profile_id' );
    }

    public function gmv_initial_rate() {
        return $this->hasMany( ProductsProfilesGmvInitialRate::class, 'product_profile_id', 'product_profile_id' );
    }

    public function initial_premium() {
        return $this->hasMany( ProductsProfilesInitialPremium::class, 'product_profile_id', 'product_profile_id' );
    }

    public function issue_age_annuitant() {
        return $this->hasMany( ProductsProfilesIssueAgeAnnuitant::class, 'product_profile_id', 'product_profile_id' );
    }

    public function issue_age_owner() {
        return $this->hasMany( ProductsProfilesIssueAgeOwner::class, 'product_profile_id', 'product_profile_id' );
    }

    public function issue_age_joint_annuitant_rule() {
        return $this->hasMany( ProductsProfilesIssueAgeJointAnnuitantRule::class, 'product_profile_id', 'product_profile_id' );
    }

    public function issue_age_joint_owner_rule() {
        return $this->hasMany( ProductsProfilesIssueAgeJointOwnerRule::class, 'product_profile_id', 'product_profile_id' );
    }

    public function maximum_premium() {
        return $this->hasMany( ProductsProfilesMaximumPremium::class, 'product_profile_id', 'product_profile_id' );
    }

    public function mva() {
        return $this->hasMany( ProductsProfilesMva::class, 'product_profile_id', 'product_profile_id' );
    }

    public function ownership_type() {
        return $this->hasMany( ProductsProfilesOwnershipType::class, 'product_profile_id', 'product_profile_id' );
    }

    public function riders_benefit() {
        return $this->hasMany( ProductsProfilesRidersBenefit::class, 'product_profile_id', 'product_profile_id' );
    }

    public function rop() {
        return $this->hasMany( ProductsProfilesRop::class, 'product_profile_id', 'product_profile_id' );
    }

    public function states() {
        return $this->hasMany( ProductsProfilesState::class, 'product_profile_id', 'product_profile_id' );
    }

    public function surrender_waiver() {
        return $this->hasMany( ProductsProfilesSurrenderWaiver::class, 'product_profile_id', 'product_profile_id' );
    }

    public function withdrawals_free_rate() {
        return $this->hasMany( ProductsProfilesWithdrawalsFreeRate::class, 'product_profile_id', 'product_profile_id' );
    }
}
