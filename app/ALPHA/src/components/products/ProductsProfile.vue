<template>
    <div class="cards cards__product" v-if="profiles && profiles.length">
    </div>
</template>
<script>
    export default {
        props: {
            product: null
        },
        created() {
            console.log( this.product );

            //this.profiles = Utils_Products.find_product_by_profile_id( this.product );
            //console.log( 'profile', JSON.parse( JSON.stringify( this.profiles ) ) );
        },
        data() {
            return {
                profiles: null
            };
        }
    };
</script>