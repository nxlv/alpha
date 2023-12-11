<template>
    <div class="infobox withdrawals" v-if="profile">
        <h3>State Availability</h3>

        <div class="availability">
            &mdash;
        </div>

        <h3>Premiums</h3>

        <div class="infotable">
            <div class="infotable__row">
                <label class="infotable__label">Min Investment</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Max Investment</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Flexible Premiums</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Types of Funds</label>
                <span class="infotable__value">X</span>
            </div>
        </div>

        <h3>Age Restrictions</h3>

        <div class="infotable">
            <div class="infotable__row">
                <label class="infotable__label">Owner</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Annuitant</label>
                <span class="infotable__value">X</span>
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