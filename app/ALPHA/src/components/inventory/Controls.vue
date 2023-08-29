<script>
    import { RouterLink } from 'vue-router';
    import { useCommonStore } from '@/stores/common';

    export default {
        components: {
            RouterLink
        },
        methods: {
            show_manager( view ) {
                const common = useCommonStore();

                console.log( 'view', view );

                common.commit( 'view', view );
                common.commit( 'modal', 'inventory_manager' );
            }
        },

        data() {
            return {
            };
        }
    };
</script>
<template>
    <aside class="alpha__prologue-secondary-inventory">
        <h3>Inventory</h3>
        <menu class="stats">
            <li data-type="title">{{ this.$inventoryUtils.get_inventory_data( 'title' ) }}</li>
            <li data-type="counter">{{ this.$inventoryUtils.get_inventory_data( 'inventory' ).length }} products</li>
        </menu>
        <menu class="controls">
            <li data-type="edit"><a href="javascript:;" title="Edit Preset" v-on:click="show_manager( null )">Edit Preset</a></li>
            <li data-type="add"><a href="javascript:;" title="Add Preset" v-on:click="show_manager( 'new' )">Add Preset</a></li>
        </menu>
    </aside>
</template>
