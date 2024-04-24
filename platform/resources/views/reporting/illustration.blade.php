@extends('layouts.pdf')

@section('content')
    <article class="reporting" style="max-width: 50vw; min-width: 992px; margin-left: auto; margin-right: auto; border-left: 1px #000 solid; border-right: 1px #000 solid;">
        <section class="report report--cover page">
            <div class="report__cover">
                <h1>Annuity Association</h1>

                <p>Report prepared for {{ $annuitant[ 'owner_name_first' ] }} {{ $annuitant[ 'owner_name_last' ] }}</p>

                <figure>
                    <img src="{{ asset( 'assets/images/illustrations/2x/reporting.png' ) }}" alt="An illustration of a financial report">
                </figure>
            </div>
        </section>

        <section class="report page">
            <div class="report__client data-points">
                <h3>Client Information</h3>

                <ul class="report__client-data data-points-list">
                    <li class="report__client-data-point data-point">
                        <label>Contract Type</label>
                        <span>{{ $annuitant[ 'annuity_type' ] }}</span>
                    </li>
                    <li class="report__client-data-point data-point">
                        <label>Investment Amount</label>
                        <span>{{ $settings[ 'premium' ] }}</span>
                    </li>
                    <li class="report__client-data-point data-point">
                        <label>Deferral</label>
                        <span>{{ $settings[ 'deferral' ] }}</span>
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
                                                {{ $row->income }}
                                                @break
                                            </div>
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
            @if ( $illustrations[ 'hypothetical' ] )
                @foreach ( $illustrations[ 'hypothetical' ] as $illustration )
                    @if ( ( $product->analysis_data_id === $illustration->analysis_request->analysis_data_id ) && ( $illustration->analysis_data ) )
                    <section class="report page">
                        <header class="report__header">
                            <div class="report__header-product-name">{{ $product->carrier_product->name }}</div>
                            <div class="report__header-carrier-name">{{ $product->carrier_product->carrier->name }}</div>
                            <div class="report__header-carrier-logo" data-carrier-slug="{{ Str::slug( $product->carrier_product->carrier->name ) }}">&nbsp;</div>
                        </header>

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
                    </section>

                    @break
                    @endif
                @endforeach
            @endif

            @if ( $illustrations[ 'hypothetical' ] )
                @foreach ( $illustrations[ 'hypothetical' ] as $illustration )
                    @if ( ( $product->analysis_data_id === $illustration->analysis_request->analysis_data_id ) && ( $illustration->analysis_data ) )
                    <section class="report page">
                        <header class="report__header" data-carrier-slug="{{ Str::slug( $product->carrier_product->carrier->name ) }}">
                            {{ $product->carrier_product->name }}<br />
                            {{ $product->carrier_product->carrier->name }}
                        </header>

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
                    </section>
                    @break

                    @endif
                @endforeach
            @endif
        @endforeach
    </article>
@endsection
