import {useInventoryStore} from '@/stores/inventory';

const inventoryUtils = {
    get_inventory_data( key ) {
        const inventory = useInventoryStore();

        let response = null;

        if ( inventory.settings.hasOwnProperty( key ) ) {
            response = inventory.settings[ key ];
        }

        return ( ( response ) ? response : 'n/a' );
    }
};

export default inventoryUtils;