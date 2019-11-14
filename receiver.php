#!/usr/bin/php
<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_PARSE);

include __DIR__ . '/src/PhpLoop.php';
include __DIR__ . '/src/Udp.php';

use PhpLoop\PhpLoop;
use Udp\Broadcast;

$loop = new PhpLoop();
$broadcast = new Broadcast(1981);
$broadcast->init();

$loop->addTask('receive', 
	function() use($broadcast){
		$received = $broadcast->receive();
		if(isset($received->bytes) && $received->bytes !== false) {
			echo "Received: " . $received->bytes . ' bytes - ' . $received->message . PHP_EOL;
		}else{
			$error = $broadcast->getError();
			if($error->code > 0 && $error->code != 11){
				echo "[ERRO] " . $error->code . ' - ' . $error->message . PHP_EOL;
			}
		}
	}
);


$loop->run();