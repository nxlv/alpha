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
                let request = await axios.post( '' + endpoint, this.parameters );

                this.errors = null;
                this.quotes = null;
                this.profiles = null;
                this.loading = false;

                if ( ( request ) && ( request.data ) ) {
                    if ( ( request.data.result.error ) && ( request.data.result.error.error_level.length ) ) {
                        this.errors = [
                            { 'code': request.data.result.error.error_cd, 'message': request.data.result.error.error_message }
                        ]
                    } else {
                        this.profiles = request.data.profiles;
                        this.quotes = request.data.result;
                    }
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
            }
        },
        data() {
            return {
                errors: null,
                quotes: null,
                profiles: null,
                loading: false,
                parameters: {
                    deferral: 10
                }
            };
        }
    };
</script>
<template>
    <section class="income-solver">
        <aside class="form">
            <fieldset>
                <legend>Search Parameters</legend>

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

                    <div class="form__column" v-if="parameters.method === 'premium'">
                        <label>Premium</label>
                        <input type="text" id="parameters__premium" name="premium" class="money" placeholder="i.e., $100,000" v-model="parameters.premium">
                    </div>
                    <div class="form__column" v-if="parameters.method === 'income'">
                        <label>Desired Income</label>
                        <input type="text" id="parameters__premium" name="income" class="money" placeholder="i.e., $1,000.00" v-model="parameters.income">
                    </div>

                    <div class="form__column">
                        <label>Withdrawal Frequency</label>
                        <div class="form__buttons">
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__frequency-annually" name="frequency" value="annually" v-model="parameters.frequency">
                                <label for="parameters__frequency-annually">Yearly</label>
                            </div>
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__frequency-monthly" name="frequency" value="monthly" v-model="parameters.frequency">
                                <label for="parameters__frequency-monthly">Monthly</label>
                            </div>
                        </div>
                    </div>

                    <div class="form__column form__column--full">
                        <label for="deferral">Defer Income for <strong>{{ parameters.deferral }} years</strong></label>
                        <input type="range" name="deferral" step="1" min="5" max="20" v-model="parameters.deferral">
                    </div>
                </div>
            </fieldset>

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

                <div class="form__row">
                    <div class="form__column">
                        <button type="submit" class="form__action" v-on:click="fetch_quote">Get Quotes</button>
                    </div>
                </div>
            </fieldset>
        </aside>

        <div class="income-solver__results" >
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

            <div class="results">
                <div class="results__inner">
                    <!-- template v-for -->
                    <div v-if="quotes" v-for="( quote, quote_index ) in quotes" v-bind:key="quote_index" class="result result__fixed">
                        <template v-if="quote.analysis_data && !quote.analysis_error">
                            <h4>
                                <strong>{{ get_product_part( quote.analysis_request.product_id, 'name' ) }}</strong>
                                {{ get_product_part( quote.analysis_request.product_id, 'carrier' ) }}
                            </h4>

                            <div class="result__grid">
                                <div class="result__grid-cta">
                                    <div class="result__grid-cta-financials">
                                        <span class="money">{{ quote.analysis_request.income }}</span>
                                        <span><strong>income per year</strong></span>

                                        <!--
                                        <span class="money">{{ quote.analysis_request.premium }}</span>
                                        <span><strong>required premium</strong> to return $[INCOME] <strong>per month</strong></span>
                                        -->
                                    </div>

                                    <div class="result__grid-cta-info">
                                        <h5>Product Details</h5>

                                        <dl>
                                            <dt>Company</dt>
                                            <dd>{{ get_product_part( quote.analysis_request.product_id, 'carrier' ) }}</dd>

                                            <dt>Product</dt>
                                            <dd>{{ get_product_part( quote.analysis_request.product_id, 'name' ) }}</dd>

                                            <dt>Deferral Period</dt>
                                            <dd>{{ parameters.deferral }} years</dd>

                                            <dt>Premium</dt>
                                            <dd>{{ quote.analysis_request.premium }}</dd>

                                            <dt>Monthly Income</dt>
                                            <dd>{{ quote.analysis_request.income }}</dd>

                                            <dt>Return of Premium</dt>
                                            <dd>No</dd>
                                        </dl>
                                    </div>
                                </div>

                                <div class="result__grid-illustration">
                                    <div class="result__grid-illustration-inner">
                                        <template v-if="quote.analysis_request.num_strategies">
                                            <span class="result__grid-illustration-banner" data-type="strategies"><strong>{{ quote.analysis_request.num_strategies }}</strong> total strategies for this product.  Click to view and simulate <i class="fal fa-chevron-right" aria-hidden="true"></i></span>
                                        </template>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Year</th>
                                                    <th>Primary Age</th>
                                                    <th>Account Value</th>
                                                    <th>Death Benefit</th>
                                                    <th>Fees</th>
                                                    <th data-type="income">Income</th>
                                                    <th>Benefit Base</th>
                                                    <th>Interest Amount</th>
                                                    <th>Interest %</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="( row, row_index ) in quote.analysis_data" v-bind:key="row_index" v-bind:data-has-income="( ( row.income ) ? 'true' : 'false' )">
                                                    <td>{{ row.year }}</td>
                                                    <td>{{ row.primary_age }}</td>
                                                    <td>{{ row.account_value }}</td>
                                                    <td>{{ row.death_benefit }}</td>
                                                    <td>{{ row.fees }}</td>
                                                    <td data-type="income">{{ row.income }}</td>
                                                    <td>{{ row.income_benefit_base }}</td>
                                                    <td>{{ row.interest_amount }}</td>
                                                    <td>{{ row.interest_percent }}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template v-if="quote.analysis_error && true === false">
                            <h4>Error</h4>
                            <p><strong>{{ quote.analysis_error.error_cd }}</strong> {{ quote.analysis_error.error_message }}</p>
                        </template>
                    </div>
                    <!-- /template v-for -->
                </div>
            </div>
        </div>
    </section>
</template>