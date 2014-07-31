<?php
	require_once("includes/globals.php");
	if(ENABLE_LOCAL_OVERRIDES && file_exists("./overrides" . $_SERVER['SCRIPT_URL'])){
		header("Content-Type: " . getContentType(substr($_SERVER['SCRIPT_URL'], strrpos($_SERVER['SCRIPT_URL'], '.')+1)));
		//Should seriously look into this more to see if it's a security issue.
		echo file_get_contents(LOCAL_OVERRIDES_LOCATION . $_SERVER['SCRIPT_URL']);
		exit;
	} else if(ENABLE_LOCAL_CACHE && !is_dir(LOCAL_CACHE_LOCATION . $_SERVER['SCRIPT_URL'])){
		//If the file doesn't exist or it's too old, we need to refresh it. 
		if(file_exists(LOCAL_CACHE_LOCATION . $_SERVER['SCRIPT_URL']) && filemtime(LOCAL_CACHE_LOCATION . $_SERVER['SCRIPT_URL']) > time() - LOCAL_CACHE_EXPIRATION){
			header("Content-Type: " . getContentType(substr($_SERVER['SCRIPT_URL'], strrpos($_SERVER['SCRIPT_URL'], '.')+1)));
			if(ENABLE_BROWSER_CACHE && shouldCache($_SERVER['SCRIPT_URL']))
				header('Expires: '  . BROWSER_CACHE_EXPIRATION);
			//Should seriously look into this more to see if it's a security issue.
			echo file_get_contents(LOCAL_CACHE_LOCATION . $_SERVER['SCRIPT_URL']);
			exit;
		}
	}

	//This should probably be static to prevent an open proxy.
	$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_URL'];
	$url .= isset($_SERVER['QUERY_STRING']) ? ("?" . $_SERVER['QUERY_STRING']) : "";
	
	//Allow multiple connection retries since a large number of cURL requests sometimes causes individual connections to time out. 
	for($connTry = 0; $connTry <= 3; $connTry++){
		$r = curl_init($url);

		$requestBody = @file_get_contents('php://input');
		$requestOptions = array(CURLOPT_COOKIE => implode('; ', $_COOKIE), 
			CURLOPT_CONNECTTIMEOUT => 120, 
			CURLOPT_TIMEOUT => 120, 
			CURLOPT_FOLLOWLOCATION => true, 
			CURLOPT_RETURNTRANSFER => true);

		$httpHeaders = array("X-Anarchy-Cookie: " . $_SERVER['HTTP_X_ANARCHY_COOKIE'],
							 "X-Anarchy-CharacterID: " . $_SERVER['HTTP_X_ANARCHY_CHARACTERID'],
							 "X-Anarchy-ServerID: " . $_SERVER['HTTP_X_ANARCHY_SERVERID'],
							 "X-Anarchy-Appearance: " . $_SERVER['HTTP_X_ANARCHY_APPEARANCE']);
		if(isset($_SERVER['CONTENT_TYPE'])){
			$httpHeaders[] = "Content-type: " . $_SERVER['CONTENT_TYPE'];
		}
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
			$httpHeaders[] = "X-Requested-With: " . $_SERVER['HTTP_X_REQUESTED_WITH'];
		}

		if(strlen($requestBody) > 1){
			$requestOptions[CURLOPT_POST] = true;
			$requestOptions[CURLOPT_POSTFIELDS] = $requestBody;
		} else {
			if($_SERVER['REQUEST_METHOD'] === 'POST'){
				$requestOptions[CURLOPT_POST] = true;
				//$_POST has to be turned into a URL because CURL loves to help. 
				$requestOptions[CURLOPT_POSTFIELDS] = http_build_query($_POST);
			} else if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$requestOptions[CURLOPT_HTTPGET] = true;
			}
		}

		curl_setopt_array($r, $requestOptions);
		curl_setopt($r, CURLOPT_HTTPHEADER, $httpHeaders);
		
		$responseBody = curl_exec($r);

		if(substr($_SERVER['SCRIPT_URL'], 0, strlen('/marketLIVE/item_orders/')) == '/marketLIVE/item_orders/'){
			header("Content-Type: text/plain");
			$doc = new DOMDocument();
			@$doc->loadHTML($responseBody);
			$sellOrders = array();
			foreach($doc->getElementById('sellOrderTable')->getElementsByTagName('tr') as $sellOrder){
				$sellOrders[] = array_filter(array_map('trim', explode("\n", $sellOrder->nodeValue)));
			};
			array_shift($sellOrders);
			$itemNumber = substr($_SERVER['SCRIPT_URL'], strpos($_SERVER['SCRIPT_URL'], '/marketLIVE/item_orders/') + strlen('/marketLIVE/item_orders/'));
			foreach($doc->getElementById('sellOrderTable')->getElementsByTagName('input') as $index => $sellOrder){
				$sellOrders[$index][0] = $itemNumber;
				$sellOrders[$index][] = $sellOrder->getAttribute('value');
			};
			// var_dump($sellOrders); exit;
			if($sellOrders[0][0] != "No results."){
				foreach($sellOrders as $sellOrder){
					new SellOrder($sellOrder);
				}
			}
		}

		if(sizeof(curl_getinfo($r, CURLINFO_CONTENT_TYPE)) > 0){
			header("Content-Type: " . curl_getinfo($r, CURLINFO_CONTENT_TYPE));
		}

		//If we successfully connect, don't reconnect.
		if(curl_getinfo($r, CURLINFO_HTTP_CODE) != 0){
			$connTry = 100;
		} else {
			//Sleep a bit before we try again.
			sleep($connTry); 
		}

		curl_close($r);
	}

	//Save our results back to cache.
	if(ENABLE_LOCAL_CACHE && shouldCache($_SERVER['SCRIPT_URL'])){
		//Create the directory tree to the file if we need to. 
		$filePath = LOCAL_CACHE_LOCATION . substr($_SERVER['SCRIPT_URL'], 0, strrpos($_SERVER['SCRIPT_URL'], "/") + 1);
		if(!file_exists($filePath)){
			if (!mkdir($filePath, 0777, true)) {
				error_log("Couldn't create directory " . $filePath);
			}
		}

		//If the file isn't a directory and it's older than LOCAL_CACHE_EXPIRATION, save it to the server. 
		if(!is_dir(LOCAL_CACHE_LOCATION . $_SERVER['SCRIPT_URL'])){
			if(@filemtime(LOCAL_CACHE_LOCATION . $_SERVER['SCRIPT_URL']) < time() - LOCAL_CACHE_EXPIRATION){
				$fp = fopen(LOCAL_CACHE_LOCATION . $_SERVER['SCRIPT_URL'], "w");
				fwrite($fp, $responseBody);
				fclose($fp); 
			}
		}
	}

	//Debug shit that can be removed.
	$filePath = "./returns" . substr($_SERVER['SCRIPT_URL'], 0, strrpos($_SERVER['SCRIPT_URL'], "/") + 1);
	if(!file_exists($filePath)){
		if (!mkdir($filePath, 0777, true)) {
			error_log("Couldn't create directory " . $filePath);
		}
	}
	if(!is_dir("./returns" . $_SERVER['SCRIPT_URL'])){
		$fp = fopen("./returns" . $_SERVER['SCRIPT_URL'], "w");
		fwrite($fp, $responseBody);
		fclose($fp); 
	}


	echo $responseBody;

?>


<?php
	function getContentType($extension){
		switch($extension){
			case "png":
				return "image/png";
				break;
			case "gif":
				return "image/gif";
				break;
			case "js":
				return "application/javascript";
				break;
			case "css":
				return "text/css";
				break;
			default: 
				return "text/plain";
				break;
		}
	}

	//Files to not cache.
	function shouldCache($fileName){
		$toCache = array('png', 'gif', 'js', 'css', 'txt');
		$fileType = substr($fileName, strrpos($fileName, '.')+1);
		if(in_array($fileType, $toCache) &&  !file_exists(LOCAL_OVERRIDES_LOCATION . $fileName))
			return true;
		return false;
	}

?>