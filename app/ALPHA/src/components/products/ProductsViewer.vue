<template>
    <section class="product-viewer" v-if="sets">
        <aside class="product-viewer__carriers">
            <menu class="product-viewer__carriers-list">
                <li v-for="( carrier, carrier_index ) in sets.sets.carriers" v-bind:data-carrier-id="carrier.id" v-bind:key="carrier_index" v-bind:data-carrier-slug="this.$globalUtils.sanitize_title( carrier.name )" v-on:click="handler( 'carrier', carrier.id )">
                    <strong>{{ carrier.name }}</strong>
                    <span data-type="product-count">{{ carrier.products.length }} product(s)</span>
                </li>
            </menu>
        </aside>
        <div class="product-viewer__products" v-if="selection.carrier_id">
            <p>Product List</p>

            <div class="product-viewer__cards">
                <template v-for="( product, product_index ) in sets.sets.products" v-bind:key="product_index">
                    <div class="product-viewer__card" v-if="product.basic.carrier_id === selection.carrier_id">
                        <h3>{{ product.basic.name }}<span>{{ product.product_id }}</span></h3>
                        <h4>{{ product.basic.carrier.name }}</h4>
                        <p>PPID: {{ product.product_profile_id }}</p>
                    </div>
                </template>
            </div>
        </div>
    </section>
</template>
<script>
    import { RouterLink } from 'vue-router';
    import { useSetsStore } from '@/stores/sets';

    export default {
        components: {
            RouterLink,
        },
        setup() {
            const sets = useSetsStore();

            return { sets };
        },
        methods: {
            handler( type, id ) {
                switch ( type ) {
                    case 'carrier' :
                        this.selection.carrier_id = id;
                        break;
                }
            }
        },
        data() {
            return {
                selection: {
                    carrier_id: null
                }
            };
        }
    };
</script>