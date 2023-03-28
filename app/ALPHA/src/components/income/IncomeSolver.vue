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
                let request = await axios.post( 'http://platform.alpha.universe.local' + endpoint, this.parameters );

                this.errors = null;
                this.quotes = null;
                this.loading = false;

                if ( ( request ) && ( request.data ) ) {
                    this.quotes = request.data.products;
                }
            },

            get_product_part( product_id, part ) {
                let response = '';
                let product;

                const sets = useSetsStore();

                for ( let counter = 0; counter < sets.sets.products.length; counter++ ) {
                    if ( sets.sets.products[ counter ].product_id === product_id ) {
                        product = sets.sets.products[ counter ];
                        break;
                    }
                }

                if ( ( product ) && ( product.basic ) ) {
                    switch ( part.toLowerCase() ) {
                        case 'carrier' :
                            if ( product.basic.carrier ) {
                                response = product.basic.carrier.name;
                            }
                            break;

                        case 'name' :
                            response = product.basic.name;
                            break;

                        default :
                            if ( product[ part ] ) {
                                response = product[ part ];
                            }
                            break;
                    }
                }

                return response;
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

            // TODO: Replace with shared generalized method
            get_indexes() {
                const sets = useSetsStore();

                if ( sets.sets.indexes ) {
                    return sets.sets.indexes;
                }

                return [];
            }
        },
        data() {
            return {
                errors: null,
                quotes: null,
                loading: false,
                parameters: {
                    premium: '100000',
                    income: '',
                    deferral: 10,
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
                            <label for="deferral">Defer Income for <strong>{{ parameters.deferral }} years</strong></label>
                            <input type="range" name="deferral" step="1" min="5" max="35" v-model="parameters.deferral">
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

                <div class="alert alert-info" v-if="!loading && !quotes">
                    <h3>No products found!</h3>
                    <p>Try adjusting your search criteria, and try again.</p>
                </div>

                <div class="results">
                    <div class="results__inner">
                        <!-- template v-for -->
                        <template v-if="quotes" v-for="( quote, quote_index ) in quotes" v-bind:key="quote_index">
                            <template v-if="quote.targets.length" v-for="( strategy, strategy_index ) in quote.targets" v-bind:key="strategy_index">
                                <div class="result result__fixed">
                                    <h4>
                                        <strong>{{ quote.product.name }}</strong>
                                        {{ quote.carrier.name }}
                                    </h4>

                                    <div class="result__grid">
                                        <div class="result__grid-cta">
                                            <div class="result__grid-cta-financials">
                                                <span class="money">{{ get_deferred_income( strategy ) }}</span>
                                                <span><strong>income per year</strong></span>

                                                <!--
                                                <span class="money">{{ quote.analysis_request.premium }}</span>
                                                <span><strong>required premium</strong> to return $[INCOME] <strong>per month</strong></span>
                                                -->
                                            </div>
                                        </div>
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