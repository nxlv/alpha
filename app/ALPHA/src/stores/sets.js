import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useSetsStore = defineStore( 'sets', () => {
    const sets = ref( {} );

    function commit( dataset, value ) {
        console.log( 'committing', dataset );
        console.log( 'values', value );

        sets.value[ dataset ] = value;
    }

    return { sets, commit }
} )
