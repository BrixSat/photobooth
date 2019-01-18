<?php

$input = array(
	"Olá!",
    "Tira uma selfie aqui!",
    "Como estás?",
    "Gente gira!",
    "Casa comigo!"
	);
$array = ["message" => $input[array_rand($input)]];

echo json_encode($array);

