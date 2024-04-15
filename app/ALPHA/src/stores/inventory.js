import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useInventoryStore = defineStore( 'inventory', () => {
    const settings = ref( {
        id: '',
        title: '',
        inventory: [],

        presets: []
    } );

    function commit( dataset, value ) {
        settings.value[ dataset ] = value;
    }

    return { settings, commit }
} )
