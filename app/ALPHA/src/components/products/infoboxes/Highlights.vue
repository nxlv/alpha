<template>
    <div class="infobox highlights" v-if="profile">
        <h3>Product Highlights</h3>

        <div class="infotable">
            <div class="infotable__row">
                <label class="infotable__label">Type</label>
                <span class="infotable__value">Fixed Indexed Annuity</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Income Benefit Type</label>
                <span class="infotable__value"><span class="badge">{{ profile.income_benefit.income_benefit_type }}</span> {{ profile.income_benefit.name }}</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Roll-up Rate</label>
                <span class="infotable__value">
                    <span class="infotable__value-badged" data-type="rate-percent" data-type-title="Roll-up Rate">
                        <template v-for="( roll_up, roll_up_index ) in profile.income_benefit.roll_up" v-bind:key="roll_up_index" v-if="profile.income_benefit.roll_up.length">
                            <span class="badge">Tier {{ roll_up.tier_no }}</span> {{ roll_up.rate }}%
                        </template>
                        <template v-if="!profile.income_benefit.roll_up.length">n/a</template>
                    </span>
                </span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Return of Premium</label>
                <span class="infotable__value">
                    <span class="infotable__value-badged" data-type="rate-percent" data-type-title="Return of Premium">
                        <template v-for="( rop, rop_index ) in profile.profile.rop" v-bind:key="rop_index" v-if="profile.profile.rop.length">
                            {{ this.$globalUtils.get_dataset( 'yesno' )[ rop.code ] }}
                        </template>
                        <template v-if="!profile.profile.rop.length">n/a</template>
                    </span>
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
            console.log( 'highlights', this.profile );
        },
        data() {
            return {
            };
        }
    };
</script>