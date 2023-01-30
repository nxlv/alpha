<script>
    import { RouterLink } from 'vue-router';
    import axios from 'axios';

    export default {
        components: {
            RouterLink
        },
        methods: {
            async fetch_quote() {
                this.loading = true;

                let endpoint = '/api/quoting/get/immediate';
                let request = await axios.post( '' + endpoint, this.parameters );

                this.errors = null;
                this.quotes = null;
                this.loading = false;

                if ( ( request ) && ( request.data ) ) {
                    if ( ( request.data.result.error ) && ( request.data.result.error.error_level.length ) ) {
                        this.errors = [
                            { 'code': request.data.result.error.error_cd, 'message': request.data.result.error.error_message }
                        ]
                    } else {
                        this.quotes = request.data.result;
                    }
                }
            }
        },
        data() {
            return {
                errors: null,
                quotes: null,
                loading: false,
                parameters: {
                    deferral: 10
                }
            };
        }
    };
</script>
<template>
    <section class="immediate">
        <aside class="form immediate__parameters">
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
                                <input type="radio" id="parameters__frequency-annually" name="frequency" value="A" v-model="parameters.frequency">
                                <label for="parameters__frequency-annually">Yearly</label>
                            </div>
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__frequency-monthly" name="frequency" value="M" v-model="parameters.frequency">
                                <label for="parameters__frequency-monthly">Monthly</label>
                            </div>
                        </div>
                    </div>

                    <div class="form__column form__column--full">
                        <label for="deferral">Defer Income for <strong>{{ parameters.deferral }} years</strong></label>
                        <input type="range" name="deferral" step="1" min="5" max="20" v-model="parameters.deferral">
                    </div>
                </div>

                <div class="form__row">
                    <div class="form__column">
                        <label><abbr title="Before Income Start Date">Return of Premium</abbr></label>
                        <div class="form__buttons">
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__rop-gt" name="return_of_premium" value="GT" v-model="parameters.return_of_premium">
                                <label for="parameters__rop-gt">No</label>
                            </div>
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__rop-rp" name="return_of_premium" value="RP" v-model="parameters.return_of_premium">
                                <label for="parameters__rop-rp">Yes</label>
                            </div>
                        </div>
                    </div>

                    <div class="form__column">
                        <label>Index Type</label>
                        <div class="form__buttons">
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__index-type-n" name="index_type" value="N" v-model="parameters.index_type">
                                <label for="parameters__index-type-n">No</label>
                            </div>
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__index-type-ac" name="index_type" value="AC" v-model="parameters.index_type">
                                <label for="parameters__index-type-ac">COLA <small>Annual Compound</small></label>
                            </div>
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__index-type-cu" name="index_type" value="CU" v-model="parameters.index_type">
                                <label for="parameters__index-type-cu">CPI-U</label>
                            </div>
                        </div>
                    </div>

                    <div class="form__column">
                        <label>Fund Type</label>
                        <div class="form__buttons">
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__fund-type-cd-n" name="fund_type" value="N" v-model="parameters.fund_type">
                                <label for="parameters__fund-type-cd-n">Non-reducing</label>
                            </div>
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__fund-type-cd-f" name="fund_type" value="F" v-model="parameters.fund_type">
                                <label for="parameters__fund-type-cd-f">Reducing <small>on first death</small></label>
                            </div>
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__fund-type-cd-p" name="fund_type" value="P" v-model="parameters.fund_type">
                                <label for="parameters__fund-type-cd-p">Reducing <small>on death of Primary</small></label>
                            </div>
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__fund-type-cd-s" name="fund_type" value="S" v-model="parameters.fund_type">
                                <label for="parameters__fund-type-cd-s">Reducing <small>on death of Secondary</small></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form__row">
                    <div class="form__column">
                        <button type="submit" class="form__action" v-on:click="fetch_quote">Get Quotes</button>
                    </div>
                </div>
            </fieldset>
        </aside>

        <div class="immediate__results">
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

            <div v-if="quotes" v-for="( quote, quote_index ) in quotes" v-bind:key="quote_index" class="result result__immediate">
                <figure class="result__immediate-logo" v-bind:data-carrier-name="quote.institution_name">
                    <img src="https://placehold.jp/180x180.png">
                </figure>
                <div class="result__immediate-details">
                    <h4>{{ quote.institution_name }}</h4>
                    <h5>{{ quote.quote.product_name }}</h5>

                    <div class="result__immediate-details-wrapper" v-if="quote.quote">
                        <div class="result__immediate-details-financials" v-if="quote.quote.illustration_id" v-bind:data-method="parameters.method">
                            <div class="result__immediate-details-financials-item" data-type="premium">
                                ${{ quote.quote.premium }}
                            </div>
                            <div class="result__immediate-details-financials-item" data-type="income" v-bind:data-frequency="parameters.frequency">
                                ${{ quote.quote.income }}
                            </div>
                            <div class="result__immediate-details-financials-item" data-type="tax">
                                ${{ quote.quote.tax_amount }}
                            </div>
                        </div>

                        <menu class="result__immediate-details-actions">
                            <a href="javascript:;" class="result__immediate-details-actions-link" v-bind:data-illustration="quote.quote.illustration_id">
                                <i class="fal fa-file-pdf" aria-hidden="true"></i> Illustration
                            </a>
                            <a href="javascript:;" class="result__immediate-details-actions-link">
                                <i class="fal fa-file-pdf" aria-hidden="true"></i> Brochure
                            </a>
                            <a href="javascript:;" class="result__immediate-details-actions-link">
                                <i class="fal fa-file-pdf" aria-hidden="true"></i> Fact Sheet
                            </a>
                        </menu>
                    </div>

                    <div class="result__immediate-details-notes" v-if="quote.quote.notes && quote.quote.notes.note && quote.quote.notes.note.length">
                        <h6>Notes:</h6>

                        <ul>
                            <li v-for="( note, note_index ) in quote.quote.notes.note" v-bind:key="note_index">
                                <p v-html="note._" />
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>