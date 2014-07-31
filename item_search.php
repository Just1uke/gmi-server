<?php
	$itemName = isset($_GET['itemName']) ? $_GET['itemName'] : exit();
	$ql = isset($_GET['ql']) ? $_GET['ql'] : 300;

	$url = "http://test.cidb.botsharp.net/?bot=AO Market Mods&search=" . $itemName . "&output=json&ql=" . $ql . "&version=1.2&max=1";
	$r = curl_init($url);
	curl_exec($r);
	curl_close($r);
?>