<script>
    import { RouterLink } from 'vue-router';

    import { useClientStore } from '@/stores/client';
    import { useCommonStore } from '@/stores/common';

    import { v4 as uuidv4 } from 'uuid';

    import InputCurrency from '@/components/ui/elements/InputCurrency.vue';

    export default {
        components: {
            RouterLink,
            InputCurrency
        },
        methods: {
            load() {

            },

            save() {
                const client = useClientStore();

                if ( !this.inputs.id ) {
                    this.inputs.id = this.generate_client_id();
                }

                let key = null;

                for ( key in this.inputs ) {
                    switch ( key ) {
                        case 'annuity_investment' :
                            console.log( 'investment amount', this.inputs[ key ], this.$globalUtils.ensure_type( this.inputs[ key ], 'float' ) );

                            this.inputs[ key ] = this.$globalUtils.ensure_type( this.inputs[ key ], 'float' );
                            break;

                        case 'owner_age' :
                        case 'joint_age' :
                            let date = this.inputs[ key.replace( '_age', '_birthdate' ) ];

                            if ( date ) {
                                date = this.$moment().diff( this.$moment( date ), 'years' );

                                console.log( 'age', date );

                                if ( ( date ) && ( !isNaN( date ) ) ) {
                                    this.inputs[ key ] = date;
                                }
                            }
                            break;
                    }

                    client.commit( key, this.inputs[ key ] );
                }

                this.save_to_storage();
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

                this.clients = JSON.parse( JSON.stringify( storage ) );
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

                if ( this.clients ) {
                    for ( let counter = 0; counter < this.clients.length; counter++ ) {
                        if ( this.clients[ counter ].id !== id ) {
                            response.push( this.clients[ counter ] );
                        }
                    }
                }

                this.storage_control( 'set', response );

                this.clients = this.storage_control( 'get', false );
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
            const client = useClientStore();
            const common = useCommonStore();

            let key = null;

            // save old template
            this.template = JSON.parse( JSON.stringify( this.inputs ) );

            for ( key in client.settings ) {
                this.inputs[ key ] = client.settings[ key ];
            }

            this.clients = this.storage_control( 'get', false );

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
                storage_key: 'alpha__clients',
                template: null,
                clients: [],
                inputs: {
                    id: '',

                    annuity_investment: 100000.00,
                    annuity_contract: 'S',

                    owner_name_first: 'Joe',
                    owner_name_last: 'Smith',
                    owner_birthdate: '1958-01-01',
                    owner_age: 65,
                    owner_gender: 'M',
                    owner_state: 'FL',

                    joint_name_first: null,
                    joint_name_last: null,
                    joint_birthdate: null,
                    joint_age: null,
                    joint_gender: null,
                    joint_state: null
                }
            };
        }
    };
