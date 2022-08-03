<?php

require '../vendor/autoload.php';

use Inn\Validator\FileValue, RequestParams\Server, RequestParams\Files;

if (Server::key('REQUEST_METHOD') === 'POST') {
	$file = new FileValue(Files::key('file'), 'file');
	var_dump($file->isUploaded());
	$file->isOk();
	if (!$file->validate()) {
		var_dump($file->getErrors());
	} else {
		var_dump($file->prop);
		var_dump($file->prop->name, $file->prop->type, $file->extension);
		//$file->move('assets/newFile.jpg');
	}
	exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<form method="post" enctype="multipart/form-data">
		<input type="file" name="file" />
		<input type="submit" value="Send" />
	</form>
</body>
</html>