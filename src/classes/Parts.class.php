<?php

header("Content-Type: text/html");

final class Parts{
	const PARTS = [
		"part::menue" => "parts/menue.php",
		"/" => "parts/landingcontent.php",
		"/login" => "parts/login.php",
		"/logout" => "parts/logout.php",
		"/create" => "parts/create.php",
		"/part" => "src/part.php",
		"/activate" => "parts/activate.php",
		"/404"	=> "parts/err/404.php"
	];

	static function getPart($id){
		if(isset(self::PARTS[$id]))
			return self::PARTS[$id];
		return self::PARTS["/404"];
	}

}

?>