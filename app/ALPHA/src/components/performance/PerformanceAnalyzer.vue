<script>
    import { RouterLink } from 'vue-router';

    import { useSetsStore } from '@/stores/sets';
    import { useClientStore } from '@/stores/client';
    import { useInventoryStore } from "@/stores/inventory";

    import axios from 'axios';
    import { VMoney } from 'v-money';
    import Multiselect from '@vueform/multiselect';

    export default {
        components: {
            RouterLink,

            Multiselect
        },
        methods: {
            async fetch() {
                this.loading = true;

                let endpoint = '/api/indexes/reports/annuities/all';
                let request = await axios.get( ( ( import.meta.env.PROD ) ? ( '//' + window.location.host ) : import.meta.env.VITE_API_BASE_URL ) + endpoint, this.parameters );

                this.errors = null;
                this.reports = null;
                this.loading = false;

                if ( ( request ) && ( !request.data.error ) && ( request.data.result ) && ( request.data.result.reports ) ) {
                    this.reports = request.data.result.reports;

                    this.filter();
                } else {
                    this.$toast.error( 'Annuity analysis information could not be loaded.  Please try again.', { position: 'top-left' } );
                }
            },

            filter() {
                this.reports_filtered = [];

                let counter, counter_models, counter_filters, key, include_in_results;

                if ( this.reports ) {
                    for ( counter = 0; counter < this.reports.length; counter++ ) {
                        let element = {
                            name: this.reports[ counter ][ 'carrier_&_product_name' ],
                            carrier: this.reports[ counter ].carrier_issuing_entity,
                            pdf: this.reports[ counter ].pdf_url,
                            allocations: []
                        };

                        for ( counter_models = 0; counter_models < this.reports[ counter ].model_allocation.length; counter_models++ ) {
                            if ( this.reports[ counter ].model_allocation[ counter_models ].index_ticker ) {
                                // filter
                                for ( key in this.parameters ) {
                                    include_in_results = true;

                                    if ( this.parameters[ key ] ) {
                                        switch ( key ) {
                                            case 'index' :
                                                if ( ( Array.isArray( this.parameters[ key ] ) ) && ( this.parameters[ key ].length ) ) {
                                                    if ( this.parameters[ key ].indexOf( this.reports[ counter ].model_allocation[ counter_models ].index_ticker ) === -1 ) {
                                                        include_in_results = false;
                                                    }
                                                }
                                                break;
                                        }
                                    }

                                    if ( !include_in_results ) {
                                        break;
                                    }
                                }

                                if ( include_in_results ) {
                                    element.allocations.push( this.reports[ counter ].model_allocation[ counter_models ] );
                                }
                            }
                        }

                        if ( element.allocations.length ) {
                            this.reports_filtered.push( element );
                        }
                    }
                }
            },

            format_percentage( percentage ) {
                let value = parseFloat( percentage );

                return value.toFixed( 2 );
            },

            get_dataset( dataset ) {
                let counter;

                switch ( dataset ) {
                }
            }
        },

        created() {
            this.fetch();

            //this.$toast.success( 'Loading...', { position: 'top-left' } );
        },

        data() {
            return {
                errors: null,
                reports: null,
                reports_filtered: [],
                loading: false,
                parameters: {
                    index: []
                }
            };
        }
    };
