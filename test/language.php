<?php

require '../vendor/autoload.php';

use JsonToArray\Json;
use Inn\Validator\DataValue;
use Inn\Validator\DataObject;

$json = new Json('assets/json.json');

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
			'label' => ['es' => 'Nombre', 'en' => 'Name']
		],
		'age' => [
			'isInt' => true,
			'max' => 25,
			'label' => 'Age',
		],
		'email' => [
			'isEmail' => true,
			'label' => 'Email',
			'label' => ['es' => 'Correo', 'en' => 'Email']
		],
	],
	$json->data,
	'es'
);

$result = $dataObject->validate();

var_dump($result);

if (!$result) {
	var_dump($dataObject->getErrors());
}
