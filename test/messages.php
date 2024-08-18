<?php

require '../vendor/autoload.php';

use Inn\Validator\DataObject;

$dataObject = new DataObject(
    [
        'name' => 'izisaurio',
        'email' => 'izi.isaac@gmail',
        'value' => 'value',
        'second' => 'valuess'
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
            'isNullable' => true,
        ],
        'email' => [
            'isEmail' => true,
            'label' => 'Email',
        ],
        'second' => [
            'equal' => '@value',
            'notEqual' => '@value',
        ],
    ],
    DataObject::getDefaultMessages()['en'], 'en'
);

$result = $dataObject->validate();

var_dump($result);

if (!$result) {
	var_dump($dataObject->getErrors());
}