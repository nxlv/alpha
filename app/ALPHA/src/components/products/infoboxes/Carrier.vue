<template>
    <div class="infobox carrier" v-if="profile">
        <h3>Carrier Information</h3>

        <div class="carrier__details">
            <h3 class="carrier__title" v-bind:data-carrier-slug="this.$globalUtils.sanitize_title( profile.carrier.name )">
                {{ profile.carrier.name }}
            </h3>

            <div class="carrier__contact">
                <div class="carrier__contact-item" data-type="address">
                    <address>123 Main Street
                    Orlando, FL  32801</address>
                </div>
                <div class="carrier__contact-item" data-type="phone">
                    <a href="tel:407-555-1212">+1 (407) 555-1212</a>
                </div>
                <div class="carrier__contact-item" data-type="website">
                    <a href="https://google.com/">https://google.com/</a>
                </div>
                <div class="carrier__contact-item" data-type="founded">
                    Founded in 1901
                </div>
            </div>
        </div>

        <br />

        <h3>Carrier Ratings</h3>

        <div class="carrier__details">
            <div class="carrier__ratings">
                <ul class="carrier__ratings-items">
                    <li class="carrier__ratings-items-item" v-for="( rating, rating_index ) in profile.ratings" v-bind:key="rating_index" v-bind:data-item-label="this.$globalUtils.format( 'rating_company', rating.company )">
                        <span class="carrier__ratings-items-item-rating">{{ rating.rating }}</span>
                        <span class="carrier__ratings-items-item-date">{{ rating.effective_date }}</span>
                    </li>
                </ul>
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
        console.log( 'carrier', this.profile );
    },
    data() {
        return {
        };
    }
};
</script>
<script setup>
</script>