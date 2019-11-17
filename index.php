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

$loop->addTask('send', 
	function() use($broadcast){
		$message = 'test';
		$sent = $broadcast->send($message);
		echo 'Sent: ' . $sent . ' bytes - ' . $message . PHP_EOL;
		sleep(1);
	}
);

var_dump(SOL_UDP);

$loop->run();