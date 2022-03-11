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

$dataObject = new DataObject(
	[
		'name' => 'izisaurio',
		'age' => 30,
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
	'assets/json.json'
);

$result = $dataObject->validate();

var_dump($result);

if (!$result) {
	var_dump($dataObject->getErrors());
}
