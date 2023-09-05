import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useComparisonStore = defineStore( 'inventory', () => {
    const settings = ref( {
        cart: [],
    } );

    function commit( dataset, value ) {
        settings.value[ dataset ] = value;
    }

    return { settings, commit }
} )
