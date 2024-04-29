<script setup>
    import axios from 'axios';
    import { useSetsStore } from '@/stores/sets';
    import { useCommonStore } from "@/stores/common";
    import { RouterView } from 'vue-router';

    import NavigationPrimary from '@/components/navigation/Primary.vue';
    import NavigationSecondary from '@/components/navigation/Secondary.vue';
    import Initialization from '@/components/ui/Initialization.vue';
    import Modals from '@/components/ui/Modals.vue';
</script>

<template>
    <main id="alpha" class="alpha">
        <Initialization />

        <input type="checkbox" id="alpha__prologue-collapse" class="alpha__prologue-collapse" value="1">
        <label class="alpha__prologue-collapse-control" for="alpha__prologue-collapse">Collapse</label>

        <header class="alpha__prologue">
            <NavigationPrimary />
            <NavigationSecondary />
        </header>

        <article class="alpha__content">
            <RouterView />
            <Modals />
        </article>

        <footer class="alpha__epilogue">
        </footer>
    </main>
</template>

<script>
    export default {
        async created() {
            let request;

            const sets = useSetsStore();
            const common = useCommonStore();

            const preload = [
                { 'identifier': 'carriers', 'endpoint': '/api/carriers/all', 'text': 'Loading carrier information' },
                { 'identifier': 'indexes', 'endpoint': '/api/indexes/all', 'text': 'Loading financial index data' },
                { 'identifier': 'notices', 'endpoint': '/api/notices/all', 'text': 'Loading product notices' }
            ];

            for ( let counter = 0; counter < preload.length; counter++ ) {
                console.log( '[    START ]', 'Loading of dataset', preload[ counter ].identifier, 'starting...' );

                this.$emitter.emit( 'alpha__init-status', { stage: { current: ( counter + 1 ), total: preload.length, data: preload[ counter ] } } );

                request = await axios.get( ( ( import.meta.env.PROD ) ? ( '//' + window.location.host ) : import.meta.env.VITE_API_BASE_URL ) + preload[ counter ].endpoint );

                if ( ( request ) && ( request.data ) ) {
                    console.log( '[      END ]', 'Loading of dataset', preload[ counter ].identifier, 'complete!' );

                    sets.commit( preload[ counter ].identifier, request.data );
                }
            }

            this.$emitter.emit( 'alpha__init-status', { stage: { current: preload.length, total: preload.length, data: { identifier: 'complete', text: 'Done!' } } } );
        }
    }
</script>
