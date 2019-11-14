<?php 

namespace PhpLoop;

class PhpLoop {

	protected $tasks = [];
	protected $listners = [];

	public function __construct(){	
	}

	public function addTask(string $name, $function){
		if(array_key_exists($name, $this->tasks)) return false;
		return $this->tasks[$name] = $function;
	}

	public function removeTask(string $name){
		if(!array_key_exists($name, $this->tasks)) return false;
		unset($this->tasks[$name]);
		return true;
	}

	public function addListner(string $name, $function){
		if(array_key_exists($name, $this->listners)) return false;
		return $this->listners[$name] = $function;
	}

	public function removeListner(string $name){
		if(!array_key_exists($name, $this->listners)) return false;
		unset($this->tasks[$name]);
		return true;
	}

	public function run(){
		while(true){
			foreach ($this->tasks as $task) {
				$task();
			}
		}
	}

}

?>