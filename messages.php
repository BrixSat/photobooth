<?php

$input = array(
	"Vai uma foto?",
	"Anda até aqui ao espelho!",
	"Ui ui beleza!",
	"Cucu!",
	"Que me dizes a uma selfie?",
	"Que beleza!",
	"Com tanta beleza não sei se desmaio!"
	);
$array = ["message" => $input[array_rand($input)]];

echo json_encode($array);

