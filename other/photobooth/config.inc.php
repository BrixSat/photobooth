<?php

$config = Array();
$config['os'] = (DIRECTORY_SEPARATOR == '\\') || (strtolower(substr(PHP_OS, 0, 3)) === 'win') ? 'windows' : 'linux';
$config['dev'] = false;

// FOLDERS
// change the folders to whatever you like
$config['folders']['images'] = 'images';
$config['folders']['thumbs'] = 'thumbs';
$config['folders']['qrcodes'] = 'qrcodes';
$config['folders']['print'] = 'print';


// Where is the code installed
$config['baseUrl'] = "/photobooth/";

// The inactivity seconds
$config['idletimeout'] = 3;

// After a message is show it will never show another one before this value
$config['minTimeBetweenMessages'] = 40;
// Max time beetween messages
$config['maxTimeBetweenMessages'] = 5;

// Text fadeOut and fadeIn animation in seconds

$config['fadeInText'] = "'slow'";
$config['fadeOutText'] = "'slow'";
$config['maxTextRotation'] = 70;


// LANGUAGE
// possible values: en, de
$config['language'] = 'en';


// Webcam settings

$config['webcamWidth'] =1280;
$config['webcamHeight'] =720;
$config['webcamQuality'] =90;


// DON'T MODIFY
// preparation
foreach($config['folders'] as $directory) {
    if(!is_dir($directory)){
        mkdir($directory, 0777);
    }
}