</script>
<template>
    <dialog class="alpha__modal">
        <div class="alpha__modal-bounds">
            <header class="alpha__modal-header">
                <h3>Add/Edit Client Information <span class="alpha__modal-close" v-on:click="this.$globalUtils.modal_close()"><i class="fal fa-close" aria-hidden="true"></i></span></h3>
            </header>
            <section class="alpha__modal-body">
                <fieldset>
                    <legend>Owner Information</legend>
                    <div class="form">
                        <div class="form__row">
                            <div class="form__column form__column--large">
                                <label>Initial Investment</label>
                                <input type="text" id="owner__investment" name="investment" class="money" placeholder="i.e., $100,000.00" v-model="inputs.annuity_investment">
                            </div>
                        </div>
                        <div class="form__row">
                            <div class="form__column">
                                <label>First Name</label>
                                <input type="text" id="owner__name-first" name="owner_name_first" placeholder="i.e., Benjamin" v-model="inputs.owner_name_first">
                            </div>
                            <div class="form__column">
                                <label>Last Name</label>
                                <input type="text" id="owner__name-last" name="owner_name_last" placeholder="i.e., Franklin" v-model="inputs.owner_name_last">
                            </div>
                            <div class="form__column">
                                <label>Gender</label>
                                <select id="owner__gender" name="owner_gender" v-model="inputs.owner_gender">
                                    <option v-for="( option, option_index ) in this.$globalUtils.get_dataset_as_kvp( 'gender' )" v-bind:key="option.value" v-bind:value="option.value">{{ option.label }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form__row">
                            <div class="form__column">
                                <label>Birthdate <span class="badge" v-if="inputs.owner_birthdate">Age {{ this.$globalUtils.format( 'age', inputs.owner_birthdate ) }}</span></label>
                                <input type="date" id="owner__birthdate" name="owner_birthdate" placeholder="i.e., 08/12/1979" v-model="inputs.owner_birthdate">
                            </div>
                            <div class="form__column">
                                <label>State</label>
                                <select id="owner__state" name="owner_state" v-model="inputs.owner_state">
                                    <option v-for="( state, state_index ) in this.$globalUtils.get_dataset( 'states_usa' )" v-bind:key="state_index" v-bind:value="state_index">{{ this.$globalUtils.format( 'states_usa', state ) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Benefit Type</legend>
                    <div class="form">
                        <div class="form__row">
                            <div class="form__column">
                                <div class="form__buttons">
                                    <div class="form__buttons-column">
                                        <input type="radio" id="owner__method-single" name="method" value="S" checked v-model="inputs.annuity_contract">
                                        <label for="owner__method-single">Single</label>
                                    </div>
                                    <div class="form__buttons-column">
                                        <input type="radio" id="owner__method-joint" name="method" value="J" v-model="inputs.annuity_contract">
                                        <label for="owner__method-joint">Joint</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset v-bind:class="{ 'hidden': ( inputs.annuity_contract === 'S' ) }">
                    <legend>Joint Information</legend>

                    <div class="form">
                        <div class="form__row">
                            <div class="form__column">
                                <label for="joint__name-first">First Name</label>
                                <input type="text" id="joint__name-first" name="joint_name_first" placeholder="i.e., Deborah" v-model="inputs.joint_name_first">
                            </div>
                            <div class="form__column">
                                <label for="joint__name-last">Last Name</label>
                                <input type="text" id="joint__name-last" name="joint_name_last" placeholder="i.e., Read" v-model="inputs.joint_name_last">
                            </div>
                            <div class="form__column">
                                <label>Gender</label>
                                <select id="joint__gender" name="joint_gender" v-model="inputs.joint_gender">
                                    <option v-for="( option, option_index ) in this.$globalUtils.get_dataset_as_kvp( 'gender' )" v-bind:key="option.value" v-bind:value="option.value">{{ option.label }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form__row">
                            <div class="form__column">
                                <label>Birthdate <span class="badge" v-if="inputs.joint_birthdate">Age {{ this.$globalUtils.format( 'age', inputs.joint_birthdate ) }}</span></label>
                                <input type="date" id="joint__birthdate" name="joint_birthdate" placeholder="i.e., 08/12/1979" v-model="inputs.joint_birthdate">
                            </div>
                            <div class="form__column">
                                <label>State</label>
                                <select id="joint__state" name="joint_state" v-model="inputs.joint_state">
                                    <option v-for="( state, state_index ) in this.$globalUtils.get_dataset( 'states_usa' )" v-bind:key="state_index" v-bind:value="state">{{ this.$globalUtils.format( 'states_usa', state ) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </section>
            <footer class="alpha__modal-footer">
                <button type="button" v-on:click="save">Save and Close</button>
                <button type="button" class="btn-small btn-grayscale" v-on:click="this.$globalUtils.modal_close()">Close</button>
                <button type="button" class="btn-small btn-warn" v-on:click="this.clear_fields()">Clear</button>
            </footer>
        </div>

        <div class="alpha__modal-sidebar" v-if="clients">
            <div class="alpha__modal-sidebar-bounds">
                <h3>Saved Client Records</h3>
                <p>Click on a client record to recall it and use it for quoting and estimation throughout the system.</p>

                <menu class="alpha__clients">
                    <template v-for="( client, client_index ) in clients" v-bind:key="client_index">
                        <div class="alpha__clients-record">
                            <div class="alpha__clients-record-details">
                                <h3>{{ client.owner_name_first }} {{ client.owner_name_last }}</h3>
                                <div class="alpha__clients-record-amount">{{ this.$financeUtils.format_currency( client.annuity_investment, 'USD' ) }}</div>
                                <ul class="alpha__clients-record-stats">
                                    <li data-type="location">{{ client.owner_state }}</li>
                                    <li data-type="age">Age {{ this.$globalUtils.format( 'age', client.owner_birthdate ) }}</li>
                                    <li data-type="marital">{{ this.$globalUtils.format( 'single_joint', client.annuity_contract ) }}</li>
                                </ul>
                            </div>
                            <div class="alpha__clients-record-controls">
                                <button type="button" v-on:click="load_from_storage( client.id, true )" data-button-label="Use"><i class="fal fa-user-check" aria-hidden="true"></i></button>
                                <button type="button" v-on:click="load_from_storage( client.id, false )" data-button-label="Edit"><i class="fal fa-user-pen" aria-hidden="true"></i></button>
                                <button type="button" v-on:click="remove_from_storage( client.id )" data-button-label="Delete"><i class="fal fa-user-xmark" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </template>
                </menu>
            </div>
        </div>
    </dialog>
</template>
