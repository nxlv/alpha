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

                    console.log( this.reports );
                } else {
                    // error
                }
            }
        },

        created() {
            this.fetch_reports();
        },

        data() {
            return {
                errors: null,
                reports: null,
                loading: false,
                parameters: {
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
                  <th>YTD</th>
                  <th><span>6</span> month</th>
                  <th><span>1</span> year</th>
                  <th><span>3</span> years</th>
                  <th><span>5</span> years</th>
                  <th><span>10</span> years</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="( report, report_index ) in reports" v-bind:key="report_index">
                  <td class="index">
                      <strong>{{ report.ticker }}</strong>
                      <span>{{ report.display_name }}</span>
                  </td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_ytd">{{ report.index_returns_ytd.toFixed(2) }}%</td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_6month">{{ report.index_returns_6month.toFixed(2) }}%</td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_1year">{{ report.index_returns_1year.toFixed(2) }}%</td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_3year">{{ report.index_returns_3year.toFixed(2) }}%</td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_5year">{{ report.index_returns_5year.toFixed(2) }}%</td>
                  <td class="percentage" v-bind:data-amount="report.index_returns_10year">{{ report.index_returns_10year.toFixed(2) }}%</td>
              </tr>
            </tbody>
        </table>
    </section>
</template>