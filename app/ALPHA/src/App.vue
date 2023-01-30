<script setup>
    import axios from 'axios';
    import { useSetsStore } from '@/stores/sets';
    import { RouterView } from 'vue-router';

    import NavigationPrimary from '@/components/navigation/Primary.vue';
    import NavigationSecondary from '@/components/navigation/Secondary.vue';
    import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
</script>

<template>
    <main id="alpha" class="alpha">
        <input type="checkbox" id="alpha__prologue-collapse" class="alpha__prologue-collapse" value="1">
        <label class="alpha__prologue-collapse-control" for="alpha__prologue-collapse">Collapse</label>

        <header class="alpha__prologue">
            <NavigationPrimary />
            <NavigationSecondary />
        </header>
        <article>
            <RouterView />
        </article>
    </main>
</template>

<script>
    export default {
        async created() {
            let request;

            const sets = useSetsStore();
            const preload = [
                { 'identifier': 'carriers', 'endpoint': '/api/carriers/all' },
                { 'identifier': 'products', 'endpoint': '/api/products/all' },
                { 'identifier': 'indexes', 'endpoint': '/api/indexes/all' }
            ];

            for ( let counter = 0; counter < preload.length; counter++ ) {
                console.log( '[    START ]', 'Loading of dataset', preload[ counter ].identifier, 'starting...' );

                request = await axios.get( '' + preload[ counter ].endpoint );

                if ( ( request ) && ( request.data ) ) {
                    console.log( '[      END ]', 'Loading of dataset', preload[ counter ].identifier, 'complete!' );
                    sets.commit( preload[ counter ].identifier, request.data );

                    switch ( preload[ counter ].identifier ) {
                        case 'products' :
                            // TODO: post processing
                            break;
                    }
                }
            }
        }
    }
</script>