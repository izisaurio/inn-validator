<?php

require '../vendor/autoload.php';

use JsonToArray\Json;
use Inn\Validator\DataValue;
use Inn\Validator\DataObject;

$json = new Json('assets/json.json');

$value = new DataValue('i', 'nombre');

$result = $value
	->isRequired()
	->isSafeText()
	->minLength(2)
	->maxLength(6)
	->validate();

if (!$result) {
	var_dump($value->getErrors());
}

$date = new DataValue('2022-01-01', 'fecha');

$result = $date
	->isRequired()
	->lessDate('2030-01-01', 'Future')
	->validate();

if (!$result) {
	var_dump($date->getErrors());
}

$dataObject = new DataObject(
	[
		'name' => 'izisaurio',
		'email' => 'izi.isaac@gmail',
	],
	[
		'name' => [
			'label' => 'Name',
			'isSafeText' => true,
			'maxLength' => 5,
		],
		'age' => [
			'isInt' => true,
			'max' => 25,
			'label' => 'Age',
		],
		'email' => [
			'isEmail' => true,
			'label' => 'Email',
		],
	],
	$json->data
);

$result = $dataObject->validate();

var_dump($result);

if (!$result) {
	var_dump($dataObject->getErrors());
}
