import {useClientStore} from '@/stores/client';

const clientUtils = {
    get_client_data( key ) {
        const client = useClientStore();

        let response = null;

        if ( client.settings.hasOwnProperty( key ) ) {
            response = client.settings[ key ];
        }

        return ( ( response ) ? response : 'n/a' );
    }
};

export default clientUtils;