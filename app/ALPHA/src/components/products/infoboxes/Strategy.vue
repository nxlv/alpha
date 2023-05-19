<template>
    <div class="benefit" v-if="profile && profile.strategy">
        <input type="checkbox" name="benefit-strategy-toggle" id="benefit-strategy-toggle" value="1" class="benefit__toggle hidden" v-bind:checked="expanded">

        <h3 class="benefit__header">
            <label for="benefit-strategy-toggle">
                Strategy Profile

                <span class="benefit__controls">
                    <i class="fal fa-chevron-double-up" aria-hidden="true" data-type="collapse"></i>
                    <i class="fal fa-chevron-double-down" aria-hidden="true" data-type="expand"></i>
                </span>
            </label>
        </h3>

        <div class="benefit__details">
            <fieldset class="benefit__details-group" data-group-type="extended">
                <legend>Profile Details</legend>

                <ul class="benefit__details-group-items">
                    <li class="benefit__details-group-item" data-item-label="Index">
                        {{ this.$globalUtils.format( 'index', profile.strategy.index_id ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Type">
                        {{ this.$globalUtils.format( 'strategy_type', profile.strategy.strategy_type ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Configuration">
                        {{ this.$globalUtils.format( 'strategy_configuration', profile.strategy.strategy_configuration ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Calculation">
                        {{ this.$globalUtils.format( 'frequency', profile.strategy.calculation_frequency ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Crediting">
                        {{ this.$globalUtils.format( 'frequency', profile.strategy.crediting_frequency ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Guarantee Status">
                        {{ this.$globalUtils.format( 'yesno', profile.strategy.guarantee_status ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Guarantee Period">
                        {{ profile.strategy.guarantee_period_years }} <small>years</small> {{ profile.strategy.guarantee_period_months }} <small>months</small>
                    </li>
                </ul>
            </fieldset>

            <fieldset class="benefit__details-group" data-group-type="rates">
                <legend>Current Rates</legend>

                <ul class="benefit__details-group-items">
                    <li class="benefit__details-group-item" data-item-label="Fixed">
                        {{ this.$globalUtils.format( 'rate', profile.current_fixed_rate ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Increase">
                        {{ this.$globalUtils.format( 'rate', profile.current_increase_fixed_rate ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Declared">
                        {{ this.$globalUtils.format( 'rate', profile.current_declared_rate ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Cap">
                        {{ this.$globalUtils.format( 'rate', profile.current_cap_rate ) }} {{ this.$globalUtils.format( 'cap_rate_cd', profile.current_cap_rate_cd ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Participation">
                        {{ this.$globalUtils.format( 'rate', profile.current_participation_rate ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Spread">
                        {{ this.$globalUtils.format( 'rate', profile.current_spread_rate ) }}
                    </li>
                </ul>

                <ul class="benefit__details-group-items" data-group-label="Protection">
                    <li class="benefit__details-group-item" data-item-label="Participation">
                        {{ this.$globalUtils.format( 'rate', profile.current_protection_downside_participation_rate ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Floor">
                        {{ this.$globalUtils.format( 'rate', profile.current_protection_floor_rate ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Buffer">
                        {{ this.$globalUtils.format( 'rate', profile.current_protection_buffer_rate ) }}
                    </li>
                </ul>
            </fieldset>

            <fieldset class="benefit__details-group" data-group-type="rates hidden">
                <legend>Announced Rates</legend>

                <ul class="benefit__details-group-items">
                    <li class="benefit__details-group-item" data-item-label="Fixed">
                        {{ this.$globalUtils.format( 'rate', profile.announced_fixed_rate ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Declared">
                        {{ this.$globalUtils.format( 'rate', profile.announced_declared_rate ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Cap">
                        {{ this.$globalUtils.format( 'rate', profile.announced_cap_rate ) }} {{ this.$globalUtils.format( 'cap_rate_cd', profile.announced_cap_rate_cd ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Participation">
                        {{ this.$globalUtils.format( 'rate', profile.announced_participation_rate ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Spread">
                        {{ this.$globalUtils.format( 'rate', profile.announced_spread_rate ) }}
                    </li>
                </ul>

                <ul class="benefit__details-group-items" data-group-label="Protection (Announced)">
                    <li class="benefit__details-group-item" data-item-label="Participation">
                        {{ this.$globalUtils.format( 'rate', profile.announced_protection_downside_participation_rate ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Floor">
                        {{ this.$globalUtils.format( 'rate', profile.announced_protection_floor_rate ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Buffer">
                        {{ this.$globalUtils.format( 'rate', profile.announced_protection_buffer_rate ) }}
                    </li>
                </ul>
            </fieldset>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        profile: null,
        expanded: false
    },
    created() {
        console.log( 'strategy', this.profile );
    },
    data() {
        return {
        };
    }
};
</script>