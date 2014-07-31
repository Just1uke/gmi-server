<?php
	require_once('config.php');
	
	if(ENABLE_DB_USAGE){
		require_once(DATA_HANDLER);
	}

	foreach (scandir(dirname(__FILE__) . '/classes') as $filename) {
		$path = dirname(__FILE__) . '/classes/' . $filename;
		if (is_file($path) && substr($filename, -strlen('.class.php')) === '.class.php') {
			require_once($path);
		}
	}

	$globals = new Globals();

	class Globals {
		public $dataHandler; 
		function __construct(){
			$this->dataHandler = new DataHandler(); 
		}
	}
?>