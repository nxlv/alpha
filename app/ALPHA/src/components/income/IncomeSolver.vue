<script>
    import { RouterLink } from 'vue-router';

    import { useSetsStore } from '@/stores/sets';
    import { useClientStore } from '@/stores/client';
    import { useInventoryStore } from "@/stores/inventory";
    import { useComparisonStore } from '@/stores/compare';

    import Infobox_IncomeBenefits from '@/components/products/infoboxes/IncomeBenefit.vue';
    import Infobox_DeathBenefits from '@/components/products/infoboxes/DeathBenefit.vue';
    import Infobox_Highlights from '@/components/products/infoboxes/Highlights.vue';
    import Infobox_Illustration from '@/components/products/infoboxes/Illustration.vue';
    import Infobox_Rules from '@/components/products/infoboxes/Rules.vue';
    import Infobox_Withdrawals from '@/components/products/infoboxes/Withdrawals.vue';
    import Infobox_Strategy from '@/components/products/infoboxes/Strategy.vue';
    import Infobox_Carrier from '@/components/products/infoboxes/Carrier.vue';
    import Infobox_Options from '@/components/products/infoboxes/Options.vue';

    import axios from 'axios';
    import { VMoney } from 'v-money';
    import Multiselect from '@vueform/multiselect';

    export default {
        components: {
            RouterLink,

            Infobox_IncomeBenefits,
            Infobox_DeathBenefits,
            Infobox_Highlights,
            Infobox_Illustration,
            Infobox_Rules,
            Infobox_Withdrawals,
            Infobox_Strategy,
            Infobox_Carrier,
            Infobox_Options,

            Multiselect
        },
        methods: {
            async fetch_quote() {
                this.loading = true;

                const inventory = useInventoryStore();
                const client = useClientStore();

                this.aborters.requests.abort();

                this.nonce = this.$globalUtils.generate_nonce();
                this.errors = null;
                this.loading = true;

                this.selections.method = this.parameters.method;
                this.selections.offset = 0;

                this.selections.comparison = [];

                let endpoint = '/api/quoting/get/fixed';
                let settings = { ...this.parameters, nonce: this.nonce, annuitant: this.$globalUtils.merge_with_defaults( client.settings, this.parameters.overrides.annuitant ), inventory: inventory.settings.inventory };

                if ( this.mode === 'comparison' ) {
                    settings = { ...settings, nonce: this.nonce, annuitant: this.$globalUtils.merge_with_defaults( client.settings, this.parameters.overrides.annuitant ), offset: this.selections.offset, chunk_size: this.parameters.chunk_size, comparisons: this.selections.comparison };
                }

                clearTimeout( this.timers.populator );

                let request = await axios.post( import.meta.env.VITE_API_BASE_URL + endpoint, { ...settings, signal: this.aborters.requests.signal } );

                /*
                if ( !this.$globalUtils.verify_nonce( this.nonce, request ) ) {
                    this.loading = false;

                    return;
                }
                */

                this.parameters.searched = true;

                if ( ( request ) && ( request.data ) ) {
                    this.quotes = [];

                    for ( let counter = 0; counter < request.data.length; counter++ ) {
                        this.quotes.push( request.data[ counter ] );
                    }

                    this.$emitter.emit( 'fetch_guaranteed', { products: this.quotes.slice( 0, this.parameters.chunk_size ), parameters: this.parameters } );
                }
            },

            async fetch_quote_guaranteed( inputs ) {
                const client = useClientStore();

                let endpoint = '/api/quoting/get/fixed/guaranteed';
                let settings = { products: [], nonce: this.nonce, annuitant: this.$globalUtils.merge_with_defaults( client.settings, this.parameters.overrides.annuitant ), settings: inputs.parameters };

                for ( let counter = 0; counter < inputs.products.length; counter++ ) {
                    settings.products.push( inputs.products[ counter ].analysis_data_id );
                }

                let request = await axios.post( import.meta.env.VITE_API_BASE_URL + endpoint, { ...settings, signal: this.aborters.requests.signal } );

                /*
                if ( !this.$globalUtils.verify_nonce( this.nonce, request ) ) {
                    this.loading = false;

                    return;
                }
                */

                if ( ( request ) && ( request.data ) ) {
                    let index, index_inner;

                    for ( index = 0; index < request.data.length; index++ ) {
                        for ( index_inner = 0; index_inner < this.quotes.length; index_inner++ ) {
                            if ( this.quotes[ index_inner ].analysis_data_id === request.data[ index ].analysis_data_id ) {
                                this.quotes[ index_inner ].quotes = request.data[ index ];
                                break;
                            }
                        }
                    }
                }

                this.sort_results();

                // fetch backtested returns
                //this.$emitter.emit( 'fetch_backtested', { products: this.quotes.slice( this.selections.offset, this.parameters.chunk_size ), parameters: this.parameters } );

                this.loading = false;

                this.selections.offset += this.parameters.chunk_size;
            },

            async fetch_quote_backtested_return( inputs ) {
                const client = useClientStore();

                let endpoint = '/api/quoting/get/fixed/chunk/backtested';
                let settings = { products: [], nonce: this.nonce, annuitant: this.$globalUtils.merge_with_defaults( client.settings, this.parameters.overrides.annuitant ), settings: inputs.parameters };

                for ( let counter = 0; counter < inputs.products.length; counter++ ) {
                    settings.products.push( inputs.products[ counter ].analysis_data_id );
                }

                let request = await axios.post( import.meta.env.VITE_API_BASE_URL + endpoint, { ...settings, signal: this.aborters.requests.signal } );

                /*
                if ( !this.$globalUtils.verify_nonce( this.nonce, request ) ) {
                    this.loading = false;

                    return;
                }
                */

                if ( ( request ) && ( request.data ) ) {
                    let index, index_inner, index_income;

                    for ( index = 0; index < request.data.length; index++ ) {
                        for ( index_inner = 0; index_inner < this.quotes.length; index_inner++ ) {
                            if ( ( request.data[ index ].analysis_request ) && ( request.data[ index ].analysis_data ) ) {
                                if ( this.quotes[ index_inner ].analysis_data_id === request.data[ index ].analysis_request.analysis_data_id ) {
                                    console.log( 'found backtested match for analysis id# ', this.quotes[ index_inner ].analysis_data_id );

                                    this.quotes[ index_inner ].historical = {
                                        request: request.data[ index ].analysis_request,
                                        income: 0,
                                        account_value: 0,
                                        account_return: 0
                                    };

                                    for ( index_income = 0; index_income < request.data[ index ].analysis_data.length; index_income++ ) {
                                        if ( request.data[ index ].analysis_data[ index_income ].income ) {
                                            this.quotes[ index_inner ].historical.income = request.data[ index ].analysis_data[ index_income ].income;
                                            this.quotes[ index_inner ].historical.account_value = request.data[ index ].analysis_data[ index_income - 1 ].account_value;
                                            this.quotes[ index_inner ].historical.account_return = ( ( request.data[ index ].analysis_data[ index_income - 1 ].account_value / request.data[ index ].analysis_request.premium ) - 1 ) * 100;

                                            console.log( 'account value', request.data[ index ].analysis_data[ index_income - 1 ].account_value, 'premium', request.data[ index ].analysis_request.premium );
                                            break;
                                        }
                                    }

                                    break;
                                }
                            }
                        }
                    }

                    // auto fetch the first 100 results
                    /*
                    if ( this.selections.offset < ( this.parameters.chunk_size * this.parameters.chunk_prefetch ) ) {
                        this.timers.populator = setTimeout( this.fetch_chunk, 1000 );
                    }
                    */
                }
            },

            async fetch_chunk() {
                const inventory = useInventoryStore();
                const client = useClientStore();

                let endpoint = '/api/quoting/get/fixed';
                let settings = { ...this.parameters, nonce: this.nonce, offset: this.selections.offset, chunk_size: this.parameters.chunk_size, annuitant: this.$globalUtils.merge_with_defaults( client.settings, this.parameters.overrides.annuitant ), inventory: inventory.settings.inventory };

                let request = await axios.post( import.meta.env.VITE_API_BASE_URL + endpoint, { ...settings, signal: this.aborters.requests.signal } );

                /*
                if ( !this.$globalUtils.verify_nonce( this.nonce, request ) ) {
                    this.loading = false;

                    return;
                }
                */

                if ( ( request ) && ( request.data ) ) {
                    for ( let counter = 0; counter < request.data.length; counter++ ) {
                        console.log( 'adding', request.data[ counter ] );

                        this.quotes.push( request.data[ counter ] );
                    }

                    this.$emitter.emit( 'fetch_guaranteed', { products: this.quotes.slice( this.selections.offset, ( this.selections.offset + this.parameters.chunk_size ) ), parameters: this.parameters } );
                }
            },

            async fetch_illustration() {
                const client = useClientStore();

                let endpoint = '/api/quoting/get/fixed/illustration';
                let request = await axios.post( import.meta.env.VITE_API_BASE_URL + endpoint, { product: this.selections.product_id, settings: this.parameters, annuitant: this.$globalUtils.merge_with_defaults( client.settings, this.parameters.overrides.annuitant ), signal: this.aborters.requests.signal } );

                this.errors = null;
                this.loading = false;

                this.selections.illustration.valid = false;
                this.selections.illustration.dataset = null;
                this.selections.illustration.message = null;

                if ( ( request ) && ( request.data ) && ( request.data.result ) && ( request.data.result.length ) ) {
                    if ( request.data.result[ 0 ].analysis_error ) {
                        this.selections.illustration.message = request.data.result[ 0 ].analysis_error.error_message;
                    } else if ( request.data.result[ 0 ].analysis_data.length ) {
                        this.selections.illustration.valid = true;
                        this.selections.illustration.dataset = request.data.result[ 0 ].analysis_data;
                    }
                } else {
                    if ( request.data.result[ 0 ].analysis_error ) {
                        this.selections.illustration.message = 'Invalid response from API.';
                    }
                }
            },

            async fetch_details() {
                this.loading = true;

                const client = useClientStore();

                let endpoint = '/api/products/details';
                let request = await axios.post( import.meta.env.VITE_API_BASE_URL + endpoint, { ...client.settings, product: this.selections.product_id, signal: this.aborters.requests.signal } );

                this.errors = null;
                this.loading = false;

                if ( ( request ) && ( request.data ) && ( request.data.details ) ) {
                    this.selections.product = request.data.details[ 0 ];
                    this.selections.product.options = ( ( request.data.options ) ? request.data.options : null );

                    // fetch illustration
                    await this.fetch_illustration();
                }
            },

            async fetch_report() {
                this.loading = true;

                const client = useClientStore();

                let endpoint = '/api/quoting/report'
                let request = await axios.post( import.meta.env.VITE_API_BASE_URL + endpoint, { product: this.selections.product_id, annuitant: this.$globalUtils.merge_with_defaults( client.settings, this.parameters.overrides.annuitant ), settings: this.parameters, signal: this.aborters.requests.signal } );

                this.errors = null;
                this.loading = false;

                if ( ( request ) && ( request.data ) ) {
                    console.log( request.data );
                }
            },

            sort_results() {
                let response = this.quotes;

                switch ( this.selections.method ) {
                    case 'premium' :
                        response.sort( ( left, right ) => {
                            return ( ( left.income_initial > right.income_initial ) ? -1 : ( ( left.income_initial === right.income_initial ) ? 0 : 1 ) );
                        } );
                        break;

                    case 'income' :
                        /*
                        TODO: rebuild

                        response.sort( ( left, right ) => {
                            return ( ( left.quotes.income_data.initial_income < right.quotes.income_data.initial_income ) ? -1 : ( ( left.quotes.income_data.initial_income === right.quotes.income_data.initial_income ) ? 0 : 1 ) );
                        } );
                        */
                        break;
                }

                this.quotes = response;
            },

            set_deferral_period() {
                clearTimeout( this.timers.refresh );
                clearTimeout( this.timers.populator );

                this.timers.refresh = setTimeout( () => {
                    this.parameters.deferral = this.parameters.deferral_selected;
                    this.nonce = this.$globalUtils.generate_nonce();

                    this.fetch_quote();
                }, 175 );
            },

            set_product( product_id, strategy_premium ) {
                this.selections.product_id = product_id;
                this.selections.details = 'illustration';   // start on the illustration tab of the details window

                if ( strategy_premium ) {
                    this.selections.premium = strategy_premium;
                }

                this.fetch_details();
            },

            replace_product( product_id, strategy_premium ) {
                this.close_details();

                clearTimeout( this.timers.replace );

                this.timers.replace = setTimeout( () => { this.set_product( product_id, strategy_premium ) }, 250 );
            },

            close_details() {
                this.selections.product_id = null;
                this.selections.product = null;
                this.selections.illustration.valid = false;
                this.selections.illustration.message = null;
                this.selections.illustration.dataset = null;
            },

            toggle_comparison() {
                this.mode = ( ( this.mode === 'comparison' ) ? 'normal' : 'comparison' );
                this.nonce = this.$globalUtils.generate_nonce();

                this.fetch_quote();
            },

            set_details_page( page ) {
                this.selections.details = page;
            },

            clear_client_overrides( type ) {
                let key;

                switch ( type ) {
                    case 'owner' :
                    case 'joint' :
                        for ( key in this.parameters.overrides.annuitant ) {
                            if ( key.indexOf( type + '_' ) > -1 ) {
                                this.parameters.overrides.annuitant[ key ] = null;
                            }
                        }
                        break;

                    default :
                        for ( key in this.parameters.overrides.annuitant ) {
                            this.parameters.overrides.annuitant[ key ] = null;
                        }
                        break;
                }
            }
        },
        created() {
            const client = useClientStore();

            this.$emitter.on( 'set_product_id', this.replace_product );
            this.$emitter.on( 'fetch_guaranteed', this.fetch_quote_guaranteed );
            this.$emitter.on( 'fetch_backtested', this.fetch_quote_backtested_return );

            this.aborters.requests = new AbortController();

            this.nonce = this.$globalUtils.generate_nonce();

            if ( client.settings.annuity_investment ) {
                console.log( 'Client Override -- setting premium ', client.settings.annuity_investment );
                this.parameters.premium = client.settings.annuity_investment;
            }

            this.fetch_quote();
        },
        data() {
            return {
                mode: 'normal',
                loading: false,

                errors: null,
                quotes: null,
                nonce: null,

                timers: {
                    replace: null,
                    refresh: null,
                    populator: null,
                },

                aborters: {
                    requests: null
                },

                selections: {
                    method: 'premium',
                    product_id: null,
                    product: {
                        death_benefit: null,
                        income_benefit: null,
                        carrier: null,
                        options: null,
                    },
                    illustration: {
                        valid: false,
                        message: null,
                        dataset: null
                    },
                    details: 'options',
                    premium: '100000',
                    offset: 0,
                    comparison: []
                },

                parameters: {
                    searched: false,
                    chunk_size: 20,
                    chunk_prefetch: 3,
                    premium: '100000',
                    income: '',
                    deferral: 10,
                    deferral_selected: 10,
                    method: 'premium',
                    index: [],
                    carrier: [],
                    strategy_type: [],
                    strategy_configuration: [],
                    calculation_frequency: [],
                    crediting_frequency: [],
                    participation_rate: '100',
                    guarantee_period_years: 1,
                    guarantee_period_months: 0,
                    overrides: {
                        annuitant: {
                            annuity_type: null,

                            owner_state: null,
                            owner_age: null,
                            owner_gender: null,

                            joint_state: null,
                            joint_age: null,
                            joint_gender: null,
                        }
                    }
                },

                options: {
                    money: {
                        decimal: '.',
                        thousands: ',',
                        prefix: '$',
                        suffix: '',
                        precision: 0,
                        masked: false
                    }
                }
            };
        },
        directives: {
            money: VMoney
        }
    };
