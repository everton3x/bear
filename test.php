<?php

require './vendor/autoload.php';

$data = [
	[
		'id' => 1,
		'name' => 'Everton',
		'money' => 10000
	],
	[
		'id' => 1,
		'name' => 'Everton',
		'money' => 10000
	],
	[
		'id' => 1,
		'name' => 'Everton',
		'money' => 10000
	]
];
//print_r($data);
$ao = new ArrayObject($data);
print_r($ao);
