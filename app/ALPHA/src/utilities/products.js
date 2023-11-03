import { useSetsStore } from '@/stores/sets';
import globalUtils from '@/utilities/global.js';
import financeUtils from '@/utilities/financials.js';

const productUtils = {
    find_products_by_carrier_id( carrier_id ) {
        const sets = useSetsStore();

        let response = [];

        if ( ( sets ) && ( sets.sets ) && ( sets.sets.carriers ) && ( sets.sets.carriers.length ) ) {
            let results = [];

            for ( let counter = 0; counter < sets.sets.carriers.length; counter++ ) {
                if ( sets.sets.carriers[ counter ].id === carrier_id ) {
                    results = JSON.parse( JSON.stringify( sets.sets.carriers[ counter ].products ) );
                    break;
                }
            }

            if ( results.length ) {
                for ( let counter = 0; counter < results.length; counter++ ) {
                    response.push( $globalUtils.get_value_from_meta_key( results[ counter ].meta, 'UNIQUE' ) );
                }
            }
        }

        return response;
    },

    find_product_by_profile_id( profile_id ) {
        const sets = useSetsStore();

        let results = [];

        // find product

        return JSON.parse( JSON.stringify( results ) );
    },

    generate_iao_name( row ) {
        let parts = [];

        let index_id = ( ( row.source ) ? row.source.strategy.index_id : row.strategy.index_id );
        let calculation_frequency = ( ( row.source ) ? row.source.strategy.calculation_frequency : row.strategy.calculation_frequency );
        let current_participation_rate = ( ( row.source ) ? row.source.current_participation_rate : row.current_participation_rate );
        let strategy_type = ( ( row.source ) ? row.source.strategy.strategy_type : row.strategy.strategy_type );
        let strategy_configuration = ( ( row.source ) ? row.source.strategy.strategy_configuration : row.strategy.strategy_configuration );
        let guarantee_years = ( ( row.source ) ? row.source.strategy.guarantee_period_years : row.strategy.guarantee_period_years );
        let guarantee_months =  ( ( row.source ) ? row.source.strategy.guarantee_period_months : row.strategy.guarantee_period_months );
        let premium_range_min = ( ( row.source ) ? row.source.premium_range_min : row.rules.premium_min );
        let premium_range_max = ( ( row.source ) ? row.source.premium_range_max : row.rules.premium_max );

        if ( guarantee_years ) {
            parts.push( guarantee_years + ' ' + ( ( guarantee_years === 1 ) ? 'year' : 'years' ) );

            if ( guarantee_months ) {
                parts.push( guarantee_months + ' ' + ( ( guarantee_months === 1 ) ? 'month' : 'months' ) );
            }
        }

        if ( index_id ) {
            parts.push( globalUtils.format( 'index', index_id ) );
        }

        /*
        if ( calculation_frequency ) {
            parts.push( globalUtils.format( 'frequency', calculation_frequency ) );
        }
        */

        if ( strategy_type ) {
            parts.push( globalUtils.format( 'strategy_type', strategy_type ) );
        }

        /*
        if ( ( premium_range_min > 0 ) || ( premium_range_max > 0 ) ) {
            if ( ( premium_range_min > 0 ) && ( premium_range_max > 0 ) ) {
                parts.push( '$' + financeUtils.format_currency_compact( premium_range_min, 'USD' ) + '-$' + financeUtils.format_currency_compact( premium_range_max, 'USD' ) );
            } else if ( premium_range_min > 0 ) {
                parts.push( '&gt;= $' + financeUtils.format_currency_compact( premium_range_min, 'USD' ) );
            } else if ( premium_range_max > 0 ) {
                parts.push( '&lt;= $' + financeUtils.format_currency_compact( premium_range_max, 'USD' ) );
            }
        }
        */

        if ( strategy_configuration ) {
            parts.push( globalUtils.format( 'strategy_configuration', strategy_configuration ) );
        }

        if ( current_participation_rate > 0 ) {
            parts.push( current_participation_rate + '% PR' );
        }

        return parts.join( ' ' );
    }
};

export default productUtils;