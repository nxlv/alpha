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
                common.commit( 'modal', 'client_manager' );
            }
        },

        data() {
            return {
            };
        }
    };
</script>
<template>
    <aside class="alpha__prologue-secondary-client">
        <h3>
            {{ this.$clientUtils.get_client_data( 'owner_name_first' ) + ' ' + this.$clientUtils.get_client_data( 'owner_name_last' ) }}
            <span class="money">{{ this.$financeUtils.format_currency( this.$globalUtils.ensure_type( this.$clientUtils.get_client_data( 'investment' ), 'float' ), 'USD' ) }}</span>
        </h3>
        <menu class="stats">
            <li data-type="location">{{ this.$clientUtils.get_client_data( 'owner_state' ) }}</li>
            <li data-type="age">Age {{ this.$globalUtils.format( 'age', this.$clientUtils.get_client_data( 'owner_birthdate' ) ) }}</li>
            <li data-type="marital">{{ this.$globalUtils.format( 'single_joint', this.$clientUtils.get_client_data( 'method' ) ) }}</li>
        </menu>
        <menu class="controls">
            <li data-type="change"><a href="javascript:;" title="Change Client" v-on:click="show_manager( null )">Change Client</a></li>
            <li data-type="edit"><a href="javascript:;" title="Edit Client" v-on:click="show_manager( null )">Edit Client</a></li>
            <li data-type="add"><a href="javascript:;" title="Add Client" v-on:click="show_manager( 'new' )">Add Client</a></li>
        </menu>
    </aside>
</template>
