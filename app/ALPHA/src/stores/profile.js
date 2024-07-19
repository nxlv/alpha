import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useProfileStore = defineStore( 'profile', () => {
    const settings = ref( {
        id: '',

        name_first: 'Joe',
        name_last: 'Smith',
        gender: 'M',

        data_access_type: 'local'
    } );

    function commit( dataset, value ) {
        console.log( 'committing', dataset );
        console.log( 'values', value );

        settings.value[ dataset ] = value;
    }

    return { settings, commit }
} )
