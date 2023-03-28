<?php
require_once __DIR__ . '/../vendor/autoload.php';

$tf = new TensorFlow\TensorFlow();
$ret =
	$tf->add(
		$tf->add(
			$tf->placeholder("x", TensorFlow\API::DOUBLE),
			$tf->placeholder("y", TensorFlow\API::DOUBLE)),
		$tf->constant(0.5));
TensorFlow\PrintGraph::print_graph($tf->graph);

$def = $tf->graph->export();

$tf = new TensorFlow\TensorFlow();
TensorFlow\PrintGraph::print_graph($tf->graph);

$tf->graph->import($def, "import");
TensorFlow\PrintGraph::print_graph($tf->graph);


$x = $tf->tensor([ord('a'),ord('b'),ord('c'),ord('d')], TensorFlow\API::INT8);
var_dump($x->value());
var_dump($x->bytes());
$x->setBytes("xyz_");
var_dump($x->value());
