@extends('layouts.pdf')

@section('content')
    <article class="report">
        <section class="report report--cover page">
            <h1>Annuity Association</h1>
            <p>Report prepared for {{ $annuitant[ 'owner_name_first' ] }} {{ $annuitant[ 'owner_name_last' ] }}</p>
        </section>

        <section class="report page">
            <div class="report__client">
                <h3>Client Information</h3>

                <ul class="report__client-data">
                    <li class="report__client-data-point">
                        <label>Name</label>
                        <span>{{ $annuitant[ 'owner_name_first' ] }} {{ $annuitant[ 'owner_name_last' ] }}</span>
                    </li>
                    <li class="report__client-data-point">
                        <label>DOB / Age</label>
                        <span>{{ $annuitant[ 'owner_dob' ] }} ({{ $annuitant[ 'owner_age' ] }} years old)</span>
                    </li>
                </ul>
            </div>

            <div class="report__products">
                <h3>Products being analyzed</h3>

                <ul class="report__products-list">
                    @foreach ( $products as $product )
                        <li>
                            {{ $product->carrier_product->name }} ({{ $product->income_benefit->name }})
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        @foreach ( $hypothetical as $illustration )
        <section class="report page">
            <div class="report__illustration">
                <h3>Hypothetical Income</h3>

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
                        @foreach ( $illustration as $row )
                            <tr <?php echo ( ( $row[ 'income' ] ) ? 'class="has-income"' : '' ); ?>>
                                <td>{{ $row[ 'year' ] }}</td>
                                <td>{{ $row[ 'primary_age' ] }}</td>
                                <td>{{ $row[ 'account_value' ] }}</td>
                                <td>{{ $row[ 'income_benefit_base' ], 'USD' }}</td>
                                <td>{{ $row[ 'interest_amount' ] }} ({{ $row[ 'interest_percent' ] }}%)</td>
                                <td>{{ $row[ 'fees' ] }}</td>
                                <td class="income">{{ $row[ 'income' ] }}</td>
                                <td>{{ $row[ 'death_benefit' ] }}</td>
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
        </section>
        @endforeach
    </article>
@endsection
