import moment from 'moment';

import {useSetsStore} from '@/stores/sets';
import {useCommonStore} from '@/stores/common';

const globalUtils = {
    merge_with_defaults( defaults, overrides ) {
        let result = { ...defaults };

        for ( let key in overrides ) {
            if ( ( overrides[ key ] !== undefined ) && ( overrides[ key ] !== null ) ) {
                if ( ( result[ key ] === null ) || ( result[ key ] === undefined ) || ( result[ key ] !== overrides[ key ] ) ) {
                    console.log( 'overriding ', key, ' with ', overrides[ key ] );

                    result[ key ] = overrides[ key ];
                }
            }
        }

        return result;
    },

    get_dataset(key) {
        let dataset = [];

        switch (key) {
            case 'states_usa' :
                dataset = {
                    'AK': 'Alaska',
                    'AL': 'Alabama',
                    'AR': 'Arkansas',
                    'AZ': 'Arizona',
                    'CA': 'California',
                    'CO': 'Colorado',
                    'CT': 'Connecticut',
                    'DE': 'Delaware',
                    'FL': 'Florida',
                    'GA': 'Georgia',
                    'HI': 'Hawaii',
                    'IA': 'Iowa',
                    'ID': 'Idaho',
                    'IL': 'Illinois',
                    'IN': 'Indiana',
                    'KS': 'Kansas',
                    'KY': 'Kentucky',
                    'LA': 'Louisiana',
                    'MA': 'Massachusetts',
                    'MD': 'Maryland',
                    'ME': 'Maine',
                    'MI': 'Michigan',
                    'MN': 'Minnesota',
                    'MO': 'Missouri',
                    'MS': 'Mississippi',
                    'MT': 'Montana',
                    'NC': 'North Carolina',
                    'ND': 'North Dakota',
                    'NE': 'Nebraska',
                    'NH': 'New Hampshire',
                    'NJ': 'New Jersey',
                    'NM': 'New Mexico',
                    'NV': 'Nevada',
                    'NY': 'New York',
                    'OH': 'Ohio',
                    'OK': 'Oklahoma',
                    'OR': 'Oregon',
                    'PA': 'Pennsylvania',
                    'RI': 'Rhode Island',
                    'SC': 'South Carolina',
                    'SD': 'South Dakota',
                    'TN': 'Tennessee',
                    'TX': 'Texas',
                    'UT': 'Utah',
                    'VA': 'Virginia',
                    'VT': 'Vermont',
                    'WA': 'Washington',
                    'WI': 'Wisconsin',
                    'WV': 'West Virginia',
                    'WY': 'Wyoming'
                }
                break;

            case 'states_can' :
                dataset = {
                    'AB': 'Alberta',
                    'BC': 'British Columbia',
                    'MB': 'Manitoba',
                    'NB': 'New Brunswick',
                    'NL': 'Newfoundland and Labrador',
                    'NS': 'Nova Scotia',
                    'NT': 'Northwest Territories',
                    'NU': 'Nunavut',
                    'ON': 'Ontario',
                    'PE': 'Prince Edward Island',
                    'QC': 'Quebec',
                    'SK': 'Saskatchewan',
                    'YT': 'Yukon'
                };
                break;

            case 'strategy_type' :
                dataset = {
                    'AL': 'Allocated',
                    'AV': 'Average',
                    'FX': 'Fixed',
                    'IT': 'Inverse Performance Triggered',
                    'LA': 'Layered',
                    'PP': 'Point-to-Point',
                    'PT': 'Performance Triggered',
                    'SU': 'Sum'
                };
                break;

            case 'strategy_configuration' :
                dataset = {
                    '01': 'Fixed',
                    '02': 'Declared + Participation',
                    '03': 'Cap + Participation',
                    '04': 'Spread + Participation',
                    '05': 'Participation Only',
                    '08': 'Replacement + Participation',
                    '99': 'Parent or Sub-strategy Only'
                };
                break;

            case 'death_benefit_type' :
                dataset = {
                    'CV': 'Contract Value',
                    'CSV': 'Cash Surrender Value',
                    'ROP': 'Return of Premium'
                };
                break;

            case 'age_rule_basis' :
                dataset = {
                    'O': 'Owner'
                };
                break;

            case 'premium_inheritance' :
            case 'issue_age_inheritance' :
                dataset = {
                    'S': 'Same as product',
                    'D': 'Different than product'
                };
                break;

            case 'rating_company' :
                dataset = {
                    'SP': 'Standard and Poors',
                    'MOOD': 'Moody\'s',
                    'AMB': 'A.M. Best',
                    'FTCH': 'Fitch'
                }
                break;

            case 'frequency' :
                dataset = {
                    'A': 'Annual',
                    'M': 'Monthly',
                    'D': 'Daily',
                    '2Y': '2-year',
                    '3Y': '3-year',
                    '5Y': '5-year',
                    '7Y': '7-year',
                    '10': '10-year'
                };
                break;

            case 'cap_rate' :
                dataset = {
                    '': 'Any',
                    'yes': 'Yes',
                    'none': 'None',
                    '5': '> 5%',
                    '10': '> 10%',
                    '15': '> 15%'
                }
                break;

            case 'premium_bonus' :
                dataset = {
                    '': 'Any',
                    'yes': 'Yes',
                    'no': 'No',
                    '5': '> 5%',
                    '10': '> 10%'
                }
                break;

            case 'premium_additional' :
                dataset = {
                    '': 'Any',
                    'single': 'Single Premium',
                    'product': 'Product Life',
                    'first': 'First Year (or less)'
                }
                break;

            case 'yesno' :
                dataset = {
                    'Y': 'Yes',
                    'N': 'No'
                };
                break;

            case 'single_joint' :
                dataset = {
                    'S': 'Single',
                    'J': 'Joint'
                }
                break;

            case 'gender' :
                dataset = {
                    'M': 'Male',
                    'F': 'Female'
                }
                break;

            case 'any_or_none' :
                dataset = {
                    '': 'Any',
                    'none': 'None'
                }
                break;

            case 'any_yes_or_none' :
                dataset = {
                    '': 'Any',
                    'yes': 'Yes',
                    'none': 'None'
                }
                break;
        }

        return dataset;
    },

    get_dataset_as_kvp(key) {
        let response = [];
        let dataset = this.get_dataset( key );

        if ( dataset ) {
            for ( let element in dataset ) {
                response.push(
                    {
                        value: element,
                        label: dataset[ element ],
                        disabled: false
                    }
                )
            }
        }

        return response;
    },

    get_set_as_kvp(key) {
        const sets = useSetsStore();

        let response = [];
        let dataset = null;
        let label, value, extra = null;

        switch ( key ) {
            case 'indexes' :
                dataset = sets.sets.indexes;

                label = 'index_name';
                value = 'index_id';
                break;

            case 'indexes_tickers' :
                dataset = sets.sets.indexes;

                label = 'index_name';
                value = 'universal_ticker';
                break;

            case 'carriers' :
                dataset = sets.sets.carriers;

                label = 'name';
                value = 'id';

                extra = [ 'products' ];
                break;
        }

        if ( ( dataset ) && ( label ) && ( value ) ) {
            let record = null;

            for ( let counter = 0; counter < dataset.length; counter++ ) {
                record = { 'value': dataset[ counter ][ value ], 'label': dataset[ counter ][ label ] };

                if ( extra ) {
                    for ( let counter_extra = 0; counter_extra < extra.length; counter_extra++ ) {
                        record[ extra[ counter_extra ] ] = dataset[ counter ][ extra[ counter_extra ] ];
                    }
                }

                response.push( record );
            }
        }

        return response;
    },

    get_value_from_meta_key(meta, key) {
        let value = '';

        if ((meta) && (meta.length)) {
            for (let counter = 0; counter < meta.length; counter++) {
                if (meta[counter].key === key) {
                    value = meta[counter].value;
                    break;
                }
            }
        }

        return value;
    },

    generate_nonce() {
        let nonce_length = 12;
        let result = '';
        let dictionary = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        for ( let counter = 0; counter < nonce_length; counter++ ) {
            result += dictionary.charAt( Math.floor( Math.random() * nonce_length ) );
        }

        return result;
    },

    verify_nonce(nonce, response) {
        let valid = false;

        if ( ( response ) && ( response.config ) && ( response.config.data ) ) {
            let request = JSON.parse( response.config.data );

            if ( ( request ) && ( request.nonce ) ) {
                valid = request.nonce === nonce;
            }
        }

        return valid;
    },

    sanitize_title(title) {
        let slug = '';

        if ( !title ) {
            return slug;
        }

        // Change to lower case
        let titleLower = title.toLowerCase();

        // Letter "e"
        slug = titleLower.replace(/e|é|è|ẽ|ẻ|ẹ|ê|ế|ề|ễ|ể|ệ/gi, 'e');

        // Letter "a"
        slug = slug.replace(/a|á|à|ã|ả|ạ|ă|ắ|ằ|ẵ|ẳ|ặ|â|ấ|ầ|ẫ|ẩ|ậ/gi, 'a');

        // Letter "o"
        slug = slug.replace(/o|ó|ò|õ|ỏ|ọ|ô|ố|ồ|ỗ|ổ|ộ|ơ|ớ|ờ|ỡ|ở|ợ/gi, 'o');

        // Letter "u"
        slug = slug.replace(/u|ú|ù|ũ|ủ|ụ|ư|ứ|ừ|ữ|ử|ự/gi, 'u');

        // Letter "d"
        slug = slug.replace(/đ/gi, 'd');

        // Trim the last whitespace
        slug = slug.replace(/\s*$/g, '');

        // Change whitespace to "-"
        slug = slug.replace(/\s+/g, '-');

        return slug;
    },

    ensure_type( value, type ) {
        let response = value;

        switch ( type ) {
            case 'int' :
            case 'float' :
                response = value.toString().replace( /[^0-9.]/g, '' );

                if ( type === 'int' ) {
                    response = parseInt( response );
                } else {
                    response = parseFloat( response );
                }
                break;
        }

        return response;
    },

    // TODO: integrate these datasets into the initial loading process
    format(type, value) {
        let dataset = {};
        let placeholder = '—';

        const sets = useSetsStore();

        switch (type) {
            case 'yesno' :
                placeholder = 'No';

                dataset = this.get_dataset( type );
                break;

            case 'states' :
                if (value.length) {
                    let states = value.split(',');

                    if (states.length > 10) {
                        return states.slice(0, 9).join(', ') + ' and ' + (states.length - 10) + ' more';
                    }
                }
                break;

            case 'index' :
                for (let counter = 0; counter < sets.sets.indexes.length; counter++) {
                    if (sets.sets.indexes[counter].index_id === value) {
                        return sets.sets.indexes[counter].index_name;
                    }
                }
                break;

            case 'index_ticker' :
                for (let counter = 0; counter < sets.sets.indexes.length; counter++) {
                    if (sets.sets.indexes[counter].universal_ticker === value) {
                        return sets.sets.indexes[counter].index_name;
                    }
                }
                break;

            case 'rate' :
                if (parseFloat(value) > 0) {
                    return value + '%';
                }
                break;

            case 'age' :
                // TODO: ensure type
                value = moment( value );

                if ( moment.isMoment( value ) ) {
                    value = moment().diff( value, 'years' );
                }
                break;

            case 'numeric' :
                return value.replace( /\D/g, '' );
                break;

            default :
                dataset = this.get_dataset( type );
                break;
        }

        return dataset[value] || ((value) ? value : placeholder);
    },

    modal_close() {
        const common = useCommonStore();

        common.commit( 'view', null );
        common.commit( 'modal', null );
    }
};

export default globalUtils;