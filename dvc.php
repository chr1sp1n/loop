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
if(!$broadcast->init()) {
	exit(1);
}

$loop->on('error', function($event, $error) use($broadcast){
	var_dump($event, $error);
});

$loop->on('tlg-received', function($event, $tlg) use($broadcast){
	var_dump($event, $tlg);
});

$loop->on('SIGINT', function($event) use($broadcast){
	echo $event . " - TEST!" . PHP_EOL;
	$loop->removeTask('receiver');
	unset($broadcast);
	exit(1);
});

$loop->addTask('receiver', function() use($loop, $broadcast){
	$tlg = $broadcast->receive();
	if(isset($tlg->bytes) && $tlg->bytes !== false) {
		$loop->trigger('tlg-received', $tlg);
	}else{
		$error = $broadcast->getError();
		if($error->code !== 11){
			$loop->trigger('error', null);
		}
	}
	sleep(.1);
});


$loop->run();