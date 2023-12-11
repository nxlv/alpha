<template>
    <div class="infobox highlights" v-if="profile">
        <h3>Product Highlights</h3>

        <div class="infotable">
            <div class="infotable__row">
                <label class="infotable__label">Type</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Term</label>
                <span class="infotable__value">7 Years</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Surrender Schedule</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Free Withdrawals?</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Flexible Premium?</label>
                <span class="infotable__value">X</span>
            </div>
            <div class="infotable__row">
                <label class="infotable__label">Return of Premium</label>
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
            console.log( 'highlights', this.profile );
        },
        data() {
            return {
            };
        }
    };
</script>