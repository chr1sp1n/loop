<?php 

namespace PhpLoop;

class PhpLoop {

	protected $tasks = [];
	protected $listners = [];
	protected $eventsCallbacks = [];

	public function __construct(){	
		// if (function_exists('pcntl_signal')){
		// 	pcntl_signal(SIGINT, function(){
		// 		echo "Test";
		// 		$this->trigger('SIGINT');
		// 	});
		// }
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

	public function on(string $callbackName, $callback){
		if(!isset($eventsCallbacks[$callbackName])) $eventsCallbacks[$callbackName] = [];
		$eventsCallbacks[$callbackName][] = $callback;
	}

	public function trigger($eventName, $args = null){
		if(isset($eventsCallbacks[$eventName]) && !empty($eventsCallbacks[$eventName])){
			foreach ($eventsCallbacks[$eventName] as $function) {
				if($args){
					$function($eventName, $args);
				}else{
					$function($eventName);
				}
			}
		}
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