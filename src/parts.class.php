<?php

header("Content-Type: text/html");

final class Parts{
	const PARTS = [
		"part::menue" => "parts/menue.php",
		"page::/" => "parts/landingcontent.php",
		"page::/login" => "parts/login.php",
		"page::/logout" => "parts/logout.php",
		"page::/create" => "parts/create.php"
	];

	static function getPart($id){
		if(isset(self::PARTS[$id]))
			return self::PARTS[$id];
	}

}

?>