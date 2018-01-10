<?php
require_once('config.inc.php');

$filename = $_GET['filename'];
include('resources/lib/phpqrcode/qrlib.php');
$url = 'http://'.$_SERVER['HTTP_HOST'].'/'. $config['baseUrl'].'/download.php?image=';
QRcode::png($url.$filename, false, QR_ECLEVEL_H, 10);