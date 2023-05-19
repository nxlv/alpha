import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useCommonStore = defineStore( 'common', () => {
    const state = ref( {
        modal: null,
        view: null
    } );

    function commit( dataset, value ) {
        console.log( 'committing', dataset );
        console.log( 'values', value );

        state.value[ dataset ] = value;
    }

    return { state, commit }
} )
