<?php
// Load the ml-php library
require_once 'vendor/autoload.php';

use Phpml\Regression\LeastSquares;

// Define the data set
$data = [
    [ 'analysis_id' => 1057333283, 'premiumAmount' => 50000, 'deferralPeriod' => 10, 'annualIncome' => 5400 ],
    [ 'analysis_id' => 1057333283, 'premiumAmount' => 50000, 'deferralPeriod' => 15, 'annualIncome' => 6110 ],
    [ 'analysis_id' => 1057333283, 'premiumAmount' => 50000, 'deferralPeriod' => 20, 'annualIncome' => 6481.19 ],
    [ 'analysis_id' => 1057333283, 'premiumAmount' => 250000, 'deferralPeriod' => 10, 'annualIncome' => 27000 ],
    [ 'analysis_id' => 1057333283, 'premiumAmount' => 250000, 'deferralPeriod' => 18, 'annualIncome' => 30112.05 ],
    [ 'analysis_id' => 1057333283, 'premiumAmount' => 250000, 'deferralPeriod' => 20, 'annualIncome' => 32405.95 ],
    [ 'analysis_id' => 1057333283, 'premiumAmount' => 500000, 'deferralPeriod' => 10, 'annualIncome' => 54000 ],
    [ 'analysis_id' => 1057333283, 'premiumAmount' => 500000, 'deferralPeriod' => 20, 'annualIncome' => 64811.90 ],
];

// Define the function to calculate the monthly income generated using the regression model
function predictPremium($incomeAmount, $deferralPeriod)
{
    global $data;

    // Define the independent variables (premiumAmount and deferralPeriod)
    $x = array_map(function ($d) {
        return [$d['annualIncome'], $d['deferralPeriod']];
    }, $data);

    // Define the dependent variable (annualIncome)
    $y = array_column($data, 'premiumAmount');

    print_r( $x );
    print_r( $y );

    // Use linear regression to fit a model to the data
    $regression = new LeastSquares();
    $regression->train($x, $y);

    // Use the model to predict the monthly income generated for the given premium amount and deferral period
    $prediction = $regression->predict([[$incomeAmount, $deferralPeriod]]);

    // Return the predicted monthly income rounded to two decimal places
    return round($prediction[0], 2);
}

if ( count( $argv ) >= 3 ) {
    $requiredPremium = predictPremium(floatval( $argv[ 1 ] ), intval( $argv[ 2 ] ));

    // Print the result to the screen
    echo sprintf( '[%s, deferred %d years] Predicted required premium generated: $%s', number_format( floatval( $argv[ 1 ] ), 2 ), intval( $argv[ 2 ] ), number_format( floatval( $requiredPremium ), 2 ) ) . PHP_EOL;
} else {
    echo 'Usage: <file> [income] [deferral].' . PHP_EOL;
    echo 'Example: <file> 10000 10  (for a 10 year deferral on a $10,000 annual income' . PHP_EOL;
}
