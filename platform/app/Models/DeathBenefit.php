<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeathBenefit extends Model
{
    use HasFactory;

    public function meta() {
        return $this->hasMany( DeathBenefitsMetum::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }

    // rider fees
    public function rider_fee_current() {
        return $this->hasMany( DeathBenefitsCurrentRiderFee::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }
    public function rider_fee_minimum() {
        return $this->hasMany( DeathBenefitsMinRiderFee::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }
    public function rider_fee_maximum() {
        return $this->hasMany( DeathBenefitsMaxRiderFee::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }

    // rider fees
    public function premium_initial() {
        return $this->hasMany( DeathBenefitsInitialPremium::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }
    public function premium_max() {
        return $this->hasMany( DeathBenefitsMaxPremium::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }
    public function premium_bonus() {
        return $this->hasMany( DeathBenefitsPremiumBonus::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }
    public function premium_multiplier() {
        return $this->hasMany( DeathBenefitsPremiumMultiplier::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }

    // interest
    public function interest_crediting() {
        return $this->hasMany( DeathBenefitsInterestCrediting::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }
    public function interest_bonus_crediting() {
        return $this->hasMany( DeathBenefitsInterestBonusCrediting::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }
    public function interest_multiplier_crediting() {
        return $this->hasMany( DeathBenefitsInterestMultiplierCrediting::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }

    // other
    public function enhancement() {
        return $this->hasOne( DeathBenefitsEnhancement::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }
    public function roll_up() {
        return $this->hasMany( DeathBenefitsRollUp::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }
    public function step_up() {
        return $this->hasMany( DeathBenefitsStepUp::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }

    public function states() {
        return $this->hasMany( DeathBenefitsState::class, 'death_benefit_profile_id', 'death_benefit_profile_id' );
    }
}
