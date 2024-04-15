import {useInventoryStore} from '@/stores/inventory';

const inventoryUtils = {
    get_inventory_data( key ) {
        const inventory = useInventoryStore();

        let response = null;

        if ( inventory.settings.hasOwnProperty( key ) ) {
            switch ( key ) {
                case 'inventory' :
                    response = inventory.settings[ key ].length;
                    break;

                default :
                    response = inventory.settings[ key ];
                    break;
            }
        }

        if ( !response ) {
            switch ( key ) {
                case 'title' :
                    response = 'All';
                    break;

                case 'inventory' :
                    response = 'Showing all';
                    break;
            }
        }
        return ( ( response ) ? response : 'n/a' );
    }
};

export default inventoryUtils;