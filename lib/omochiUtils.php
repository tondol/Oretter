<?php

function isGarakeIP($ip="",&$carrier=""){
	
	if($ip=="")$ip=$_SERVER["REMOTE_ADDR"];
	
	$host = gethostbyaddr($ip);
	if (preg_match('/docomo\.ne\.jp$/', $host)) {
		$carrier="docomo";
		return true;
	}
	
	if (preg_match('/ezweb\.ne\.jp$/', $host)) {
		$carrier="au";
		return true;
	}
	
	if (preg_match('/jp-[dhtcrknsq]\.ne\.jp/', $host)) {
		$carrier="softbank";
		return true;
	}
	
	return false;
}

?>