<script>
    import { RouterLink } from 'vue-router';
    import { useSetsStore } from '@/stores/sets';
    import axios from 'axios';

    export default {
        components: {
            RouterLink
        },
        methods: {
            async fetch_quote() {
                this.loading = true;

                let endpoint = '/api/quoting/get/fixed';
                let request = await axios.post( import.meta.env.VITE_API_BASE_URL + endpoint, this.parameters );

                this.errors = null;
                this.quotes = null;
                this.loading = false;

                this.parameters.searched = true;

                if ( ( request ) && ( request.data ) ) {
                    this.quotes = request.data.products;
                }
            },

            get_deferred_income( strategy ) {
                let income = 0.00;

                if ( ( strategy.predictions ) && ( strategy.predictions.length ) ) {
                    console.log( strategy.product_analysis_data_id, strategy.predictions );

                    for ( let counter = 0; counter < strategy.predictions.length; counter++ ) {
                        if ( parseInt( strategy.predictions[ counter ].deferral ) === parseInt( this.parameters.deferral ) ) {
                            income = strategy.predictions[ counter ].income;
                            break;
                        }
                    }
                }

                return parseFloat( income ).toFixed( 2 );
            },

            set_deferral_period() {
                clearTimeout( this.timers.refresh );

                this.timers.refresh = setTimeout( () => { this.parameters.deferral = this.parameters.deferral_selected; }, 175 );
            },

            // TODO: Replace with shared generalized method
            format_currency( value, currency ) {
                let engine = new Intl.NumberFormat(
                    'en-US',
                    {
                        style: 'currency',
                        currency: currency
                    }
                );

                return engine.format( value );
            },

            get_indexes() {
                const sets = useSetsStore();

                if ( sets.sets.indexes ) {
                    return sets.sets.indexes;
                }

                return [];
            },

            // TODO: integrate these datasets into the initial loading process
            format( type, value ) {
                let dataset = {};

                switch ( type ) {
                    case 'states' :
                        if ( value.length ) {
                            let states = value.split( ',' );

                            if ( states.length > 10 ) {
                                return states.slice( 0, 9 ).join( ', ' ) + ' and ' + ( states.length - 10 ) + ' more';
                            }
                        }
                        break;

                    case 'index' :
                        const sets = useSetsStore();

                        for ( let counter = 0; counter < sets.sets.indexes.length; counter++ ) {
                            if ( sets.sets.indexes[ counter ].index_id === value ) {
                                return sets.sets.indexes[ counter ].index_name;
                            }
                        }
                        break;

                    case 'strategy_type' :
                        dataset = { 'AL': 'Allocated', 'AV': 'Average', 'FX': 'Fixed', 'IT': 'Inverse Performance Triggered', 'LA': 'Layered', 'PP': 'Point-to-Point', 'PT': 'Performance Triggered', 'SU': 'Sum' };
                        break;

                    case 'strategy_configuration' :
                        dataset = { '01': 'Fixed', '02': 'Declared + Participation', '03': 'Cap + Participation', '04': 'Spread + Participation', '05': 'Participation Only', '08': 'Replacement + Participation', '99': 'Parent or Sub-strategy Only' };
                        break;

                    case 'frequency' :
                        dataset = { 'A': 'Annually', 'M': 'Monthly', 'D': 'Daily', '2Y': '2-year', '3Y': '3-year', '5Y': '5-year', '7Y': '7-year', '10': '10-year' };
                        break;

                    case 'yesno' :
                        dataset = { 'Y': 'Yes', 'N': 'No' };
                        break;
                }

                return dataset[ value ] || value;
            }
        },
        data() {
            return {
                errors: null,
                quotes: null,
                loading: false,
                timers: {
                    refresh: null
                },
                parameters: {
                    searched: false,
                    premium: '100000',
                    income: '',
                    deferral: 10,
                    deferral_selected: 10,
                    method: 'premium',
                    state: 'FL',
                    index: 'A000001X',
                    strategy_type: 'PP',
                    strategy_configuration: '03',
                    calculation_frequency: 'A',
                    crediting_frequency: 'A',
                    participation_rate: '100',
                    guarantee_period_years: 1,
                    guarantee_period_months: 0
                }
            };
        }
    };
