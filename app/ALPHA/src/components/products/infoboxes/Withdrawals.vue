<template>
    <div class="infobox withdrawals" v-if="profile">
        <h3>Withdrawals</h3>

        <div class="infotable">
            <div class="infotable__row">
                <label class="infotable__label">Surrender Charge Schedule</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Free Withdrawals?</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Contract Features</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Guaranteed Minimum CSV</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Waivers</label>
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
        console.log( 'withdrawals', this.profile );
    },
    data() {
        return {
        };
    }
};
</script>