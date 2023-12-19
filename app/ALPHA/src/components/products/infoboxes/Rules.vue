<template>
    <div class="infobox withdrawals" v-if="profile">
        <h3>State Availability</h3>

        <div class="availability">
            <span class="availability__region" v-for="( row, row_index ) in profile.states" v-bind:key="row_index">{{ row.state_cd }}</span>
            &mdash;
        </div>

        <h3>Premiums</h3>

        <div class="infotable">
            <div class="infotable__row">
                <label class="infotable__label">Min Investment</label>
                <span class="infotable__value">{{ this.$financeUtils.format_currency( profile.premium_min, 'USD' ) }}</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Max Investment</label>
                <span class="infotable__value">{{ this.$financeUtils.format_currency( profile.premium_max, 'USD' ) }}</span>
            </div>
            <!--
            <div class="infotable__row">
                <label class="infotable__label">Flexible Premiums</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Types of Funds</label>
                <span class="infotable__value">X</span>
            </div>
            -->
        </div>

        <h3>Age Restrictions</h3>

        <div class="infotable">
            <div class="infotable__row">
                <label class="infotable__label">Owner</label>
                <span class="infotable__value">{{ profile.age_range_min_years }}&nbsp;<small>years</small>&nbsp;{{ profile.age_range_min_months }}&nbsp;<small>months</small> &nbsp;&mdash;&nbsp; {{ profile.age_range_max_years }}&nbsp;<small>years</small>&nbsp;{{ profile.age_range_max_months }}&nbsp;<small>months</small></span>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        profile: null
    },
    created() {
        console.log( 'rules', this.profile );
    },
    data() {
        return {
        };
    }
};
</script>