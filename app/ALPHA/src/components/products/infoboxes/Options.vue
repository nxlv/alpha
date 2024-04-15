<template>
    <div class="infobox options" v-if="profile">
        <h3>Allocation Options</h3>

        <div class="options__details">
            <table class="table-default options__details-table">
                <thead>
                    <tr>
                        <th>Income Benefit Configuration</th>
                        <th>Type</th>
                        <th>Index</th>
                        <th>Fixed</th>
                        <th>Participation</th>
                        <th>CAP</th>
                        <th>Spread</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="( option, option_index ) in profile" v-bind:index="option_index">
                        <td>
                            <span data-type="name" v-html="$productUtils.generate_iao_name( option )"></span>
                            <span data-type="premium" v-if="( ( option.rules.premium_min > 0 ) && ( option.rules.premium_max > 0 ) )">${{ $financeUtils.format_currency_compact( option.rules.premium_min, 'USD' ) }}&mdash;${{ $financeUtils.format_currency_compact( option.rules.premium_max, 'USD' ) }}</span>
                            <span data-type="premium" v-if="( ( option.rules.premium_min > 0 ) && ( option.rules.premium_max <= 0 ) )"><i class="fal fa-greater-than-equal" aria-hidden="true"></i> ${{ $financeUtils.format_currency_compact( option.rules.premium_min, 'USD' ) }}</span>
                            <span data-type="premium" v-if="( ( option.rules.premium_max > 0 ) && ( option.rules.premium_min <= 0 ) )"><i class="fal fa-less-than-equal" aria-hidden="true"></i> ${{ $financeUtils.format_currency_compact( option.rules.premium_max, 'USD' ) }}</span>
                            <span data-type="premium" v-if="( ( option.rules.premium_min === 0 ) && ( option.rules.premium_max === 0 ) )"><i class="fal fa-check-double" aria-hidden="true"></i> No premium limits</span>
                            <span data-type="frequency">{{ $globalUtils.format( 'frequency', option.strategy.calculation_frequency ) }}</span>
                        </td>
                        <td>{{ $globalUtils.format( 'strategy_type', option.strategy.strategy_type ) }}</td>
                        <td>{{ $globalUtils.format( 'index', option.strategy.index_id ) }}</td>
                        <td>{{ ( option.strategy.rates.current_fixed_rate ) ? ( option.strategy.rates.current_fixed_rate + '%' ) : '&mdash;' }}</td>
                        <td>{{ ( option.strategy.rates.current_participation_rate ) ? ( option.strategy.rates.current_participation_rate + '%' ) : '&mdash;' }}</td>
                        <td>{{ ( option.strategy.rates.current_cap_rate ) ? ( option.strategy.rates.current_cap_rate + '%' ) : '&mdash;' }}</td>
                        <td>{{ ( option.strategy.rates.current_spread_rate ) ? ( option.strategy.rates.current_spread_rate + '%' ) : '&mdash;' }}</td>
                        <td><span data-type="link" v-on:click="set_product_id( option.analysis_data_id )">View <i class="fas fa-chevron-right" aria-hidden="true"></i></span></td>
                    </tr>
                </tbody>
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
            console.log( 'options', this.profile );
        },
        methods: {
            set_product_id( product_id ) {
                this.$emitter.emit( 'set_product_id', product_id );
            }
        },
        data() {
            return {
            };
        }
    };
</script>