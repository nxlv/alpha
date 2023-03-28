<?php
require_once __DIR__ . '/../vendor/autoload.php';

$tf = new TensorFlow\TensorFlow();
$sess = $tf->session();
var_dump($sess->devices());
