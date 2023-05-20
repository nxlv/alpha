import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useClientStore = defineStore( 'client', () => {
    const settings = ref( {
        id: '',

        investment: 100000.00,
        method: 'S',

        owner_name_first: 'Joe',
        owner_name_last: 'Smith',
        owner_birthdate: '1958-01-01',
        owner_state: 'FL',

        joint_name_first: null,
        joint_name_last: null,
        joint_birthdate: null,
        joint_state: null
    } );

    function commit( dataset, value ) {
        console.log( 'committing', dataset );
        console.log( 'values', value );

        settings.value[ dataset ] = value;
    }

    return { settings, commit }
} )
