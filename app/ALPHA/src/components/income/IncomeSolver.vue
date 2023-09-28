<script>
    import { RouterLink } from 'vue-router';

    import { useSetsStore } from '@/stores/sets';
    import { useClientStore } from '@/stores/client';
    import { useComparisonStore } from '@/stores/compare';

    import Infobox_IncomeBenefits from '@/components/products/infoboxes/IncomeBenefit.vue';
    import Infobox_DeathBenefits from '@/components/products/infoboxes/DeathBenefit.vue';
    import Infobox_Illustration from '@/components/products/infoboxes/Illustration.vue';
    import Infobox_Strategy from '@/components/products/infoboxes/Strategy.vue';
    import Infobox_Carrier from '@/components/products/infoboxes/Carrier.vue';
    import Infobox_Options from '@/components/products/infoboxes/Options.vue';

    import axios from 'axios';
    import { VMoney } from 'v-money';
    import Multiselect from '@vueform/multiselect';
    import {useInventoryStore} from "../../stores/inventory";

    export default {
        components: {
            RouterLink,

            Infobox_IncomeBenefits,
            Infobox_DeathBenefits,
            Infobox_Illustration,
            Infobox_Strategy,
            Infobox_Carrier,
            Infobox_Options,

            Multiselect
        },
        methods: {
            async fetch_quote() {
                console.log( 'fetching quote' );

                this.loading = true;

                const inventory = useInventoryStore();

                let endpoint = '/api/quoting/get/fixed';
                let settings = { ...this.parameters, inventory: inventory.settings.inventory };

                this.errors = null;
                this.quotes = null;
                this.results = null;
                this.show_all = false;
                this.loading = true;

                this.selections.method = this.parameters.method;

                if ( this.mode === 'comparison' ) {
                    console.log( 'comparison mode enabled' );

                    settings = { ...settings, comparisons: this.selections.comparison };

                    console.log( 'settings = ', settings );
                }

                let request = await axios.post( import.meta.env.VITE_API_BASE_URL + endpoint, settings );

                this.loading = false;

                this.parameters.searched = true;

                if ( ( request ) && ( request.data ) ) {
                    this.quotes = request.data.products;

                    this.sort_results();
                }
            },

            async fetch_illustration() {
                let endpoint = '/api/quoting/get/fixed/illustration';
                let request = await axios.post( import.meta.env.VITE_API_BASE_URL + endpoint, { ...this.parameters, ...{ premium: ( ( this.selections.method === 'income' ) ? this.selections.premium : this.parameters.premium ), owner_state: this.parameters.state, product_analysis_id: this.selections.product_id } } );

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
                let request = await axios.post( import.meta.env.VITE_API_BASE_URL + endpoint, { ...client.settings, ...{ product: this.selections.product_id } } );

                this.errors = null;
                this.loading = false;

                if ( ( request ) && ( request.data ) && ( request.data.details ) ) {
                    this.selections.product = request.data.details;
                    this.selections.product.options = ( ( request.data.options ) ? request.data.options : null );

                    // fetch illustration
                    await this.fetch_illustration();
                }
            },

            sort_results() {
                let response = [];
                let counter, counter_inner;
                let product;

                this.results = null;

                for ( product in this.quotes ) {
                    for ( counter = 0; counter < this.quotes[ product ].targets.length; counter++ ) {
                        if ( this.quotes[ product ].targets[ counter ].predictions ) {
                            for ( counter_inner = 0; counter_inner < this.quotes[ product ].targets[ counter ].predictions.length; counter_inner++ ) {
                                if ( parseInt( this.quotes[ product ].targets[ counter ].predictions[ counter_inner ].deferral ) === parseInt( this.parameters.deferral ) ) {
                                    if ( ( this.quotes[ product ].targets[ counter ].predictions ) && ( this.quotes[ product ].targets[ counter ].guaranteed ) ) {
                                        response.push(
                                            {
                                                amount: this.quotes[ product ].targets[ counter ].predictions[ counter_inner ],
                                                amount_guaranteed: this.quotes[ product ].targets[ counter ].guaranteed[ counter_inner ],
                                                product: this.quotes[ product ].product,
                                                carrier: this.quotes[ product ].carrier,
                                                source: this.quotes[ product ].targets[ counter ]
                                            }
                                        )
                                    } else {
                                        console.log( 'error' );
                                        break;
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }

                switch ( this.selections.method ) {
                    case 'premium' :
                        response.sort( ( left, right ) => {
                            return ( ( left.amount.income > right.amount.income ) ? -1 : ( ( left.amount.income === right.amount.income ) ? 0 : 1 ) );
                        } );
                        break;

                    case 'income' :
                        response.sort( ( left, right ) => {
                            return ( ( left.amount.premium < right.amount.premium ) ? -1 : ( ( left.amount.premium === right.amount.premium ) ? 0 : 1 ) );
                        } );
                        break;
                }

                this.results = response;
            },

            get_results_dataset( show_all ) {
                if ( show_all ) {
                    return this.results;
                } else {
                    return this.results.slice( 0, 25 );
                }
            },

            set_deferral_period() {
                clearTimeout( this.timers.refresh );

                this.timers.refresh = setTimeout( () => { this.parameters.deferral = this.parameters.deferral_selected; this.sort_results(); }, 175 );
            },

            set_product( product_id, strategy_premium ) {
                console.log( 'setting product ID', product_id );

                this.selections.product_id = product_id;
                this.selections.details = 'illustration';

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

            toggle_show_all_results() {
                this.show_all = !this.show_all;
            },

            toggle_comparison() {
                this.mode = ( ( this.mode === 'comparison' ) ? 'normal' : 'comparison' );

                this.fetch_quote();
            },

            set_details_page( page ) {
                this.selections.details = page;
            }
        },
        created() {
            const client = useClientStore();

            if ( client.settings.investment ) {
                console.log( 'Client Override -- setting premium ', client.settings.investment );
                this.parameters.premium = client.settings.investment;
            }

            if ( client.settings.owner_state ) {
                console.log( 'Client Override -- setting owner state to ', client.settings.owner_state );
                this.parameters.state = client.settings.owner_state;
            }

            this.$emitter.on( 'set_product_id', this.replace_product, null );
        },
        data() {
            return {
                errors: null,
                quotes: null,
                loading: false,
                show_all: false,
                mode: 'normal',
                timers: {
                    replace: null,
                    refresh: null
                },
                results: [],
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
                    comparison: []
                },
                parameters: {
                    searched: false,
                    premium: '100000',
                    income: '',
                    deferral: 10,
                    deferral_selected: 10,
                    method: 'premium',
                    state: 'FL',
                    index: [],
                    carrier: [],
                    strategy_type: [],
                    strategy_configuration: [],
                    calculation_frequency: [],
                    crediting_frequency: [],
                    participation_rate: '100',
                    guarantee_period_years: 1,
                    guarantee_period_months: 0
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
            <label for="income-solver__compare-toggle" class="income-solver__compare-toggler" v-if="selections.comparison.length" v-on:click="toggle_comparison">
                <i class="fal fa-list-timeline" aria-hidden="true"></i>
            </label>

            <input type="checkbox" id="income-solver__parameters-toggle" class="income-solver__parameters-toggle" value="1">
            <label for="income-solver__parameters-toggle" class="income-solver__parameters-toggler">
                <i class="fal fa-filter-list" aria-hidden="true"></i>
            </label>

            <aside class="income-solver__parameters form">
                <fieldset data-filter-type="income">
                    <legend>Income Method</legend>

                    <div class="form__row">
                        <div class="form__column">
                            <label>Solve for...</label>
                            <div class="form__buttons">
                                <div class="form__buttons-column">
                                    <input type="radio" id="parameters__method-income" name="method" value="income" v-model="parameters.method">
                                    <label for="parameters__method-income">Income</label>
                                </div>
                                <div class="form__buttons-column">
                                    <input type="radio" id="parameters__method-premium" name="method" value="premium" v-model="parameters.method">
                                    <label for="parameters__method-premium">Premium</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__column" v-if="parameters.method === 'premium'">
                            <label>Desired Premium</label>
                            <input type="text" id="parameters__premium" name="premium" class="money" placeholder="i.e., $100,000" v-model.lazy="parameters.premium" v-money="options.money">
                        </div>
                        <div class="form__column" v-if="parameters.method === 'income'">
                            <label>Desired Annual Income</label>
                            <input type="text" id="parameters__premium" name="income" class="money" placeholder="i.e., $1,000.00" v-model.lazy="parameters.income" v-money="options.money">
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Filtering Options</legend>

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
                </fieldset>

                <div class="form__row">
                    <div class="form__column">
                        <button type="submit" class="form__action" v-on:click="fetch_quote">Fetch Quotes</button>
                    </div>
                </div>
            </aside>

            <div class="income-solver__results">
                <div class="strategy" v-if="selections.product_id">
                    <div class="strategy__inner">
                        <h3>Strategy Details<a href="javascript:" v-on:click="close_details"><i class="fal fa-close" aria-hidden="true"></i> <span>Close</span></a></h3>

                        <menu class="strategy__menu">
                            <li class="strategy__menu-item" data-type="options" v-on:click="set_details_page( 'options' )">
                                <span>Options</span>
                            </li>
                            <li class="strategy__menu-item" data-type="carrier" v-on:click="set_details_page( 'carrier' )">
                                <span>Carrier</span>
                            </li>
                            <li class="strategy__menu-item" data-type="illustration" v-on:click="set_details_page( 'illustration' )">
                                <span>Illustration</span>
                            </li>
                            <li class="strategy__menu-item" data-type="withdrawals" v-on:click="set_details_page( 'withdrawals' )">
                                <span>Withdrawals</span>
                            </li>
                            <li class="strategy__menu-item" data-type="rules" v-on:click="set_details_page( 'rules' )">
                                <span>Rules</span>
                            </li>
                        </menu>

                        <div class="strategy__details">
                            <div class="strategy__details-options" v-if="selections.details === 'options'">
                                <Infobox_Options v-bind:profile="selections.product.options" />
                            </div>
                            <div class="strategy__details-options" v-if="selections.details === 'carrier'">
                                <Infobox_Carrier v-bind:profile="selections.product.carrier_product" />
                            </div>
                            <div class="strategy__details-illustration" v-if="selections.details === 'illustration'">
                                <div class="strategy__details-illustration-notice" v-if="!selections.illustration.dataset && !selections.illustration.message">
                                    <div class="strategy__details-illustration-notice-loader">
                                        <i class="fal fa-folder-magnifying-glass" aria-hidden="true"></i> Fetching Illustration...
                                    </div>
                                    <div class="strategy__details-illustration-notice-text">
                                        This could take a minute...
                                    </div>
                                </div>
                                <div class="alert alert-error" v-if="!selections.illustration.dataset && selections.illustration.message">
                                    <h3>An error has occurred!</h3>
                                    <p>Please close this window and try again.</p>
                                    <p><small>Reason: {{ selections.illustration.message }}</small></p>
                                </div>

                                <div class="strategy__details-illustration-table" v-if="selections.illustration.valid">
                                    <Infobox_Illustration v-bind:profile="selections.illustration.dataset" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="income-solver__results-controls" v-if="results">
                    <div class="form__row">
                        <div class="form__column form__column--full">
                            <label for="deferral">Defer Income for <strong>{{ parameters.deferral_selected }} years</strong></label>
                            <input type="range" name="deferral" step="1" min="5" max="20" v-on:change="set_deferral_period" v-model="parameters.deferral_selected">
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

                <div class="alert alert-info" v-if="mode == 'comparison'">
                    <h3>Comparison mode</h3>
                </div>

                <div class="alert alert-error" v-if="errors">
                    <h3>An error has occurred!</h3>
                    <template v-for="( error, error_index ) in errors" v-bind:key="error_index">
                        <p>{{ error.message }}</p>
                        <p><small>{{ error.code }}</small></p>
                    </template>
                </div>

                <div class="alert alert-info" v-if="parameters.searched && !loading && !results.length">
                    <h3>No products found!</h3>
                    <p>Try adjusting your search criteria, and try again.</p>
                </div>

                <div class="alert alert-info" v-if="!parameters.searched && !loading && !results.length">
                    <h3>Begin your search</h3>
                    <p>Adjust any search parameters you like, and click <strong>Fetch Quotes</strong> to get started.</p>
                </div>

                <div class="results" v-bind:class="{ 'results--inactive': selections.product_id }">
                    <div class="results__inner">
                        <template v-if="results" v-for="( result, result_index ) in get_results_dataset( show_all )" v-bind:key="result_index">
                            <div class="result result__card" v-bind:class="{ 'result--faded': parameters.deferral != parameters.deferral_selected }">
                                <h4 class="result__card-title" v-bind:data-product-id="result.product.id" v-bind:data-carrier-slug="this.$globalUtils.sanitize_title( result.carrier.name )">
                                    <div class="result__card-title-text">
                                        <strong>{{ result.product.name }}</strong>
                                        <!--{{ result.carrier.name }}-->
                                        <span v-html="this.$productUtils.generate_iao_name( result )"></span>

                                        <!--{{ result.source.product_analysis_data_id }}-->
                                        <div class="result__card-title-comparison">
                                            <input type="checkbox" v-bind:id="'product_' + result.source.product_analysis_data_id" v-bind:value="result.source.product_analysis_data_id" v-model="selections.comparison">
                                            <label v-bind:for="'product_' + result.source.product_analysis_data_id">Add to Compare</label>
                                        </div>
                                    </div>
                                    <template v-if="result.carrier.ratings">
                                        <div class="result__card-title-ratings">
                                            <span v-for="( rating, rating_index ) in result.carrier.ratings" class="result__card-title-ratings-rating" v-bind:index="rating_index" v-bind:data-rating-company="rating.company" v-bind:data-rating-value="rating.rating">{{ rating.rating }}</span>
                                        </div>
                                    </template>
                                </h4>
                                <div class="result__card-strategies">
                                    <div class="result__card-strategy" v-on:click="set_product( result.source.product_analysis_data_id, result.amount.premium )">
                                        <div class="result__card-strategy-meta">
                                            <span data-type="id" title="CANNEX Analysis ID">{{ result.source.product_analysis_data_id }}</span>
                                            <span data-type="id" title="CANNEX Product ID">{{ result.product.id }}</span>
                                        </div>
                                        <div class="result__card-strategy-income">
                                            <template v-if="selections.method == 'premium'">
                                                <div class="result__card-strategy-income-money" data-period="annually" data-method="premium" data-type="guaranteed">{{ this.$financeUtils.format_currency( result.amount_guaranteed.income, 'USD' ) }}</div>
                                                <div class="result__card-strategy-income-money" data-period="annually" data-method="premium" data-type="hypothetical">{{ this.$financeUtils.format_currency( result.amount.income, 'USD' ) }}</div>
                                            </template>
                                            <template v-if="selections.method == 'income'">
                                                <div class="result__card-strategy-income-money" data-period="annually" data-method="income" data-type="hypothetical">{{ this.$financeUtils.format_currency( result.amount.premium, 'USD' ) }}</div>
                                                <div class="result__card-strategy-income-money" data-period="annually" data-method="income" data-type="income">{{ this.$financeUtils.format_currency( result.amount.income, 'USD' ) }}</div>
                                            </template>
                                        </div>
                                        <div class="result__card-strategy-data">
                                            <div class="result__card-strategy-data-points">
                                                <span class="result__card-strategy-data-point" data-type="rate-percent" data-type-title="Roll-up Rate">
                                                    <template v-for="( roll_up, roll_up_index ) in result.source.income_benefit.roll_up" v-bind:key="roll_up_index" v-if="result.source.income_benefit.roll_up.length">
                                                        <span><em>Tier {{ roll_up.tier_no }}</em> {{ roll_up.rate }}%</span>
                                                    </template>
                                                    <template v-if="!result.source.income_benefit.roll_up.length">n/a</template>
                                                </span>
                                                <span class="result__card-strategy-data-point" data-type="rate-percent" data-type-title="Annual Rider Fee">
                                                    <template v-for="( fee, fee_index ) in result.source.income_benefit.rider_fee_current" v-bind:key="fee_index" v-if="result.source.income_benefit.rider_fee_current.length">
                                                        <span><em>Tier {{ fee.tier_no }}</em> {{ fee.rate }}%</span>
                                                    </template>
                                                    <template v-if="!result.source.income_benefit.rider_fee_current.length">None</template>
                                                </span>
                                                <span class="result__card-strategy-data-point" data-type="rate-percent" data-type-title="Premium Bonus">
                                                    <template v-for="( bonus, bonus_index ) in result.source.income_benefit.premium_bonus" v-bind:key="bonus_index" v-if="result.source.income_benefit.premium_bonus.length">
                                                        <span><em>Tier {{ bonus.tier_no }}</em> {{ bonus.rate }}%</span>
                                                    </template>
                                                    <template v-if="!result.source.income_benefit.premium_bonus.length">None</template>
                                                </span>
                                                <span class="result__card-strategy-data-point" data-type="rate-percent" data-type-title="Premium Multiplier">
                                                    <template v-for="( multiplier, multiplier_index ) in result.source.income_benefit.premium_multiplier" v-bind:key="multiplier_index" v-if="result.source.income_benefit.premium_multiplier.length">
                                                        <span><em>Tier {{ multiplier.tier_no }}</em> {{ multiplier.rate }}%</span>
                                                    </template>
                                                    <template v-if="!result.source.income_benefit.premium_multiplier.length">None</template>
                                                </span>
                                                <span class="result__card-strategy-data-point" data-type="setting" data-type-title="Min. Income Start Age">
                                                    <template v-for="( age, age_index ) in result.source.income_benefit.income_start_age" v-bind:key="age_index" v-if="result.source.income_benefit.income_start_age.length">
                                                        <span>{{ age.min_years }} years old</span>
                                                    </template>
                                                    <template v-if="!result.source.income_benefit.income_start_age.length">None</template>
                                                </span>
                                                <span class="result__card-strategy-data-point" data-type="setting" data-type-title="Rider Type/Name">{{ result.source.income_benefit.name }}</span>
                                            </div>
                                        </div>
                                        <div class="result__card-strategy-data result__card-strategy-data--extra">
                                            <div class="result__card-strategy-details-points result__card-strategy-details-points--vertical">
                                                <span class="result__card-strategy-details-point" data-type="index" data-type-title="Index">{{ this.$globalUtils.format( 'index', result.source.strategy.index_id ) }}</span>
                                                <span class="result__card-strategy-details-point" data-type="setting" data-type-title="Type">{{ this.$globalUtils.format( 'strategy_type', result.source.strategy.strategy_type ) }}</span>
                                                <span class="result__card-strategy-details-point" data-type="setting" data-type-title="Configuration">{{ this.$globalUtils.format( 'strategy_configuration', result.source.strategy.strategy_configuration ) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template v-if="( ( !this.show_all ) && ( ( this.results ) && ( this.results.length > 25 ) ) )">
                            <div class="form result__notice">
                                <p>Only the top 25 results are shown.</p>
                                <button type="button" class="form__action" v-on:click="toggle_show_all_results">Show All</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>