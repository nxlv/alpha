<script>
    import { useSetsStore } from '@/stores/sets';

    import Utils_Meta from '@/utilities/Meta.vue';

    export default {
        find_products_by_carrier_id( carrier_id ) {
            const sets = useSetsStore();

            let response = [];

            if ( ( sets ) && ( sets.sets ) && ( sets.sets.carriers ) && ( sets.sets.carriers.length ) ) {
                let results = [];

                for ( let counter = 0; counter < sets.sets.carriers.length; counter++ ) {
                    if ( sets.sets.carriers[ counter ].id === carrier_id ) {
                        results = JSON.parse( JSON.stringify( sets.sets.carriers[ counter ].products ) );
                        break;
                    }
                }

                if ( results.length ) {
                    for ( let counter = 0; counter < results.length; counter++ ) {
                        response.push( Utils_Meta.get_value_from_meta_key( results[ counter ].meta, 'UNIQUE' ) );
                    }
                }
            }

            return response;
        },

        find_product_by_profile_id( profile_id ) {
            const sets = useSetsStore();

            let results = [];

            return JSON.parse( JSON.stringify( results ) );
        }
    }
</script>