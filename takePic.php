<?php

require_once('db.php');
require_once('config.inc.php');

$file = md5(time()).'.jpg';
$filename_photo = $config['folders']['images'] . DIRECTORY_SEPARATOR . $file;
$filename_thumb = $config['folders']['thumbs'] . DIRECTORY_SEPARATOR . $file;

if($config['dev'] === false) {
    $shootimage = shell_exec(
        sprintf(
            $config['take_picture']['cmd'],
            $filename_photo
        )
    );
    if(strpos($shootimage, $config['take_picture']['msg']) === false) {
        die(json_encode(array('error' => true)));
    }
} else {
    $devImg = array('dev.jpg', 'dev2.jpg');
    copy(
        $devImg[array_rand($devImg)],
        $filename_photo
    );
}

// If in auto print mode ;)
if  ( $config['auto_print'] == true)
{
// http://192.168.1.78/print.php?filename=7c124e8193c87fc7790668657a32e8f5.jpg


$filename = trim(basename($file));
if($pos = strpos($filename, '?')) {
    $parts = explode('?', $filename);
    $filename = array_shift($parts);
}
$filename_source = $config['folders']['images'] . DIRECTORY_SEPARATOR . $filename;
$filename_print = $config['folders']['print'] . DIRECTORY_SEPARATOR . $filename;
$filename_codes = $config['folders']['qrcodes'] . DIRECTORY_SEPARATOR . $filename;
$filename_thumb = $config['folders']['thumbs'] . DIRECTORY_SEPARATOR . $filename;
$status = false;
// exit with error
if(!file_exists($filename_source)) {
    echo json_encode(array('status' => sprintf('file "%s" not found', $filename_source)));
}
// print
if(file_exists($filename_source)) {
    // copy and merge
    if(!file_exists($filename_print)) {
	if ($config['print_qr']) {
	        // create qr code
        	if(!file_exists($filename_codes)) {
	            include('resources/lib/phpqrcode/qrlib.php');
       		    $url = 'http://'.$_SERVER['HTTP_HOST'].'/download.php?image=';
            	    QRcode::png($url.$filename, $filename_codes, QR_ECLEVEL_H, 10);
        	}
        	// merge source and code
        	list($width, $height) = getimagesize($filename_source);
        	$newwidth = $width + ($height / 2);
        	$newheight = $height;
	        $source = imagecreatefromjpeg($filename_source);
        	$code = imagecreatefrompng($filename_codes);
	        $print = imagecreatetruecolor($newwidth, $newheight);
        	imagefill($print, 0, 0, imagecolorallocate($print, 255, 255, 255));
	        imagecopy($print, $source , 0, 0, 0, 0, $width, $height);
        	imagecopyresized($print, $code, $width, 0, 0, 0, ($height / 2), ($height / 2), imagesx($code), imagesy($code));
	        imagejpeg($print, $filename_print);
        	imagedestroy($print);
	        imagedestroy($code);
        	imagedestroy($source);
    	}
    	// print image
    	// fixme: move the command to the config.inc.php
    	$printimage = shell_exec(
        	sprintf(
	            $config['composite']['cmd'],
		    $config['watermark'],
		    $filename_source,
		    $filename_print
        	)
    	);
    	$printimage = shell_exec(
        	sprintf(
            		$config['print']['cmd'],
            		$filename_print
        	)
    	);
    }
    else
    {
	die(json_encode(array('error' => true)));
    }
}

}

// image scale
list($width, $height) = getimagesize($filename_photo);
$newwidth = 500;
$newheight = $height * (1 / $width * 500);
$source = imagecreatefromjpeg($filename_photo);
$thumb = imagecreatetruecolor($newwidth, $newheight);
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
imagejpeg($thumb, $filename_thumb);

// insert into database
$images[] = $file;
file_put_contents('data.txt', json_encode($images));

// send imagename to frontend
echo json_encode(array('success' => true, 'img' => $file));