</script>

<style src="@vueform/multiselect/themes/default.css"></style>

<template>
    <section class="income-solver" v-bind:data-mode="mode">
        <div class="income-solver__content">
            <!--
            <label for="income-solver__compare-toggle" class="income-solver__compare-toggler" v-if="selections.comparison.length" v-on:click="toggle_comparison">
                <i class="fal fa-list-timeline" aria-hidden="true"></i>
            </label>
            -->

            <button type="submit" class="income-solver__fetch-button" v-on:click="fetch_quote">Fetch Quotes <i class="fas fa-arrow-right" aria-hidden="true"></i></button>

            <aside class="income-solver__parameters form">
                <fieldset data-filter-type="income">
                    <legend><i class="fa-duotone fa-money-check-dollar-pen"></i> Premium &amp; Income</legend>

                    <input type="checkbox" class="hidden" id="income__toggler" value="1">
                    <label for="income__toggler"></label>

                    <div class="income__content">
                        <div class="form__row" data-row-type="mode">
                            <div class="form__column">
                                <label>Solve for...</label>
                                <div class="form__buttons">
                                    <div class="form__buttons-column">
                                        <input type="radio" id="parameters__method-income" name="method" value="income" v-model="parameters.method">
                                        <label for="parameters__method-income--disabled">Income</label>
                                    </div>
                                    <div class="form__buttons-column">
                                        <input type="radio" id="parameters__method-premium" name="method" value="premium" v-model="parameters.method">
                                        <label for="parameters__method-premium">Premium</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form__row" data-row-type="input">
                            <div class="form__column" v-if="parameters.method === 'premium'">
                                <label>Desired Premium</label>
                                <input type="text" id="parameters__premium" name="premium" class="money" placeholder="i.e., $100,000" v-model.lazy="parameters.premium" v-money="options.money">
                            </div>
                            <div class="form__column" v-if="parameters.method === 'income'">
                                <label>Desired Annual Income</label>
                                <input disabled type="text" id="parameters__premium" name="income" class="money" placeholder="i.e., $5,000.00" v-model.lazy="parameters.income" v-money="options.money">
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset data-filter-type="client">
                    <legend><i class="fa-duotone fa-handshake"></i> Client Information</legend>

                    <input type="checkbox" class="hidden" id="client__toggler" value="1">
                    <label for="client__toggler"></label>

                    <div class="client__content">
                        <div class="form__row">
                            <div class="form__column">
                                <div class="notice"><p>You can override any of your clients' settings here.  This <u>will not</u> make permanent changes to your client records.</p></div>

                                <label>Benefit Type</label>
                                <select id="parameters__client-type" name="client-type" v-model="parameters.overrides.annuitant.annuity_type">
                                    <option value="">&mdash;</option>
                                    <option v-for="( option, option_index ) in this.$globalUtils.get_dataset_as_kvp( 'single_joint' )" v-bind:key="option_index" v-bind:value="option.value">{{ option.label }}</option>
                                </select>
                            </div>

                        </div>
                        <div class="form__row">
                            <div class="form__column">
                                <fieldset>
                                    <legend>Owner <span class="form__action--inline" v-on:click="clear_client_overrides( 'owner' )">Clear All</span></legend>

                                    <div class="form__row">
                                        <div class="form__column">
                                            <label>Gender</label>
                                            <select id="owner__gender" name="owner_gender" v-model="parameters.overrides.annuitant.owner_gender">
                                                <option value="">&mdash;</option>
                                                <option v-for="( option, option_index ) in this.$globalUtils.get_dataset_as_kvp( 'gender' )" v-bind:key="option_index" v-bind:value="option.value">{{ option.label }}</option>
                                            </select>
                                        </div>
                                        <div class="form__column">
                                            <label>Age</label>
                                            <input type="number" id="owner__age" name="owner_age" placeholder="&mdash;" v-model="parameters.overrides.annuitant.owner_age">
                                        </div>
                                        <div class="form__column">
                                            <label>State</label>
                                            <select id="owner__state" name="owner_state" v-model="parameters.overrides.annuitant.owner_state">
                                                <option value="">&mdash;</option>
                                                <option v-for="( state, state_index ) in this.$globalUtils.get_dataset( 'states_usa' )" v-bind:key="state_index" v-bind:value="state_index">{{ this.$globalUtils.format( 'states_usa', state ) }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="form__row">
                            <div class="form__column">
                                <fieldset v-if="parameters.overrides.annuitant.annuity_type === 'J'">
                                    <legend>Joint <span class="form__action--inline" v-on:click="clear_client_overrides( 'joint' )">Clear All</span></legend>

                                    <div class="form__row">
                                        <div class="form__column">
                                            <label>Gender</label>
                                            <select id="joint__gender" name="joint_gender" v-model="parameters.overrides.annuitant.joint_gender">
                                                <option value="">&nbsp;</option>
                                                <option v-for="( option, option_index ) in this.$globalUtils.get_dataset_as_kvp( 'gender' )" v-bind:key="option_index" v-bind:value="option.value">{{ option.label }}</option>
                                            </select>
                                        </div>
                                        <div class="form__column">
                                            <label>Age</label>
                                            <input type="number" id="joint__age" name="joint_age" v-model="parameters.overrides.annuitant.joint_age">
                                        </div>
                                        <div class="form__column">
                                            <label>State</label>
                                            <select id="joint__state" name="joint_state" v-model="parameters.overrides.annuitant.joint_state">
                                                <option value="">&nbsp;</option>
                                                <option v-for="( state, state_index ) in this.$globalUtils.get_dataset( 'states_usa' )" v-bind:key="state_index" v-bind:value="state_index">{{ this.$globalUtils.format( 'states_usa', state ) }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset data-filter-type="filtering">
                    <legend><i class="fa-duotone fa-filter-list"></i> Filtering Options</legend>

                    <input type="checkbox" class="hidden" id="filtering__toggler" value="1">
                    <label for="filtering__toggler"></label>

                    <div class="filtering__content">
                        <div class="form__row">
                            <div class="form__column">
                                <label for="indexes">Index</label>
                                <multiselect v-model="parameters.index"
                                             mode="multiple"
                                             label="label"
                                             track-by="label"
                                             :options="this.$globalUtils.get_set_as_kvp( 'indexes' )"
                                             :hide-selected="false"
                                             :close-on-select="false"
                                             :searchable="true"
                                             :create-option="false">
                                </multiselect>
                            </div>
                        </div>

                        <div class="form__row">
                            <div class="form__column">
                                <label for="indexes">Carrier</label>
                                <multiselect v-model="parameters.carrier"
                                             mode="multiple"
                                             label="label"
                                             track-by="label"
                                             :options="this.$globalUtils.get_set_as_kvp( 'carriers' )"
                                             :hide-selected="false"
                                             :close-on-select="false"
                                             :searchable="true"
                                             :create-option="false">
                                </multiselect>
                            </div>
                        </div>

                        <div class="form__row">
                            <div class="form__column">
                                <label for="strategy_configuration">Strategy</label>
                                <multiselect v-model="parameters.strategy_configuration"
                                             mode="multiple"
                                             label="label"
                                             track-by="label"
                                             :options="this.$globalUtils.get_dataset_as_kvp( 'strategy_configuration' )"
                                             :hide-selected="false"
                                             :close-on-select="false"
                                             :searchable="true"
                                             :create-option="false">
                                </multiselect>
                            </div>
                        </div>

                        <div class="form__row">
                            <div class="form__column">
                                <label for="strategy_type">Type</label>
                                <multiselect v-model="parameters.strategy_type"
                                             mode="multiple"
                                             label="label"
                                             track-by="label"
                                             :options="this.$globalUtils.get_dataset_as_kvp( 'strategy_type' )"
                                             :hide-selected="false"
                                             :close-on-select="false"
                                             :searchable="true"
                                             :create-option="false">
                                </multiselect>
                            </div>
                        </div>

                        <div class="form__row">
                            <div class="form__column">
                                <label for="calculation_frequency">Calculation Frequency</label>
                                <multiselect v-model="parameters.calculation_frequency"
                                             mode="multiple"
                                             label="label"
                                             track-by="label"
                                             :options="this.$globalUtils.get_dataset_as_kvp( 'frequency' )"
                                             :hide-selected="false"
                                             :close-on-select="false"
                                             :searchable="true"
                                             :create-option="false">
                                </multiselect>
                            </div>
                        </div>

                        <div class="form__row">
                            <div class="form__column">
                                <label for="crediting_frequency">Crediting Frequency</label>
                                <multiselect v-model="parameters.crediting_frequency"
                                             mode="multiple"
                                             label="label"
                                             track-by="label"
                                             :options="this.$globalUtils.get_dataset_as_kvp( 'frequency' )"
                                             :hide-selected="false"
                                             :close-on-select="false"
                                             :searchable="true"
                                             :create-option="false">
                                </multiselect>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </aside>

            <div class="income-solver__results">
                <div class="strategy" v-if="selections.product_id">
                    <div class="strategy__inner">
                        <h3>Strategy Details<a href="javascript:" v-on:click="close_details"><i class="fal fa-close" aria-hidden="true"></i> <span>Close</span></a></h3>

                        <header class="strategy__header" v-bind:data-carrier-slug="this.$globalUtils.sanitize_title( selections.product.carrier_product.carrier.name )">
                            <h4>{{ selections.product.carrier_product.name }} <span>{{ this.$productUtils.generate_iao_name( selections.product ) }}</span></h4>
                            <h5>{{ selections.product.carrier_product.carrier.name }}</h5>
                        </header>

                        <menu class="strategy__menu">
                            <li class="strategy__menu-item strategy__menu-item--special" data-type="download" v-on:click="fetch_report()">
                                <span>Download Report</span>
                            </li>

                            <!-- TODO: Replace with loop to avoid duplicated code -->
                            <li class="strategy__menu-item" v-bind:class="{ 'strategy__menu-item--selected': selections.details === 'highlights' }" data-type="highlights" v-on:click="set_details_page( 'highlights' )">
                                <span>Highlights</span>
                            </li>
                            <li class="strategy__menu-item" v-bind:class="{ 'strategy__menu-item--selected': selections.details === 'options' }" data-type="options" v-on:click="set_details_page( 'options' )">
                                <span>Options</span>
                            </li>
                            <li class="strategy__menu-item" v-bind:class="{ 'strategy__menu-item--selected': selections.details === 'carrier' }" data-type="carrier" v-on:click="set_details_page( 'carrier' )">
                                <span>Carrier</span>
                            </li>
                            <li class="strategy__menu-item" v-bind:class="{ 'strategy__menu-item--selected': selections.details === 'illustration' }" data-type="illustration" v-on:click="set_details_page( 'illustration' )">
                                <span>Illustration</span>
                            </li>
                            <li class="strategy__menu-item" v-bind:class="{ 'strategy__menu-item--selected': selections.details === 'withdrawals' }" data-type="withdrawals" v-on:click="set_details_page( 'withdrawals' )">
                                <span>Withdrawals</span>
                            </li>
                            <li class="strategy__menu-item" v-bind:class="{ 'strategy__menu-item--selected': selections.details === 'rules' }" data-type="rules" v-on:click="set_details_page( 'rules' )">
                                <span>Rules</span>
                            </li>
                        </menu>

                        <div class="strategy__details">
                            <div class="strategy__details-highlights" data-type="highlights" v-if="selections.details === 'highlights'">
                                <Infobox_Highlights v-bind:profile="selections.product.options" />
                            </div>
                            <div class="strategy__details-options" data-type="options" v-if="selections.details === 'options'">
                                <Infobox_Options v-bind:profile="selections.product.options" />
                            </div>
                            <div class="strategy__details-carrier" data-type="carrier" v-if="selections.details === 'carrier'">
                                <Infobox_Carrier v-bind:profile="selections.product.carrier_product" />
                            </div>
                            <div class="strategy__details-illustration" data-type="highlights" v-if="selections.details === 'illustration'">
                                <div class="strategy__details-illustration-notice" v-if="!selections.illustration.dataset && !selections.illustration.message">
                                    <div class="strategy__details-illustration-notice-loader">
                                        <i class="fal fa-folder-magnifying-glass" aria-hidden="true"></i> Fetching Illustration...
                                    </div>
                                    <div class="strategy__details-illustration-notice-text">
                                        This could take a minute...
                                    </div>
                                </div>
                                <div class="alert alert-error" data-type="highlights" v-if="!selections.illustration.dataset && selections.illustration.message">
                                    <h3>An error has occurred!</h3>
                                    <p>Please close this window and try again.</p>
                                    <p><small>Reason: {{ selections.illustration.message }}</small></p>
                                </div>

                                <div class="strategy__details-illustration-table" data-type="illustration" v-if="selections.illustration.valid">
                                    <Infobox_Illustration v-bind:profile="selections.illustration.dataset" />
                                </div>
                            </div>
                            <div class="strategy__details-withdrawals" data-type="withdrawals" v-if="selections.details === 'withdrawals'">
                                <Infobox_Withdrawals v-bind:profile="selections.product" />
                            </div>
                            <div class="strategy__details-rules" data-type="rules" v-if="selections.details === 'rules'">
                                <Infobox_Rules v-bind:profile="selections.product.rules" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="income-solver__results-controls" v-if="quotes && !loading">
                    <div class="form__row">
                        <div class="form__column form__column--full">
                            <label for="deferral">Defer Income for <strong>{{ parameters.deferral_selected }} years</strong></label>
                            <input type="range" name="deferral" step="1" min="5" max="20" v-on:change="set_deferral_period" v-model="parameters.deferral_selected">
                        </div>
                    </div>

                    <div class="form__row" v-if="selections.comparison.length && selections.comparison.length > 1">
                        <div class="form__column form__column--full">
                            <button type="button" v-on:click="toggle_comparison">
                                <span v-if="mode === 'comparison'"><i class="fa-solid fa-angles-left"></i> Exit Comparison Mode</span>
                                <span v-if="mode === 'normal'"><i class="fa-light fa-sitemap" aria-hidden="true"></i> Enter Comparison Mode</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="loading" v-if="loading">
                    <div class="bookshelf_wrapper">
                        <ul class="books_list">
                            <li class="book_item first"></li>
                            <li class="book_item second"></li>
                            <li class="book_item third"></li>
                            <li class="book_item fourth"></li>
                            <li class="book_item fifth"></li>
                            <li class="book_item sixth"></li>
                        </ul>
                        <div class="books_status">
                            Running the numbers, this might take a minute...
                        </div>
                    </div>
                </div>

                <div class="alert alert-error" v-if="errors">
                    <h3>An error has occurred!</h3>
                    <template v-for="( error, error_index ) in errors" v-bind:key="error_index">
                        <p>{{ error.message }}</p>
                        <p><small>{{ error.code }}</small></p>
                    </template>
                </div>

                <div class="alert alert-info" v-if="parameters.searched && !loading && !quotes">
                    <h3>No products found!</h3>
                    <p>Try adjusting your search criteria, and try again.</p>
                </div>

                <!--
                <div>
                    <button type="button" v-on:click="aborters.requests.abort()">Abort All</button>
                </div>
                -->

                <div class="results" v-bind:class="{ 'results--inactive': selections.product_id }">
                    <div class="results__inner">
                        <template v-if="quotes" v-for="( result, result_index ) in quotes" v-bind:key="result_index">
                            <div class="result result__card" v-bind:class="{ 'result--faded': parameters.deferral != parameters.deferral_selected }">
                                <h4 class="result__card-title" v-bind:data-product-id="result.analysis_data_id" v-bind:data-carrier-slug="this.$globalUtils.sanitize_title( result.analysis.carrier_product.carrier.name )">
                                    <div class="result__card-title-text">
                                        <strong>{{ result.analysis.carrier_product.name }}</strong>
                                        <span v-html="this.$productUtils.generate_iao_name( result.analysis )"></span>

                                        <div class="result__card-title-comparison">
                                            <input type="checkbox" v-bind:id="'product_' + result.analysis_data_id" v-bind:value="result.analysis_data_id" v-model="selections.comparison">
                                            <label v-bind:for="'product_' + result.analysis_data_id">Add to Compare</label>
                                        </div>
                                    </div>
                                    <template v-if="result.analysis.carrier_product.carrier.ratings">
                                        <div class="result__card-title-ratings">
                                            <span v-for="( rating, rating_index ) in result.analysis.carrier_product.carrier.ratings" class="result__card-title-ratings-rating" v-bind:index="rating_index" v-bind:data-rating-company="rating.company" v-bind:data-rating-value="rating.rating">{{ rating.rating }}</span>
                                        </div>
                                    </template>
                                </h4>
                                <div class="result__card-strategies">
                                    <div class="result__card-strategy" v-on:click="set_product( result.analysis_data_id, selections.premium )">
                                        <div class="result__card-strategy-meta">
                                            <span data-type="id" title="CANNEX Analysis ID">{{ result.analysis_data_id }}</span>
                                            <span data-type="id" title="CANNEX Product ID">{{ result.analysis.product_id }}</span>
                                        </div>
                                        <div class="result__card-strategy-income" v-bind:class="{ 'result__card-strategy-income--loading': !result.quotes }">
                                            <template v-if="selections.method == 'premium'">
                                                <div class="result__card-strategy-income-money" data-period="annually" data-method="premium" data-type="guaranteed">
                                                    <span v-if="result.quotes" data-type="result">{{ this.$financeUtils.format_currency( result.quotes.income_data.initial_income, 'USD' ) }}</span>
                                                    <span v-if="!result.quotes" data-type="loading">Loading...</span>
                                                </div>
                                            </template>
                                            <template v-if="selections.method == 'income'">
                                                <div class="result__card-strategy-income-money" data-period="annually" data-method="income" data-type="hypothetical">{{ this.$financeUtils.format_currency( result.quotes.income_data.initial_income, 'USD' ) }}</div>
                                                <div class="result__card-strategy-income-money" data-period="annually" data-method="income" data-type="income">{{ this.$financeUtils.format_currency( result.quotes.income_data.initial_income, 'USD' ) }}</div>
                                            </template>

                                            <div class="result__card-strategy-income-backtest">
                                                <div class="result__card-strategy-data-points">
                                                    <span class="result__card-strategy-data-point" data-type="setting" data-type-title="Surrender Period">
                                                        <abbr title="Years"><strong>{{ result.analysis.surrender_period_years }}</strong> years</abbr> <abbr title="Months" v-if="result.analysis.surrender_period_months"><strong>{{ result.analysis.surrender_period_months }}</strong> months</abbr>
                                                    </span>

                                                    <span class="result__card-strategy-data-point" data-type="setting" data-type-title="Guarantee Period">
                                                        <abbr title="Years"><strong>{{ result.analysis.guarantee_period_years }}</strong> years</abbr> <abbr title="Months" v-if="result.analysis.guarantee_period_months"><strong>{{ result.analysis.guarantee_period_months }}</strong> months</abbr>
                                                    </span>
                                                </div>

                                                <cite class="result__card-strategy-income-backtest-results" v-bind:class="{ 'result__card-strategy-income-backtest-results--loading': !result.historical }">
                                                    <span v-if="result.historical" data-type="result"><strong>{{ result.historical.account_return.toFixed( 2 ) }}%</strong> anticipated return over {{ result.historical.request.analysis_time_horizon_years - 1 }} years.</span>
                                                    <span v-if="!result.historical" data-type="loading">Calculating anticipated return...</span>
                                                </cite>
                                            </div>
                                        </div>

                                        <div class="result__card-strategy-data">
                                            <div class="result__card-strategy-data-points">
                                                <span class="result__card-strategy-data-point" data-type="setting" data-type-title="Rider Type/Name">
                                                    {{ result.analysis.income_benefit.name }}
                                                </span>
                                                <span class="result__card-strategy-data-point" data-type="rate-percent" data-type-title="Annual Rider Fee">
                                                    <template v-for="( fee, fee_index ) in result.analysis.income_benefit.rider_fee_current" v-bind:key="fee_index" v-if="result.analysis.income_benefit.rider_fee_current.length">
                                                        <span><em>Tier {{ fee.tier_no }}</em> {{ fee.rate }}%</span>
                                                    </template>
                                                    <template v-if="!result.analysis.income_benefit.rider_fee_current.length">None</template>
                                                </span>
                                                <span class="result__card-strategy-data-point" data-type="rate-percent" data-type-title="Roll-up Rate">
                                                    <template v-for="( roll_up, roll_up_index ) in result.analysis.income_benefit.roll_up" v-bind:key="roll_up_index" v-if="result.analysis.income_benefit.roll_up.length">
                                                        <span><em>Tier {{ roll_up.tier_no }}</em> {{ roll_up.rate }}%</span>
                                                    </template>
                                                    <template v-if="!result.analysis.income_benefit.roll_up.length">n/a</template>
                                                </span>
                                                <span class="result__card-strategy-data-point" data-type="setting" data-type-title="Min. Income Start Age">
                                                    <template v-for="( age, age_index ) in result.analysis.income_benefit.income_start_age" v-bind:key="age_index" v-if="result.analysis.income_benefit.income_start_age.length">
                                                        <span>{{ age.min_years }} years old</span>
                                                    </template>
                                                    <template v-if="!result.analysis.income_benefit.income_start_age.length">None</template>
                                                </span>
                                                <span class="result__card-strategy-data-point" data-type="rate-percent" data-type-title="Premium Bonus">
                                                    <template v-for="( bonus, bonus_index ) in result.analysis.income_benefit.premium_bonus" v-bind:key="bonus_index" v-if="result.analysis.income_benefit.premium_bonus.length">
                                                        <span><em>Tier {{ bonus.tier_no }}</em> {{ bonus.rate }}%</span>
                                                    </template>
                                                    <template v-if="!result.analysis.income_benefit.premium_bonus.length">None</template>
                                                </span>
                                                <span class="result__card-strategy-data-point" data-type="rate-percent" data-type-title="Premium Multiplier">
                                                    <template v-for="( multiplier, multiplier_index ) in result.analysis.income_benefit.premium_multiplier" v-bind:key="multiplier_index" v-if="result.analysis.income_benefit.premium_multiplier.length">
                                                        <span><em>Tier {{ multiplier.tier_no }}</em> {{ multiplier.rate }}%</span>
                                                    </template>
                                                    <template v-if="!result.analysis.income_benefit.premium_multiplier.length">None</template>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="result__card-strategy-data result__card-strategy-data--extra">
                                            <div class="result__card-strategy-details-points result__card-strategy-details-points--vertical">
                                                <span class="result__card-strategy-details-point" data-type="index" data-type-title="Index">{{ this.$globalUtils.format( 'index', result.analysis.strategy.index_id ) }}</span>
                                                <span class="result__card-strategy-details-point" data-type="setting" data-type-title="Type">{{ this.$globalUtils.format( 'strategy_type', result.analysis.strategy.strategy_type ) }}</span>
                                                <span class="result__card-strategy-details-point" data-type="setting" data-type-title="Configuration">{{ this.$globalUtils.format( 'strategy_configuration', result.analysis.strategy.strategy_configuration ) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template v-if="this.quotes && this.mode !== 'comparison'">
                            <div class="form result__notice">
                                <p>Load more results?</p>
                                <button type="button" class="form__action" v-on:click="fetch_chunk">Load More</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>