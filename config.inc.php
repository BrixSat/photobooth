<?php

$config = Array();
$config['os'] = (DIRECTORY_SEPARATOR == '\\') || (strtolower(substr(PHP_OS, 0, 3)) === 'win') ? 'windows' : 'linux';
$config['dev'] = false	;
// the print button
$config['use_print'] = true;
// print without button
$config['auto_print'] = false;
$config['use_qr'] = true;
$config['print_qr'] = false;
$config['watermark'] = "/var/www/html/resources/img/logo_ge.png";

// FOLDERS
// change the folders to whatever you like
$config['folders']['images'] = 'images';
$config['folders']['thumbs'] = 'thumbs';
$config['folders']['qrcodes'] = 'qrcodes';
$config['folders']['print'] = 'print';

// GALLERY
// should the gallery list the newest pictures first?
$config['gallery']['newest_first'] = true;

// Where is the code installed
$config['baseUrl'] = "";

// The inactivity seconds
$config['idletimeout'] = 3;

// After a message is show it will never show another one before this value
$config['minTimeBetweenMessages'] = 4;
// Max time beetween messages
$config['maxTimeBetweenMessages'] = 5;

// Text fadeOut and fadeIn animation in seconds

$config['fadeInText'] = 1;
$config['fadeOutText'] = 2;


// LANGUAGE
// possible values: en, de
$config['language'] = 'pt';

// COMMANDS and MESSAGES
switch($config['os']) {
    case 'windows':
        $config['take_picture']['cmd'] = 'digicamcontrol\CameraControlCmd.exe /capture /filename %s';
        $config['take_picture']['msg'] = 'Photo transfer done.';
        $config['print']['cmd'] = 'mspaint /pt "%s"';
        $config['print']['msg'] = '';
        break;
    case 'mac':
        $config['take_picture']['cmd'] = 'sudo gphoto2 --capture-image-and-download --filename=%s images';
        $config['take_picture']['msg'] = 'New file is in location';
        $config['print']['cmd'] = 'echo sudo lp -o landscape -o fit-to-page %s';
        $config['print']['msg'] = '';
    case 'linux':
    default:
        $config['take_picture']['cmd'] = 'sudo /usr/bin/fswebcam --no-banner -r 1920x1080 --jpeg 100 %s 2>&1 ';
        $config['take_picture']['msg'] = 'Writing JPEG image to';
        $config['composite']['cmd'] = 'sudo /usr/bin/composite -gravity SouthEast %s %s %s';
        $config['composite']['msg'] = 'New file is in location';
        $config['print']['cmd'] = 'sudo lp -d MITSUBISHI_CPD80D -o landscape -o fit-to-page %s';
        $config['print']['msg'] = '';
        break;
}

// DON'T MODIFY
// preparation
foreach($config['folders'] as $directory) {
    if(!is_dir($directory)){
        mkdir($directory, 0777);
    }
}
