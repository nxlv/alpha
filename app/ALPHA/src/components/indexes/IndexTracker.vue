<script>
    import { RouterLink } from 'vue-router';
    import axios from 'axios';

    export default {
        components: {
            RouterLink
        },
        methods: {
            async fetch_reports() {
                this.loading = true;

                let endpoint = '/api/indexes/reports/all';
                let request = await axios.get( ( ( import.meta.env.PROD ) ? ( '//' + window.location.host ) : import.meta.env.VITE_API_BASE_URL ) + endpoint, this.parameters );

                this.errors = null;
                this.reports = null;
                this.loading = false;

                if ( ( request ) && ( !request.data.error ) && ( request.data.result ) ) {
                    this.reports = request.data.result;
                } else {
                    this.$toast.error( 'Index information could not be loaded.  Please try again.', { position: 'top-left' } );
                }
            },

            format_percentage( percentage ) {
                let value = parseFloat( percentage );

                value = value * 100;

                return value.toFixed( 2 );
            },

            sort( type ) {
                if ( this.parameters.sort_column === type ) {
                    this.parameters.sort_direction = !this.parameters.sort_direction;

                    console.log( 'sort column', this.parameters.sort_column );
                    console.log( 'sort direction', this.parameters.sort_direction );
                }

                this.parameters.sort_column = type;

                this.reports.sort( ( left, right ) => {
                    let response = 0;

                    response = ( ( left[ 'index_returns_' + type ] > right[ 'index_returns_' + type ] ) ? -1 : 1 );

                    return ( ( this.parameters.sort_direction ) ? response : ( ( response === -1 ) ? 1 : -1 ) );
                } );
            }
        },

        created() {
            this.fetch_reports();

            //this.$toast.success( 'Loading...', { position: 'top-left' } );
        },

        data() {
            return {
                errors: null,
                reports: null,
                loading: false,
                parameters: {
                    sort_column: '',
                    sort_direction: true
                }
            };
        }
    };
</script>
<template>
    <section class="tracker">
        <table v-if="reports">
            <thead>
              <tr>
                  <th>Index</th>
                  <th v-on:click="sort( 'ytd' )" v-bind:class="{ 'sorted': parameters.sort_column === 'ytd', 'sorted--direction-desc': parameters.sort_direction, 'sorted--direction-asc': !parameters.sort_direction }"><span>YTD</span></th>
                  <th v-on:click="sort( '6month' )" v-bind:class="{ 'sorted': parameters.sort_column === '6month', 'sorted--direction-desc': parameters.sort_direction, 'sorted--direction-asc': !parameters.sort_direction }"><span>6</span> months</th>
                  <th v-on:click="sort( '1year' )" v-bind:class="{ 'sorted': parameters.sort_column === '1year', 'sorted--direction-desc': parameters.sort_direction, 'sorted--direction-asc': !parameters.sort_direction }"><span>1</span> year</th>
                  <th v-on:click="sort( '3year' )" v-bind:class="{ 'sorted': parameters.sort_column === '3year', 'sorted--direction-desc': parameters.sort_direction, 'sorted--direction-asc': !parameters.sort_direction }"><span>3</span> years</th>
                  <th v-on:click="sort( '5year' )" v-bind:class="{ 'sorted': parameters.sort_column === '5year', 'sorted--direction-desc': parameters.sort_direction, 'sorted--direction-asc': !parameters.sort_direction }"><span>5</span> years</th>
                  <th v-on:click="sort( '10year' )" v-bind:class="{ 'sorted': parameters.sort_column === '10year', 'sorted--direction-desc': parameters.sort_direction, 'sorted--direction-asc': !parameters.sort_direction }"><span>10</span> years</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="( report, report_index ) in reports" v-bind:key="report_index">
                  <td class="index">
                      <strong>{{ report.display_name }}</strong>

                      <span data-type="ticker">{{ report.ticker }}</span>
                      <span data-type="accounts">{{ report.product_count }} accounts</span>
                      <span data-type="date">{{ report.computation_date }}</span>

                      <br />

                      <span data-type="report"><a v-bind:href="report.ratings_report_url" target="_blank">Ratings Report</a></span>
                      <span data-type="report"><a v-bind:href="report.forecasts_report_url" target="_blank">Forecast Report</a></span>
                  </td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_ytd" v-bind:class="{'percentage--gain': report.index_returns_ytd > 0, 'percentage--loss': report.index_returns_ytd < 0}">{{ format_percentage( report.index_returns_ytd ) }}%</td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_6month" v-bind:class="{'percentage--gain': report.index_returns_6month > 0, 'percentage--loss': report.index_returns_6month < 0}">{{ format_percentage( report.index_returns_6month ) }}%</td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_1year" v-bind:class="{'percentage--gain': report.index_returns_1year > 0, 'percentage--loss': report.index_returns_1year < 0}">{{ format_percentage( report.index_returns_1year ) }}%</td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_3year" v-bind:class="{'percentage--gain': report.index_returns_3year > 0, 'percentage--loss': report.index_returns_3year < 0}">{{ format_percentage( report.index_returns_3year ) }}%</td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_5year" v-bind:class="{'percentage--gain': report.index_returns_5year > 0, 'percentage--loss': report.index_returns_5year < 0}">{{ format_percentage( report.index_returns_5year ) }}%</td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_10year" v-bind:class="{'percentage--gain': report.index_returns_10year > 0, 'percentage--loss': report.index_returns_10year < 0}">{{ format_percentage( report.index_returns_10year ) }}%</td>
              </tr>
            </tbody>
        </table>
    </section>
</template>