@extends('layouts.pdf')

@section('content')
    <article class="reporting" style="max-width: 50vw; min-width: 992px; margin-left: auto; margin-right: auto; border-left: 1px #000 solid; border-right: 1px #000 solid;">
        <section class="report report--cover page">
            <div class="report__cover">
                <h1>Personalized Income Rider Payouts</h1>

                <h2>Income Riders</h2>
                <p>{{ count( $products ) }} income rider(s)</p>

                <p>Report prepared for {{ $annuitant[ 'owner_name_first' ] }} {{ $annuitant[ 'owner_name_last' ] }}</p>

                <figure>
                    <img src="{{ asset( 'assets/images/illustrations/2x/reporting.png' ) }}" alt="An illustration of a financial report">
                </figure>
            </div>
        </section>

        <section class="report page">
            <div class="report__disclaimers">
                <p>This information is not a quote, contract or guarantee of future performance. It has been created by Annuity Association for informational purposes only and is based on our understanding of the product at the time and is subject to change without notice. It is not complete unless all pages are included. It is not intended to be a recommendation to purchase an annuity and is not a representation regarding the suitability of any concept or product. You are strongly urged to consult with financial planning, tax, and legal advisors and must refer to each insurance company’s complete product information before making any decision. Annuities are subject to the terms and conditions of the specific contract issued, all rates are subject to change, and any guarantees are subject to the claims paying ability of the applicable insurance company.</p>
            </div>

            <div class="report__client data-points">
                <h3>Client Information</h3>

                <ul class="report__client-data data-points-list">
                    <li class="report__client-data-point data-point">
                        <label>Contract Type</label>
                        <span>{{ ( ( $annuitant[ 'annuity_type' ] === 'S' ) ? 'Single' : 'Joint' ) }}</span>
                    </li>
                    <li class="report__client-data-point data-point">
                        <label>Investment Amount</label>
                        <span>{{ $settings[ 'premium' ] }}</span>
                    </li>
                    <li class="report__client-data-point data-point">
                        <label>Deferral</label>
                        <span>{{ $settings[ 'deferral' ] }} years</span>
                    </li>
                </ul>

                <h4>Owner Information</h4>
                <ul class="report__client-data data-points-list">
                    <li class="report__client-data-point data-point">
                        <label>Name</label>
                        <span>{{ $annuitant[ 'owner_name_first' ] }} {{ $annuitant[ 'owner_name_last' ] }}</span>
                    </li>
                    <li class="report__client-data-point data-point">
                        <label>DOB / Age</label>
                        <span>{{ $annuitant[ 'owner_birthdate' ] }}<br /><small>{{ $annuitant[ 'owner_age' ] }} years old</small></span>
                    </li>
                    <li class="report__client-data-point data-point">
                        <label>Gender</label>
                        <span>{{ $annuitant[ 'owner_gender' ] }}</span>
                    </li>
                    <li class="report__client-data-point data-point">
                        <label>State</label>
                        <span>{{ $annuitant[ 'owner_state' ] }}</span>
                    </li>
                </ul>

                @if ( $annuitant[ 'annuity_type' ] === 'J' )
                <h4>Joint Information</h4>
                <ul class="report__client-data data-points-list">
                    <li class="report__client-data-point data-point">
                        <label>Name</label>
                        <span>{{ $annuitant[ 'joint_name_first' ] }} {{ $annuitant[ 'joint_name_last' ] }}</span>
                    </li>
                    <li class="report__client-data-point data-point">
                        <label>DOB / Age</label>
                        <span>{{ $annuitant[ 'joint_birthdate' ] }}<br /><small>{{ $annuitant[ 'joint_age' ] }} years old</small></span>
                    </li>
                    <li class="report__client-data-point data-point">
                        <label>Gender</label>
                        <span>{{ $annuitant[ 'joint_gender' ] }}</span>
                    </li>
                    <li class="report__client-data-point data-point">
                        <label>State</label>
                        <span>{{ $annuitant[ 'joint_state' ] }}</span>
                    </li>
                </ul>
                @endif
            </div>

            <div class="report__products data-points">
                <h3>Products being analyzed</h3>

                <ul class="report__products-data data-points-list">
                    @foreach ( $products as $product )
                        <!-- loop through $illustrations[ guaranteed' ] and match up records to get guaranteed income -->
                        @foreach ( $illustrations[ 'guaranteed' ] as $illustration )
                            @if ( $product->analysis_data_id === $illustration->evaluate_request->evaluate_request_a->income_analysis_data_id )
                                <li class="report__products-data-point data-point">
                                    <div class="report__products-data-point-thumbnail" data-carrier-slug="{{ Str::slug( $product->carrier_product->carrier->name ) }}"></div>

                                    @foreach ( $illustration->evaluate_data as $row )
                                        @if ( floatval( $row->income ) )
                                            <div class="report__products-data-point-income">
                                                ${{ number_format( $row->income, 2 ) }}
                                            </div>
                                            @break
                                        @endif
                                    @endforeach

                                    <div class="report__products-data-point-details">
                                        <p>{{ $product->carrier_product->name }} ({{ $product->income_benefit->name }})</p>
                                        <p><small>{{ $product->carrier_product->carrier->name }}</small></p>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </section>

        @foreach ( $products as $product )
            <section class="report page">
                <div class="report__disclaimers">
                    <p>This information is not a quote, contract or guarantee of future performance. It has been created by Annuity Association for informational purposes only and is based on our understanding of the product at the time and is subject to change without notice. It is not complete unless all pages are included. It is not intended to be a recommendation to purchase an annuity and is not a representation regarding the suitability of any concept or product. You are strongly urged to consult with financial planning, tax, and legal advisors and must refer to each insurance company’s complete product information before making any decision. Annuities are subject to the terms and conditions of the specific contract issued, all rates are subject to change, and any guarantees are subject to the claims paying ability of the applicable insurance company.</p>
                </div>

                <header class="report__header">
                    <div class="report__header-product-name">{{ $product->carrier_product->name }} ({{ $product->income_benefit->name }})</div>
                    <div class="report__header-carrier-name">{{ $product->carrier_product->carrier->name }}</div>
                    <div class="report__header-carrier-logo" data-carrier-slug="{{ Str::slug( $product->carrier_product->carrier->name ) }}">&nbsp;</div>
                </header>

                <div class="report__product data-points">
                    <h3>Product Information</h3>

                    <ul class="report__client-data data-points-list">
                        @if ( $illustrations[ 'guaranteed' ] )
                            @foreach ( $illustrations[ 'guaranteed' ] as $illustration )
                                @if ( $product->analysis_data_id === $illustration->evaluate_request->evaluate_request_a->income_analysis_data_id )
                                    @foreach ( $illustration->evaluate_data as $row )
                                        @if ( floatval( $row->income ) )
                                            <li class="report__client-data-point data-point">
                                                <label>Guaranteed Income</label>
                                                <span>${{ number_format( $row->income, 2 ) }}</span>
                                            </li>
                                            <li class="report__client-data-point data-point">
                                                <label>Income Base</label>
                                                <span>${{ number_format( $row->income_benefit_base, 2 ) }} <small>at age {{ $row->primary_age }}</small></span>
                                            </li>
                                            <li class="report__client-data-point data-point">
                                                <label>Fees</label>
                                                <span>${{ number_format( $row->fees, 2 ) }}<br /></span>
                                            </li>

                                            @break
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                        
                        <li class="report__client-data-point data-point">
                            <label>Roll-up Rate</label>
                            <span>
                                @if ( $product->income_benefit->roll_up )
                                    @foreach ( $product->income_benefit->roll_up as $roll_up )
                                    <span><em>Tier {{ $roll_up->tier_no }}</em> {{ $roll_up->rate }}%</span>
                                    @endforeach
                                @else
                                    &mdash;
                                @endif
                            </span>
                        </li>
                        <li class="report__client-data-point data-point">
                            <label>Annual Rider Fee</label>
                            <span>
                                @if ( $product->income_benefit->rider_fee_current )
                                    @foreach ( $product->income_benefit->rider_fee_current as $rider_fee )
                                    <span><em>Tier {{ $rider_fee->tier_no }}</em> {{ $rider_fee->rate }}%</span>
                                    @endforeach
                                @else
                                    &mdash;
                                @endif
                            </span>
                        </li>
                        <li class="report__client-data-point data-point">
                            <label>Premium Bonus</label>
                            <span>
                                @if ( $product->income_benefit->premium_bonus )
                                    @foreach ( $product->income_benefit->premium_bonus as $premium_bonus )
                                    <span><em>Tier {{ $premium_bonus->tier_no }}</em> {{ $premium_bonus->rate }}%</span>
                                    @endforeach
                                @else
                                    &mdash;
                                @endif
                            </span>
                        </li>
                        <li class="report__client-data-point data-point">
                            <label>Premium Multiplier</label>
                            <span>
                                @if ( $product->income_benefit->premium_multiplier )
                                    @foreach ( $product->income_benefit->premium_multiplier as $premium_multiplier )
                                    <span><em>Tier {{ $premium_multiplier->tier_no }}</em> {{ $premium_multiplier->rate }}%</span>
                                    @endforeach
                                @else
                                    &mdash;
                                @endif
                            </span>
                        </li>
                        <li class="report__client-data-point data-point">
                            <label>Income Start Age (Min)</label>
                            <span>
                                @if ( $product->income_benefit->income_start_age )
                                    @foreach ( $product->income_benefit->income_start_age as $income_start_age )
                                    <span>{{ $income_start_age->min_years }}</span>
                                    @endforeach
                                @else
                                    &mdash;
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </section>

            @if ( $illustrations[ 'guaranteed' ] )
                @foreach ( $illustrations[ 'guaranteed' ] as $illustration )
                    @if ( ( $product->analysis_data_id === $illustration->evaluate_request->evaluate_request_a->income_analysis_data_id ) && ( $illustration->evaluate_data ) )
                    <section class="report page">
                        <div class="report__disclaimers">
                            <p>This information is not a quote, contract or guarantee of future performance. It has been created by Annuity Association for informational purposes only and is based on our understanding of the product at the time and is subject to change without notice. It is not complete unless all pages are included. It is not intended to be a recommendation to purchase an annuity and is not a representation regarding the suitability of any concept or product. You are strongly urged to consult with financial planning, tax, and legal advisors and must refer to each insurance company’s complete product information before making any decision. Annuities are subject to the terms and conditions of the specific contract issued, all rates are subject to change, and any guarantees are subject to the claims paying ability of the applicable insurance company.</p>
                        </div>

                        <header class="report__header">
                            <div class="report__header-product-name">{{ $product->carrier_product->name }} ({{ $product->income_benefit->name }})</div>
                            <div class="report__header-carrier-name">{{ $product->carrier_product->carrier->name }}</div>
                            <div class="report__header-carrier-logo" data-carrier-slug="{{ Str::slug( $product->carrier_product->carrier->name ) }}">&nbsp;</div>
                        </header>

                        <div class="report__content">
                            <div class="report__content-wrapper">
                                <h3>Guaranteed Income</h3>
                                <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eu ligula id est tincidunt malesuada. Quisque ultrices volutpat porta. Ut pharetra leo quis lorem imperdiet auctor. Aliquam ut pellentesque justo. Praesent urna turpis, fermentum vitae lacus a, venenatis cursus augue. Duis vitae consectetur nisi, nec dictum sapien. Cras vitae arcu a risus pellentesque tincidunt. Proin risus risus, porta id sapien non, interdum dictum magna. Morbi eu pulvinar sapien, et placerat mauris. Etiam nec ornare augue. Duis ullamcorper mi vel tellus vulputate varius. Ut cursus nulla vel rutrum commodo. Mauris et feugiat lectus. Donec sodales arcu et ex rhoncus faucibus. Sed ac ultricies diam. Phasellus ut vestibulum tellus, et ullamcorper ipsum.</p>
                                <p>Curabitur faucibus molestie ligula, eget pharetra orci convallis vel. Integer volutpat hendrerit augue vitae aliquam. Sed aliquam purus eget auctor eleifend. Etiam quis viverra nunc. Pellentesque mi velit, varius vel est ac, pulvinar fermentum orci. Duis quis nisl id urna finibus vehicula et ut velit. Pellentesque sodales magna diam, quis iaculis erat luctus ut. Quisque velit enim, pulvinar non finibus et, elementum eu nibh.</p>
                            </div>
                            <div class="report__content-wrapper">
                                <table class="table-default illustration__details-table">
                                    <thead>
                                        <tr>
                                            <th>Year</th>
                                            <th>Age</th>
                                            <th>Account Value</th>
                                            <th>Income Benefit Base</th>
                                            <th>Interest</th>
                                            <th>Fees</th>
                                            <th>Income</th>
                                            <th>Death Benefit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ( $illustration->evaluate_data as $row )
                                        <tr class="{{ ( ( $row->income ) ? 'has-income' : '' ) }}">
                                            <td>{{ $row->year }}</td>
                                            <td>{{ $row->primary_age }}</td>
                                            <td>${{ number_format( $row->account_value, 2 ) }}</td>
                                            <td>${{ number_format( $row->income_benefit_base, 2 ) }}</td>
                                            <td>${{ number_format( $row->interest_amount, 2 ) }} ({{ $row->interest_percent }}%)</td>
                                            <td>${{ number_format( $row->fees, 2 ) }}</td>
                                            <td class="income">${{ number_format( $row->income, 2 ) }}</td>
                                            <td>${{ number_format( $row->death_benefit, 2 ) }}</td>
                                        </tr>
                                    @endforeach
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
                    </section>

                    @break
                    @endif
                @endforeach
            @endif

            @if ( $illustrations[ 'hypothetical' ] )
                @foreach ( $illustrations[ 'hypothetical' ] as $illustration )
                    @if ( ( $product->analysis_data_id === $illustration->analysis_request->analysis_data_id ) && ( $illustration->analysis_data ) )
                    <section class="report page">
                        <div class="report__disclaimers">
                            <p>This information is not a quote, contract or guarantee of future performance. It has been created by Annuity Association for informational purposes only and is based on our understanding of the product at the time and is subject to change without notice. It is not complete unless all pages are included. It is not intended to be a recommendation to purchase an annuity and is not a representation regarding the suitability of any concept or product. You are strongly urged to consult with financial planning, tax, and legal advisors and must refer to each insurance company’s complete product information before making any decision. Annuities are subject to the terms and conditions of the specific contract issued, all rates are subject to change, and any guarantees are subject to the claims paying ability of the applicable insurance company.</p>
                        </div>

                        <header class="report__header">
                            <div class="report__header-product-name">{{ $product->carrier_product->name }} ({{ $product->income_benefit->name }})</div>
                            <div class="report__header-carrier-name">{{ $product->carrier_product->carrier->name }}</div>
                            <div class="report__header-carrier-logo" data-carrier-slug="{{ Str::slug( $product->carrier_product->carrier->name ) }}">&nbsp;</div>
                        </header>

                        <div class="report__content">
                            <div class="report__content-wrapper">
                                <h3>Hypothetical Income</h3>
                                <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eu ligula id est tincidunt malesuada. Quisque ultrices volutpat porta. Ut pharetra leo quis lorem imperdiet auctor. Aliquam ut pellentesque justo. Praesent urna turpis, fermentum vitae lacus a, venenatis cursus augue. Duis vitae consectetur nisi, nec dictum sapien. Cras vitae arcu a risus pellentesque tincidunt. Proin risus risus, porta id sapien non, interdum dictum magna. Morbi eu pulvinar sapien, et placerat mauris. Etiam nec ornare augue. Duis ullamcorper mi vel tellus vulputate varius. Ut cursus nulla vel rutrum commodo. Mauris et feugiat lectus. Donec sodales arcu et ex rhoncus faucibus. Sed ac ultricies diam. Phasellus ut vestibulum tellus, et ullamcorper ipsum.</p>
                                <p>Curabitur faucibus molestie ligula, eget pharetra orci convallis vel. Integer volutpat hendrerit augue vitae aliquam. Sed aliquam purus eget auctor eleifend. Etiam quis viverra nunc. Pellentesque mi velit, varius vel est ac, pulvinar fermentum orci. Duis quis nisl id urna finibus vehicula et ut velit. Pellentesque sodales magna diam, quis iaculis erat luctus ut. Quisque velit enim, pulvinar non finibus et, elementum eu nibh.</p>
                            </div>
                            <div class="report__content-wrapper">
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
                                        @foreach ( $illustration->analysis_data as $row )
                                            <tr class="{{ ( ( $row->income ) ? 'has-income' : '' ) }}">
                                                <td>{{ $row->year }}</td>
                                                <td>{{ $row->primary_age }}</td>
                                                <td>${{ number_format( $row->account_value, 2 ) }}</td>
                                                <td>${{ number_format( $row->income_benefit_base, 2 ) }}</td>
                                                <td>${{ number_format( $row->interest_amount, 2 ) }} ({{ $row->interest_percent }}%)</td>
                                                <td>${{ number_format( $row->fees, 2 ) }}</td>
                                                <td class="income">${{ number_format( $row->income, 2 ) }}</td>
                                                <td>${{ number_format( $row->death_benefit, 2 ) }}</td>
                                            </tr>
                                        @endforeach
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
                    </section>
                    @break

                    @endif
                @endforeach
            @endif
        @endforeach
    </article>
@endsection
