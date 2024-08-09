<script>
    import { RouterLink } from 'vue-router';

    import { useInventoryStore } from '@/stores/inventory';
    import { useCommonStore } from '@/stores/common';

    import { v4 as uuidv4 } from 'uuid';

    export default {
        components: {
            RouterLink,
        },
        methods: {
            load() {

            },

            save() {
                const inventory = useInventoryStore();

                if ( !this.inputs.id ) {
                    this.inputs.id = this.generate_client_id();
                }

                let key = null;

                for ( key in this.inputs ) {
                    inventory.commit( key, this.inputs[ key ] );
                }

                this.save_to_storage();

                this.$globalUtils.modal_close();
            },

            save_to_storage() {
                let found = false;
                let storage = this.storage_control( 'get', false );

                if ( !Array.isArray( storage ) ) {
                    storage = [];

                    this.storage_control( 'set', storage );
                }

                for ( let counter = 0; counter < storage.length; counter++ ) {
                    if ( storage[ counter ].id === this.inputs.id ) {
                        storage[ counter ] = JSON.parse( JSON.stringify( this.inputs ) );

                        found = true;
                        break;
                    }
                }

                if ( !found ) {
                    storage.push( JSON.parse( JSON.stringify( this.inputs ) ) );
                }

                this.storage_control( 'set', storage );

                this.presets = JSON.parse( JSON.stringify( storage ) );
            },

            load_from_storage( id, should_close ) {
                let storage = this.storage_control( 'get', false );

                if ( storage ) {
                    for ( let counter = 0; counter < storage.length; counter++ ) {
                        if ( storage[ counter ].id === id ) {
                            this.inputs = storage[ counter ];

                            if ( should_close ) {
                                this.save();
                                this.$globalUtils.modal_close();
                            }
                            break;
                        }
                    }
                }
            },

            remove_from_storage( id ) {
                let response = [];

                if ( this.presets ) {
                    for ( let counter = 0; counter < this.presets.length; counter++ ) {
                        if ( this.presets[ counter ].id !== id ) {
                            response.push( this.presets[ counter ] );
                        }
                    }
                }

                this.storage_control( 'set', response );

                this.presets = this.storage_control( 'get', false );
            },

            storage_control( method, data ) {
                switch ( method ) {
                    case 'get' :
                        return JSON.parse( localStorage.getItem( this.storage_key ) );
                        break;

                    case 'set' :
                        localStorage.setItem( this.storage_key, JSON.stringify( data ) );
                        break;
                }

                return true;
            },

            clear_fields() {
                let key = null;

                for ( key in this.template ) {
                    this.inputs[ key ] = this.template[ key ];
                }
            },

            generate_client_id() {
                return uuidv4();
            }
        },
        created() {
            const inventory = useInventoryStore();
            const common = useCommonStore();

            let key = null;

            // save old template
            this.template = JSON.parse( JSON.stringify( this.inputs ) );

            for ( key in inventory.settings ) {
                this.inputs[ key ] = inventory.settings[ key ];
            }

            this.presets = this.storage_control( 'get', false );

            console.log( 'view', common.state.view );

            if ( common.state.view ) {
                switch ( common.state.view ) {
                    case 'new' :
                        this.clear_fields();
                        break;
                }
            }
        },
        data() {
            return {
                storage_key: 'alpha__inventory',
                template: null,
                presets: [],
                inputs: {
                    id: '',
                    title: '',
                    inventory: [
                        {
                            id: '__default',
                            title: 'All - Showing filtered products',
                            inventory: ['A000PH','A000PT','A00155','A001BH','A000RE','A000PN','A000RH','A000PP','A000PR','A000RO','A000BH','A000CP','A000CT','A000BU','A000CH','A000DC','A000CJ','A000HL','A000YE','A00104','A0010P','A0010R','A00106','A000ZL','A000HR','A000HY','A000ZP','A0010E','A0011C','A0010G','A000ZX','A000I1','A000ZJ','A0010L','A00112','A00153','A0010T','A0010B','A0010D','A0010Y','A00163','A00165','A00110','A0010J','A001BQ','A000D2','A000D6','A000D8','A000D4','A000LZ','A000MF','A000MH','A000DA','A0016A']
                        }
                    ]
                }
            };
        }
    };
</script>
<template>
    <dialog class="alpha__modal">
        <div class="alpha__modal-bounds">
            <header class="alpha__modal-header">
                <h3>Add/Edit Inventory Presets <span class="alpha__modal-close" v-on:click="this.$globalUtils.modal_close()"><i class="fal fa-close" aria-hidden="true"></i></span></h3>
            </header>
            <section class="alpha__modal-body alpha__modal-body--max-75">
                <fieldset>
                    <legend>Preset Details</legend>

                    <div class="form">
                        <div class="form__row">
                            <div class="form__column form__column--full">
                                <label for="preset__name">Preset Name</label>
                                <input type="text" id="preset__name" name="preset__name" placeholder="i.e., Top 10 annuity products in 2023" v-model="inputs.title">
                            </div>
                        </div>
                    </div>

                    <hr />

                    <fieldset v-for="( carrier, carrier_index ) in this.$globalUtils.get_set_as_kvp( 'carriers' )" v-bind:key="carrier_index" v-bind:data-carrier-key="this.$globalUtils.sanitize_title( carrier.label )">
                        <legend><label v-bind:for="'carrier_' + carrier.value">{{ carrier.label }} <span>{{ carrier.products.length }} products</span></label></legend>
                        <input type="checkbox" v-bind:id="'carrier_' + carrier.value" v-bind:value="carrier.value">

                        <div class="form form__collapsable">
                            <div class="form__row form__row--wrapped">
                                <div class="form__column form__column--checkbox" v-for="( product, product_index ) in carrier.products" v-bind:key="product_index">
                                    <input type="checkbox" v-bind:id="product.product_id" v-bind:value="product.product_id" v-model="inputs.inventory">
                                    <label v-bind:for="product.product_id">{{ product.name }}</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </fieldset>
            </section>
            <footer class="alpha__modal-footer">
                <button type="button" v-on:click="save">Save and Close</button>
                <button type="button" class="btn-small btn-grayscale" v-on:click="this.$globalUtils.modal_close()">Close</button>
                <button type="button" class="btn-small btn-warn" v-on:click="this.clear_fields()">Clear</button>
            </footer>
        </div>

        <div class="alpha__modal-sidebar" v-if="presets">
            <div class="alpha__modal-sidebar-bounds">
                <h3>Saved Inventory Presets</h3>
                <p>Click on an inventory preset to recall it and use it for quoting and estimation throughout the system.</p>

                <menu class="alpha__presets">
                    <template v-for="( preset, preset_index ) in presets" v-bind:key="preset_index">
                        <div class="alpha__presets-record">
                            <div class="alpha__presets-record-details">
                                <h3>{{ preset.title }}</h3>
                                <span>{{ preset.inventory.length }} products</span>
                            </div>
                            <div class="alpha__clients-record-controls">
                                <button type="button" v-on:click="load_from_storage( preset.id, true )" data-button-label="Use"><i class="fal fa-user-check" aria-hidden="true"></i></button>
                                <button type="button" v-on:click="load_from_storage( preset.id, false )" data-button-label="Edit"><i class="fal fa-user-pen" aria-hidden="true"></i></button>
                                <button type="button" v-on:click="remove_from_storage( preset.id )" data-button-label="Delete"><i class="fal fa-user-xmark" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </template>
                </menu>
            </div>
        </div>
    </dialog>
</template>
