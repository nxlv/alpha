<?php

// Load the PHP-ML library
require_once 'vendor/autoload.php';

use Phpml\Regression\LeastSquares;

// Define the training data
$data = [
    ['premiumAmount' => 200000, 'deferralPeriod' => 10, 'monthlyIncome' => 251],
    ['premiumAmount' => 100000, 'deferralPeriod' => 10, 'monthlyIncome' => 153],
    ['premiumAmount' => 350000, 'deferralPeriod' => 10, 'monthlyIncome' => 402],
    ['premiumAmount' => 1000000, 'deferralPeriod' => 10, 'monthlyIncome' => 913],
    ['premiumAmount' => 575000, 'deferralPeriod' => 10, 'monthlyIncome' => 721],
    ['premiumAmount' => 74999, 'deferralPeriod' => 10, 'monthlyIncome' => 102]
];

// Extract the features (premiumAmount and deferralPeriod) and the labels (monthlyIncome) from the data
$features = array_map(function($d) {
    return [$d['premiumAmount'], $d['deferralPeriod']];
}, $data);

$labels = array_column($data, 'monthlyIncome');

// Train a linear regression model on the training data
$regression = new LeastSquares();
$regression->train($features, $labels);

// Use the trained model to predict the monthly income for a given premium amount and deferral period
$premiumAmount = 500000;
$deferralPeriod = 5;

$predictedMonthlyIncome = $regression->predict([$premiumAmount, $deferralPeriod]);

echo "For a premium amount of $premiumAmount and a deferral period of $deferralPeriod, the predicted monthly income is $predictedMonthlyIncome";
