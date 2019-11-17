<?php

namespace Udp;

class Broadcast{

	private $sock = null;
	private $verbose;
	private $broadcast_ip;
	private $port;
	
	public function __construct(int $port, string $broadcast_ip = '255.255.255.255', $verbose = false){
		$this->verbose = $verbose;
		$this->broadcast_ip = $broadcast_ip;
		$this->port = $port;
		//$this->init();
	}

	public function __destruct(){
		if($this->sock){
			\socket_close($this->sock);
		}
	}


	public function getError(){
		$error = new \stdClass();
		$error->code = \socket_last_error();
		$error->message = \socket_strerror($error->code);
		if($this->verbose){
			echo "[ERRO] " . $error->code . ' - ' . $error->message . PHP_EOL;
		}
		return $error;
	}

	public function init(){
		if( !( $this->sock = \socket_create(AF_INET, SOCK_DGRAM, 0) )){	
			$this->getError();
			return false;
		}
		if( !socket_set_option( $this->sock, SOL_SOCKET, SO_BROADCAST, 1 )){
			$this->getError();
			return false;
		}
		if( !socket_set_option( $this->sock, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 0, 'usec' => 250000] )){
			$this->getError();
			return false;
		}
		// if($this->verbose){
		// 	if( !socket_set_option( $this->sock, SOL_SOCKET, SO_DEBUG, 1 )){
		// 		$this->getError();
		// 		return false;
		// 	}			
		// }
		if( !@socket_bind( $this->sock, '0.0.0.0' , $this->port) ){
			$this->getError();
			return false;
		}
		return true;
	}

	public function send($message){
		$return = \socket_sendto( $this->sock, $message , \strlen($message) , 0 , $this->broadcast_ip , $this->port);
		if($return === false && $this->verbose){
			$this->getError();
		}
		return $return;
	}

	public function receive(){
		$data = new \stdClass();
		$return = socket_recvfrom($this->sock, $data->message, 4, 0, $data->remote_ip, $data->remote_port);
		if( $return === false && $this->verbose){
			$this->getError();
			return false;
		}
		$data->bytes = $return;
		return $data;
	}

}

?>