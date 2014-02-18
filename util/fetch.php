<?php

function fetchUrl($url, $referer = false)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 100);
	curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 10);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
	if ($referer !== false)
		curl_setopt($ch, CURLOPT_REFERER, $referer);
	//We are chrome, really, you can trust me on this one! (Useragent filterin is annoying)
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36");
	
	curl_setopt($ch, CURLOPT_URL, $url);
	$data = curl_exec($ch);
	curl_close($ch);
	
	return $data;
}

?>