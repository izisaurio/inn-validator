<?php

require '../vendor/autoload.php';

use JsonToArray\Json;
use Inn\Validator\DataValue;

$json = new Json('assets/json.json');

$value = new DataValue('i', 'nombre');

$result = $value->isArray()->validate();

if (!$result) {
    var_dump($value->getErrors());
}

$value = new DataValue([1, 2, 3], 'Array');

$result = $value->isArray()->validate();

var_dump($result);

$values = new StdClass();
$values->name = 'izisaurio';
$values->job = 'Developer';
$std = new DataValue($values, 'Object');
//$std = new DataValue('hello', 'Object');

$result = $std->isJson()->validate();

var_dump($std->getErrors());