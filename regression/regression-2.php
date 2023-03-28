<?php


// Define the data
$data = [
    [ 'premiumAmount' => 50000, 'deferralPeriod' => 10, 'monthlyIncome' => 5400 ],
    [ 'premiumAmount' => 50000, 'deferralPeriod' => 20, 'monthlyIncome' => 6481.19 ],
    [ 'premiumAmount' => 250000, 'deferralPeriod' => 10, 'monthlyIncome' => 27000 ],
    [ 'premiumAmount' => 250000, 'deferralPeriod' => 20, 'monthlyIncome' => 32405.95 ],
    [ 'premiumAmount' => 500000, 'deferralPeriod' => 10, 'monthlyIncome' => 54000 ],
    [ 'premiumAmount' => 500000, 'deferralPeriod' => 20, 'monthlyIncome' => 64811.90 ],
];

// Specify the premium amount and deferral period for the new annuity
$premiumAmount = 250000;
$deferralPeriod = 5;

// Initialize variables for calculating the monthly income estimate
$totalWeightedIncome = 0;
$totalWeight = 0;

// Calculate the weighted average of monthly incomes from the data
foreach ( $data as $annuity ) {
    // Calculate the weight for this annuity
    $weight = exp( -0.5 * pow( ( $annuity[ 'premiumAmount' ] - $premiumAmount ) / $premiumAmount, 2 ) ) *
        exp( -0.5 * pow( ( $annuity[ 'deferralPeriod' ] - $deferralPeriod ) / $deferralPeriod, 2 ) );

    // Add the weighted income to the total
    $totalWeightedIncome += $weight * $annuity[ 'monthlyIncome' ];
    $totalWeight += $weight;
}

// Calculate the weighted average monthly income estimate
$monthlyIncomeEstimate = $totalWeightedIncome / $totalWeight;

// Output the estimated monthly income
echo "Estimated monthly income for a premium amount of $premiumAmount and deferral period of $deferralPeriod: $monthlyIncomeEstimate";


