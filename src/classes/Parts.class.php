<?php

header("Content-Type: text/html");

final class Parts{
	const PARTS = [
		"part::menue" => "parts/menue.php",
		"/" => "parts/landingcontent.php",
		"/login" => "parts/login.php",
		"/logout" => "parts/logout.php",
		"/create" => "parts/create/create.structure.php",
		"part::preview" => "parts/create/preview.php",
		"part::imageControlls" => "parts/create/imageControlls.php",
		"part::Stickers" => "parts/create/stickerpanel.php",
		"/part" => "src/part.php",
		"/activate" => "parts/activate.php",
		"/404"	=> "parts/err/404.php",
		"/user" => "parts/user/landing.php"
	];

	static function getPart($id){
		if(isset(self::PARTS[$id]))
			return self::PARTS[$id];
		return self::PARTS["/404"];
	}

}

?>