<?php
	//Highly recommended. Speeds up market load times and lowers network usage.
	define("ENABLE_BROWSER_CACHE", true);
	define("BROWSER_CACHE_EXPIRATION", gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));



	//Recommended. Reduces the number of external calls to AO's servers.
	define("ENABLE_LOCAL_CACHE", true);
	define("LOCAL_CACHE_LOCATION", "./cache"); //No trailing slash. Example: ./cache
	define("LOCAL_CACHE_EXPIRATION", 60*60*12); //Time (in seconds) before refreshing a file via cache.



	//Highly recommended, this allows you to put in your own code.
	define("ENABLE_LOCAL_OVERRIDES", true);
	//Location of where overrides can be found.
	define("LOCAL_OVERRIDES_LOCATION", "./overrides"); // No trailing slash. Example: ./overrides



	/*
	*	Data Handlers
	*/

		//MySQL Data Handler
		define("ENABLE_DB_USAGE", false);
		define("DATA_HANDLER", 'data_handlers/mysql.class.php');
		define("MYSQL_HOST", "");
		define("MYSQL_USERNAME", "");
		define("MYSQL_PASSWORD", "");
		define("MYSQL_DATABASE", "");


?>