</script>
<template>
    <section class="income-solver">
        <div class="income-solver__content">
            <aside class="income-solver__parameters form">
                <fieldset>
                    <legend>Search Parameters</legend>

                    <!--
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
                    -->

                    <div class="form__row">
                        <div class="form__column">
                            <button type="submit" class="form__action" v-on:click="fetch_quote">Fetch Quotes</button>
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__column" v-if="parameters.method === 'premium'">
                            <label>Premium</label>
                            <input type="text" id="parameters__premium" name="premium" class="money" placeholder="i.e., $100,000" v-model="parameters.premium">
                        </div>
                        <div class="form__column" v-if="parameters.method === 'income'">
                            <label>Desired Income</label>
                            <input type="text" id="parameters__premium" name="income" class="money" placeholder="i.e., $1,000.00" v-model="parameters.income">
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__column">
                            <label for="parameters__state">Owner State</label>

                            <select id="parameters__state" name="state" v-model="parameters.state">
                                <option value="FL">(FL) Florida</option>
                            </select>
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__column">
                            <label for="indexes">Index(es)</label>
                            <select name="indexes" id="indexes" v-model="parameters.index">
                                <option v-for="( index, index_counter ) in get_indexes()" v-bind:value="index.index_id">{{ index.index_name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__column">
                            <label for="strategy_type">Strategy Type</label>
                            <select name="strategy_type" id="strategy_type" v-model="parameters.strategy_type">
                                <option value="AL">Allocated</option>
                                <option value="AV">Average</option>
                                <option value="FX">Fixed</option>
                                <option value="IT">Inverse Performance Triggered</option>
                                <option value="LA">Layered</option>
                                <option value="PP">Point-to-Point</option>
                                <option value="PT">Performance Triggered</option>
                                <option value="SU">Sum</option>
                            </select>
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__column">
                            <label for="strategy_configuration">Strategy Configuration</label>
                            <select name="strategy_configuration" id="strategy_configuration" v-model="parameters.strategy_configuration">
                                <option value="01">Fixed</option>
                                <option value="02">Declared + Participation</option>
                                <option value="03">Cap + Participation</option>
                                <option value="04">Spread + Participation</option>
                                <option value="05">Participation Only</option>
                                <option value="08">Replacement + Participation</option>
                                <option value="99">Parent or Sub-strategy Only</option>
                            </select>
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__column">
                            <label for="calculation_frequency">Calculation Frequency</label>
                            <select name="calculation_frequency" id="calculation_frequency" v-model="parameters.calculation_frequency">
                                <option value="A">Annually</option>
                                <option value="M">Monthly</option>
                                <option value="D">Daily</option>
                                <option value="2Y">2-year</option>
                                <option value="3Y">3-year</option>
                                <option value="5Y">5-year</option>
                                <option value="7Y">7-year</option>
                                <option value="10">10-year</option>
                            </select>
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__column">
                            <label for="crediting_frequency">Crediting Frequency</label>
                            <select name="crediting_frequency" id="crediting_frequency" v-model="parameters.crediting_frequency">
                                <option value="A">Annually</option>
                                <option value="M">Monthly</option>
                                <option value="D">Daily</option>
                                <option value="2Y">2-year</option>
                                <option value="3Y">3-year</option>
                                <option value="5Y">5-year</option>
                                <option value="7Y">7-year</option>
                                <option value="10">10-year</option>
                            </select>
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__column">
                            <label for="participation_rate">Participation Rate (%)</label>
                            <input type="number" name="participation_rate" id="participation_rate" v-model="parameters.participation_rate">
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__column">
                            <label for="guarantee_period_years">Guarantee Years</label>
                            <input type="number" name="guarantee_period_years" id="guarantee_period_years" v-model="parameters.guarantee_period_years">
                        </div>

                        <div class="form__column">
                            <label for="guarantee_period_months">Guarantee Months</label>
                            <input type="number" name="guarantee_period_months" id="guarantee_period_months" v-model="parameters.guarantee_period_months">
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__column">
                            <button type="submit" class="form__action" v-on:click="fetch_quote">Fetch Quotes</button>
                        </div>
                    </div>
                </fieldset>
            </aside>

            <div class="income-solver__results">
                <!--
                <fieldset>
                    <legend>Filter Parameters</legend>

                    <div class="form__row">
                        <div class="form__column">
                            <label>Carrier or Product Name</label>
                            <input type="text" id="filtering__name" name="name" value="" placeholder="i.e., Allianz">
                        </div>

                        <div class="form__column">
                            <label>Rating (A.M. Best)</label>
                            <select name="filtering__rating">
                                <option value="A">A</option>
                                <option value="A+">A+</option>
                                <option value="A++">A++</option>
                            </select>
                        </div>

                        <div class="form__column">
                            <label>Premium Bonus</label>
                            <select name="filtering__premium-bonus">
                                <option value="true">Has Premium Bonus</option>
                                <option value="false">No Premium Bonus</option>
                                <option value="5%">&gt;= 5%</option>
                                <option value="10%">&gt;= 10%</option>
                            </select>
                        </div>

                        <div class="form__column">
                            <label>Income Base Bonus</label>
                            <select name="filtering__base-bonus">
                                <option value="true">Has Income Base Bonus</option>
                                <option value="false">No Income Base Bonus</option>
                                <option value="5%">&gt;= 5%</option>
                                <option value="10%">&gt;= 10%</option>
                            </select>
                        </div>

                        <div class="form__column">
                            <label>Additional Premium</label>
                            <select name="filtering__premium-additional">
                                <option value="single">Single Premium</option>
                                <option value="life">Product Life</option>
                                <option value="first">First Year (or Less)</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
                -->
                <div class="income-solver__results-controls" v-if="quotes">
                    <div class="form__row">
                        <div class="form__column form__column--full">
                            <label for="deferral">Defer Income for <strong>{{ parameters.deferral_selected }} years</strong></label>
                            <input type="range" name="deferral" step="1" min="5" max="35" v-on:change="set_deferral_period" v-model="parameters.deferral_selected">
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

                <div class="alert alert-info" v-if="!parameters.searched && !loading && !quotes">
                    <h3>Begin your search</h3>
                    <p>Adjust any search parameters you like, and click <strong>Fetch Quotes</strong> to get started.</p>
                </div>

                <div class="results">
                    <div class="results__inner">
                        <!-- template v-for -->
                        <template v-if="quotes" v-for="( quote, quote_index ) in quotes" v-bind:key="quote_index">
                            <template v-if="quote.targets.length">
                                <div class="result result__card" v-bind:class="{ 'transient': parameters.deferral != parameters.deferral_selected }">
                                    <h4 class="result__card-title" v-bind:data-product-id="quote.product.id">
                                        <strong>{{ quote.product.name }}</strong>
                                        {{ quote.carrier.name }}
                                    </h4>
                                    <div class="result__card-strategies">
                                        <template v-if="quote.targets.length" v-for="( target, target_index ) in quote.targets" v-bind:key="target_index">
                                            <div class="result__card-strategy">
                                                <div class="result__card-strategy-income">
                                                    <div class="result__card-strategy-income-money" data-period="annually">{{ format_currency( get_deferred_income( target ), 'USD' ) }}</div>
                                                    <div class="result__card-strategy-income-money" data-period="monthly">{{ format_currency( ( get_deferred_income( target ) / 12 ), 'USD' ) }}</div>
                                                </div>
                                                <div class="result__card-strategy-details">
                                                    <div class="result__card-strategy-details-points">
                                                        <span class="result__card-strategy-details-point" data-type="index" data-type-title="Index">{{ format( 'index', target.strategy.index_id ) }}</span>
                                                        <span class="result__card-strategy-details-point" data-type="setting" data-type-title="Type">{{ format( 'strategy_type', target.strategy.strategy_type ) }}</span>
                                                        <span class="result__card-strategy-details-point" data-type="setting" data-type-title="Configuration">{{ format( 'strategy_configuration', target.strategy.strategy_configuration ) }}</span>
                                                        <span class="result__card-strategy-details-point" data-type="rate-percent" data-type-title="Current Participation Rate">{{ target.current_participation_rate }}%</span>
                                                        <span class="result__card-strategy-details-point" data-type="frequency" data-type-title="Calculation Frequency">{{ format( 'frequency', target.strategy.calculation_frequency ) }}</span>
                                                        <span class="result__card-strategy-details-point" data-type="frequency" data-type-title="Crediting Frequency">{{ format( 'frequency', target.strategy.crediting_frequency ) }}</span>
                                                        <span class="result__card-strategy-details-point" data-type="boolean" data-type-title="Guarantee Status">{{ format( 'yesno', target.strategy.guarantee_status ) }}</span>
                                                        <span class="result__card-strategy-details-point" data-type="time" data-type-title="Guarantee Period">{{ target.strategy.guarantee_period_years }} <small>years</small> {{ target.strategy.guarantee_period_months }} <small>months</small></span>
                                                    </div>
                                                    <div class="result__card-strategy-details-rules">
                                                        <span class="result__card-strategy-details-rule" data-type="location" data-type-title="State(s)">{{ ( ( target.rules.valid_states.length ) ? format( 'states', target.rules.valid_states ) : 'Any/All' ) }}</span>
                                                        <span class="result__card-strategy-details-rule" data-type="minmax" data-type-title="Premium Limits">{{ format_currency( target.premium_range_min, 'USD' ) }}&mdash;{{ format_currency( target.premium_range_max, 'USD' ) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <!-- /template v-for -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>