<script>
    import { RouterLink } from 'vue-router';
    import { useCommonStore } from '@/stores/common';

    import ClientManager from '@/components/clients/Manager.vue';
    import InventoryManager from '@/components/inventory/Manager.vue';

    export default {
        components: {
            RouterLink,
            ClientManager,
            InventoryManager
        },
        methods: {
            modal_visible() {
                let store = useCommonStore();

                return store.state.modal;
            },

            is_modal_visible( key ) {
                let store = useCommonStore();

                if ( key ) {
                    if ( store.state.modal ) {
                        return ( ( store.state.modal === key ) ? true : false );
                    }
                } else {
                    return ( ( store.state.modal ) ? true : false );
                }

                return false;
            },

            is_any_modal_visible() {
                return this.is_modal_visible();
            },
        },

        data() {
            return {
            };
        }
    };
</script>
<template>
    <section class="modals" v-if="is_any_modal_visible()">
        <ClientManager v-if="is_modal_visible( 'client_manager' )" />
        <InventoryManager v-if="is_modal_visible( 'inventory_manager' )" />
    </section>
</template>