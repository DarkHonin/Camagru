<?php

require_once("src/classes/Query.class.php");
require_once("User.class.php");

class Token extends Query{
	public const TOKEN_TIMEOUT = 60*60*1;
	
	public static function create($user, $secret, $action, $data=null){
		$ret = new self();
		$ret->parseArray([
			"user" => $user->id,
			"token" => sha1(time().$secret),
			"action" => $action,
			"data"=>$data
		]);
		return ($ret);
	}

	public static function redeem(){
		require_once("src/classes/form/Input.class.php");
		if(!isset($_GET['token']) || empty($_GET['token']))
			return null;
		$token = Input::USER_TOKEN($_GET['token']);
		if(!$token->valid())
			return null;
		$token = self::get()->where("token='$token->value'")->send();
		return $token;
	}

	public $table = "tokens";
	public function get($what=null){return parent::get($what);}

	public $id;
	public $user;
	public $token;
	public $created_on;
	public $action;
	public $data;

	function __construct(){
		parent::__construct();
	}

	function expired(){
		$date = new DateTime($this->created_on);
		$now = new DateTime();
		$age = $now->getTimestamp() - $date->getTimestamp();
		error_log("Token age: ".$age);
		if($age > self::TOKEN_TIMEOUT)
			return true;
		return false;
	}
}

?>