<template>
    <div class="benefit" v-if="profile && profile.ratings">
        <input type="checkbox" name="benefit-carrier-toggle" id="benefit-carrier-toggle" value="1" class="benefit__toggle hidden" v-bind:checked="expanded">

        <h3 class="benefit__header">
            <label for="benefit-carrier-toggle">
                Carrier Information

                <span class="benefit__controls">
                    <i class="fal fa-chevron-double-up" aria-hidden="true" data-type="collapse"></i>
                    <i class="fal fa-chevron-double-down" aria-hidden="true" data-type="expand"></i>
                </span>
            </label>
        </h3>

        <div class="benefit__details">
            <fieldset class="benefit__details-group" data-group-type="carrier">
                <legend>Product &amp; Profile</legend>

                <ul class="benefit__details-group-items">
                    <li class="benefit__details-group-item" data-item-label="Carrier">
                        {{ profile.carrier.name }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Product">
                        {{ profile.name }}
                    </li>
                </ul>
            </fieldset>

            <fieldset class="benefit__details-group" data-group-type="ratings">
                <legend>Ratings</legend>

                <ul class="benefit__details-group-items">
                    <li class="benefit__details-group-item" v-for="( rating, rating_index ) in profile.ratings" v-bind:key="rating_index" v-bind:data-item-label="this.$globalUtils.format( 'rating_company', rating.company )">
                        <span class="benefit__details-group-item-rating">{{ rating.rating }}</span>
                        <span class="benefit__details-group-item-date">{{ rating.effective_date }}</span>
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
        console.log( 'carrier', this.profile );
    },
    data() {
        return {
        };
    }
};
</script>