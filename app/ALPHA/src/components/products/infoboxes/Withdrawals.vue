<template>
    <div class="infobox withdrawals" v-if="profile">
        <h3>CDSC Schedule</h3>

        <div class="infotable infotable--horizontal">
            <div class="infotable__row" v-for="( row, row_index ) in profile.profile.cdsc_schedule" v-bind:key="row_index">
                <label class="infotable__label">{{ row.years }} years</label>
                <span class="infotable__value">{{ row.rate }}%</span>
            </div>
        </div>

        <h3>Withdrawals</h3>

        <div class="infotable infotable">
            <div class="infotable__row">
                <label class="infotable__label">Free Withdrawals?</label>
                <span class="infotable__value">
                    <template v-for="( row, row_index ) in profile.profile.withdrawals_free_rate" v-bind:key="row_index" v-if="profile.profile.withdrawals_free_rate.length">
                        <span class="badge">Tier {{ row.tier_no }}</span> {{ row.rate }}%
                    </template>
                    <template v-if="!profile.profile.withdrawals_free_rate.length">No</template>
                </span>
            </div>
        </div>

        <h3>Contract Features</h3>

        <div class="infotable infotable">
            <div class="infotable__row">
                <label class="infotable__label">Contract Features</label>
                <span class="infotable__value">
                    <span class="badge" v-if="profile.profile.mva.length && profile.profile.mva[ 0 ].code === 'Y'">Market Value Adjustment</span>
                    <span class="badge" v-if="profile.profile.rop.length && profile.profile.rop[ 0 ].code === 'Y'">Return of Premium</span>
                </span>
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
        console.log( 'withdrawals', this.profile );
    },
    data() {
        return {
        };
    }
};
</script>