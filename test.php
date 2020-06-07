<?php

require './vendor/autoload.php';

$data = [
	[
		'id' => 1,
		'name' => 'Everton',
		'money' => 10000
	],
	[
		'id' => 2,
		'name' => 'Marlise',
		'money' => 5000
	],
	[
		'id' => 3,
		'name' => 'Arthur',
		'money' => 100
	]
];
//print_r($data);

$df = new Bear\DataFrame($data);

//var_dump($df->line('0:2'));
//var_dump($df->line('0,2'));
//var_dump($df->line([1,2]));
//print_r($df->columns('0:2'));
//print_r($df->columns('id:money'));
//print_r($df->columns('0,2'));
//print_r($df->columns('id,money'));
//print_r($df->cell(1, 'name'));