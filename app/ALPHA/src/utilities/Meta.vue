<script>
    export default {
        get_value_from_meta_key( meta, key ) {
            let value = '';

            if ( ( meta ) && ( meta.length ) ) {
                for ( let counter = 0; counter < meta.length; counter++ ) {
                    if ( meta[ counter ].key === key ) {
                        value = meta[ counter ].value;
                        break;
                    }
                }
            }

            return value;
        }
    }
</script>