</script>
<template>
    <section class="analyzer">
        <aside class="analyzer__filtering">
            <button type="submit" class="analyzer__filtering-button" v-on:click="filter">Filter <i class="fas fa-arrow-right" aria-hidden="true"></i></button>

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
                    <label for="indexes">Index</label>
                    <multiselect v-model="parameters.index"
                                 mode="multiple"
                                 label="label"
                                 track-by="label"
                                 :options="this.$globalUtils.get_set_as_kvp( 'indexes_tickers' )"
                                 :hide-selected="false"
                                 :close-on-select="false"
                                 :searchable="true"
                                 :create-option="false">
                    </multiselect>
                </div>
            </div>

            <div class="form__row">
                <div class="form__column form__column--half">
                    <label for="indexes">Account Fees</label>
                    <multiselect v-model="parameters.fees_account"
                                 label="label"
                                 track-by="label"
                                 :options="this.$globalUtils.get_dataset( 'any_or_none' )"
                                 :hide-selected="false"
                                 :close-on-select="false"
                                 :searchable="true"
                                 :create-option="false">
                    </multiselect>
                </div>
                <div class="form__column form__column--half">
                    <label for="indexes">Product Fees</label>
                    <multiselect v-model="parameters.fees_product"
                                 label="label"
                                 track-by="label"
                                 :options="this.$globalUtils.get_dataset( 'any_or_none' )"
                                 :hide-selected="false"
                                 :close-on-select="false"
                                 :searchable="true"
                                 :create-option="false">
                    </multiselect>
                </div>
            </div>

            <div class="form__row">
                <div class="form__column form__column--half">
                    <label for="indexes">Spread</label>
                    <multiselect v-model="parameters.spread"
                                 label="label"
                                 track-by="label"
                                 :options="this.$globalUtils.get_dataset( 'any_yes_or_none' )"
                                 :hide-selected="false"
                                 :close-on-select="false"
                                 :searchable="true"
                                 :create-option="false">
                    </multiselect>
                </div>
                <div class="form__column form__column--half">
                    <label for="indexes">Cap</label>
                    <multiselect v-model="parameters.cap"
                                 label="label"
                                 track-by="label"
                                 :options="this.$globalUtils.get_dataset( 'cap_rate' )"
                                 :hide-selected="false"
                                 :close-on-select="false"
                                 :searchable="true"
                                 :create-option="false">
                    </multiselect>
                </div>
            </div>

            <div class="form__row">
                <div class="form__column">
                    <label for="indexes">Premium Bonus</label>
                    <multiselect v-model="parameters.premium_bonus"
                                 label="label"
                                 track-by="label"
                                 :options="this.$globalUtils.get_dataset( 'premium_bonus' )"
                                 :hide-selected="false"
                                 :close-on-select="false"
                                 :searchable="true"
                                 :create-option="false">
                    </multiselect>
                </div>
            </div>

            <div class="form__row">
                <div class="form__column">
                    <label for="indexes">Additional Premium</label>
                    <multiselect v-model="parameters.premium_additional"
                                 label="label"
                                 track-by="label"
                                 :options="this.$globalUtils.get_dataset( 'premium_additional' )"
                                 :hide-selected="false"
                                 :close-on-select="false"
                                 :searchable="true"
                                 :create-option="false">
                    </multiselect>
                </div>
            </div>
        </aside>

        <div class="analyzer__results">
            <div v-if="reports_filtered.length">
                <div class="analyzer__result" v-for="( result, result_index ) in reports_filtered" v-bind:key="result_index" v-bind:data-carrier-slug="this.$globalUtils.sanitize_title( result.carrier )">
                    <h3 class="analyzer__result-title">
                        <strong>{{ result.name }}</strong>
                        <span>{{ result.carrier }}</span>
                    </h3>

                    <table>
                        <thead>
                            <tr>
                                <th class="reporting">
                                    <a v-bind:href="result.pdf" class="analyzer__result-report" target="_blank"><i class="fal fa-download" aria-hidden="true"></i> Download Report</a>
                                </th>
                                <th class="heading" colspan="3">10-Year</th>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <th class="percentage">Conservative</th>
                                <th class="percentage">Moderate</th>
                                <th class="percentage">Strong</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="( allocation, allocation_index ) in result.allocations">
                                <td>
                                    <h4>{{ allocation.crediting_strategy_desc }} <span>{{ this.$globalUtils.format( 'index_ticker', allocation.index_ticker ) }}</span></h4>

                                    <div class="analyzer__result-configuration">
                                        <span data-type="reset">
                                            <strong>Reset</strong> <span>Annual</span>
                                        </span>
                                        <span data-type="participation" v-if="allocation.vendor_strategy_rate_instance">
                                            <strong>Cap Rate</strong> <span>{{ allocation.vendor_strategy_rate_instance.current_cap_rate }}%</span>
                                        </span>
                                        <span data-type="participation" v-if="allocation.vendor_strategy_rate_instance">
                                            <strong>Participation Rate</strong> <span>{{ allocation.vendor_strategy_rate_instance.current_participation_rate }}%</span>
                                        </span>
                                        <span data-type="surrender" v-if="allocation.vendor_strategy_rate_instance">
                                            <strong>Premium Range</strong> <span>${{ this.$financeUtils.format_currency_compact( allocation.vendor_strategy_rate_instance.premium_range_min, 'USD' ) }}&mdash;${{ this.$financeUtils.format_currency_compact( allocation.vendor_strategy_rate_instance.premium_range_max, 'USD' ) }}</span>
                                        </span>
                                    </div>
                                </td>

                                <td class="percentage percentage--10y percentage--conservative" v-bind:data-amount="allocation.index_10y_forecast_cons" v-bind:class="{'percentage--gain': allocation.index_10y_forecast_cons > 0, 'percentage--loss': allocation.index_10y_forecast_cons < 0}">{{ format_percentage( allocation.index_10y_forecast_cons ) }}%</td>
                                <td class="percentage percentage--10y percentage--moderate" v-bind:data-amount="allocation.index_10y_forecast_moderate" v-bind:class="{'percentage--gain': allocation.index_10y_forecast_moderate > 0, 'percentage--loss': allocation.index_10y_forecast_moderate < 0}">{{ format_percentage( allocation.index_10y_forecast_moderate ) }}%</td>
                                <td class="percentage percentage--10y percentage--strong" v-bind:data-amount="allocation.index_10y_forecast_strong" v-bind:class="{'percentage--gain': allocation.index_10y_forecast_strong > 0, 'percentage--loss': allocation.index_10y_forecast_strong < 0}">{{ format_percentage( allocation.index_10y_forecast_strong ) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</template>