<?php

namespace App\Models;

use App\Models\Notice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeBenefit extends Model
{
    use HasFactory;

    public function meta() {
        return $this->hasMany( IncomeBenefitsMetum::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }

    // rider fees
    public function rider_fee_current() {
        return $this->hasMany( IncomeBenefitsCurrentRiderFee::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function rider_fee_minimum() {
        return $this->hasMany( IncomeBenefitsMinRiderFee::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function rider_fee_maximum() {
        return $this->hasMany( IncomeBenefitsMaxRiderFee::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }

    // rider fees
    public function premium_initial() {
        return $this->hasMany( IncomeBenefitsInitialPremium::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function premium_max() {
        return $this->hasMany( IncomeBenefitsMaxPremium::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function premium_bonus() {
        return $this->hasMany( IncomeBenefitsPremiumBonus::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function premium_multiplier() {
        return $this->hasMany( IncomeBenefitsPremiumMultiplier::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }

    // interest
    public function interest_crediting() {
        return $this->hasMany( IncomeBenefitsInterestCrediting::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function interest_bonus_crediting() {
        return $this->hasMany( IncomeBenefitsInterestBonusCrediting::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function interest_multiplier_crediting() {
        return $this->hasMany( IncomeBenefitsInterestMultiplierCrediting::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }

    // withdrawals
    public function withdrawal_tiers() {
        return $this->hasMany( IncomeBenefitsWithdrawalTablesTier::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function withdrawal_tiers_ruin() {
        return $this->hasMany( IncomeBenefitsWithdrawalRuinTablesTier::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function withdrawal_deferral_ages() {
        return $this->hasMany( IncomeBenefitsWithdrawalTablesDeferralAgeRange::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function withdrawal_deferral_ages_ruin() {
        return $this->hasMany( IncomeBenefitsWithdrawalRuinTablesDeferralAgeRange::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }

    // income
    public function income_start_age() {
        return $this->hasMany( IncomeBenefitsIncomeStartAge::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }

    // other
    public function persistency_credit() {
        return $this->hasMany( IncomeBenefitsPersistencyCredit::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function roll_up() {
        return $this->hasMany( IncomeBenefitsRollUp::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }
    public function step_up() {
        return $this->hasMany( IncomeBenefitsStepUp::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }

    // rules

    public function states() {
        return $this->hasMany( IncomeBenefitsState::class, 'income_benefit_profile_id', 'income_benefit_profile_id' );
    }

    // notices
    public function notice_current_rider_fees() {
        return $this->hasOne( Notice::class, 'text_id', 'interest_bonus_crediting_text_id' );
    }
    public function notice_rider_fees() {
        return $this->hasOne( Notice::class, 'text_id', 'rider_fees_notice_text_id' );
    }

    public function notice_premium_multiplier() {
        return $this->hasOne( Notice::class, 'text_id', 'interest_crediting_text_id' );
    }
    public function notice_premium_bonus() {
        return $this->hasOne( Notice::class, 'text_id', 'premium_bonus_text_id' );
    }
    public function notice_premium() {
        return $this->hasOne( Notice::class, 'text_id', 'premium_notice_text_id' );
    }

    public function notice_interest_crediting() {
        return $this->hasOne( Notice::class, 'text_id', 'interest_bonus_crediting_text_id' );
    }
    public function notice_interest_multiplier() {
        return $this->hasOne( Notice::class, 'text_id', 'interest_multiplier_crediting_text_id' );
    }

    public function notice_issue_age() {
        return $this->hasOne( Notice::class, 'text_id', 'issue_age_notice_text_id' );
    }

    public function notice_step_ups() {
        return $this->hasOne( Notice::class, 'text_id', 'step_ups_notice_text_id' );
    }

    public function notice_persistency_credit() {
        return $this->hasOne( Notice::class, 'text_id', 'persistency_credit_text_id' );
    }

    public function notice_income_start() {
        return $this->hasOne( Notice::class, 'text_id', 'income_start_age_text_id' );
    }

    public function notice_withdrawal_rates() {
        return $this->hasOne( Notice::class, 'text_id', 'withdrawal_rates_text_id' );
    }
    public function notice_withdrawal_ruin_rates() {
        return $this->hasOne( Notice::class, 'text_id', 'withdrawal_ruin_rates_text_id' );

    }
}
