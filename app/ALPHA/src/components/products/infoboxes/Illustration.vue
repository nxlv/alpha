<template>
    <div class="infobox illustration" v-if="profile">
        <h3>Hypothetical Illustration</h3>

        <div class="illustration__details">
            <table class="table-default illustration__details-table">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Age</th>
                        <th>Account Value</th>
                        <th>Income Base</th>
                        <th>Interest</th>
                        <th>Fees</th>
                        <th>Income</th>
                        <th>Death Benefit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="( row, row_index ) in profile" v-bind:key="row_index" v-bind:class="{ 'has-income': row.income }">
                        <td>{{ row.year }}</td>
                        <td>{{ row.primary_age }}</td>
                        <td>{{ this.$financeUtils.format_currency( row.account_value, 'USD' ) }}</td>
                        <td>{{ this.$financeUtils.format_currency( row.income_benefit_base, 'USD' ) }}</td>
                        <td>{{ this.$financeUtils.format_currency( row.interest_amount, 'USD' ) }} ({{ row.interest_percent }}%)</td>
                        <td>{{ this.$financeUtils.format_currency( row.fees, 'USD' ) }}</td>
                        <td class="income">{{ this.$financeUtils.format_currency( row.income, 'USD' ) }}</td>
                        <td>{{ this.$financeUtils.format_currency( row.death_benefit, 'USD' ) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Year</th>
                        <th>Age</th>
                        <th>Account Value</th>
                        <th>Income Base</th>
                        <th>Interest</th>
                        <th>Fees</th>
                        <th>Income</th>
                        <th>Death Benefit</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        profile: null
    },
    created() {
        console.log( 'illustration', this.profile );
    },
    data() {
        return {
        };
    }
};
</script>