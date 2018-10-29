<?php
require_once("Query.class.php");
require_once("Form.interface.php");

class User extends Query implements Form{
	private const LoginError = "Invalid username / password";
	private const PasswordMissmatchError = "Passwords do not match";
	private const UserExistError = "Username/email aready in use";
	public $uname;
	public $email;
	public $sha;
	public $token;
	public $id;
	private $formType = "register";

	public $table = "users";

	public function get($what=null){return parent::get($what);}

	public static function error1062($e){
		
	}

	public static function error23000($e){
		Utils::finalResponse(self::UserExistError);
	}

	public static function error21S01(){
		die("Could not create User, its empty ok");
	}

	function login(){
		$user = self::get("sha, token, active, id, uname")->where("uname='$this->uname'")->send();
		if(!$user) return self::LoginError;
		if(!password_verify($this->password, $user->sha)) return self::LoginError;
		unset($user->sha);
		$_SESSION['user'] = array_filter(get_object_vars($user));
	}

	function onFormValid($params){
		if($this->formType == "login")
			return $this->login();
		else{
			if($this->password !== $this->password1) return self::PasswordMissmatchError;
			$this->sha = password_hash($this->password1, PASSWORD_BCRYPT);
			$this->token = sha1(time().$this->uname);
			$this->password = null;
			$this->password1 = null;
			$this->action = null;
			$this->insert()->send();
		}
	}

	static function verify($doi = false){
		if(!isset($_SESSION['user']) || empty($_SESSION['user'])) return self::LoginError;
		$user = self::get("token, active")->where("uname='{$_SESSION['user']['uname']}'")->send();
		if(!$user) return self::LoginError;
		if($user->active){
			$user->token = sha1(time().$user->uname);
			$_SESSION['user']['token'] = $user->token;
		}else{
			if($doi)
				return self::LoginError;
		}
		return false;
	}

	function setFormType($str){
		$this->formType = $str;
	}

	function getFields(){
		if($this->formType == "register")
			return [
				"uname" => [
					"required" => true,
					"type" => "text",
					"placeholder" => "Username",
					"pattern" => "^[A-Za-z0-9_]{1,15}$"
				],
				"email" => [
					"required" => true,
					"type" => "email",
					"placeholder" => "Email"
				],
				"password" => [
					"type" => "password",
					"required" => true,
					"placeholder" => "Enter Password"
				],
				"password1" => [
					"type" => "password",
					"required" => true,
					"placeholder" => "Re-enter Password"
				],
				"action" => [
					"type" => "hidden",
					"value"=> "register"
				]
			];
		else if ($this->formType == "login")
		return [
			"uname" => [
				"required" => true,
				"type" => "text",
				"placeholder" => "Username",
				"pattern" => "^[A-Za-z0-9_]{1,15}$"
			],
			"password" => [
				"type" => "password",
				"required" => true,
				"placeholder" => "Enter Password"
			],
			"action" => [
				"type" => "hidden",
				"value"=> "login"
			]
		];
		else if ($this->formType == "alter")
		return [
			"password" => [
				"type" => "password",
				"required" => true,
				"placeholder" => "Enter Password"
			],
			"email" => [
				"required" => true,
				"type" => "email",
				"placeholder" => "Email"
			],
			"action" => [
				"type" => "hidden",
				"value"=> "login"
			]
		];
	}

	function getSubmitLabel(){
		if($this->formType == "register") return "Register";
		return "Login";
	}

	function getMethod(){
		return "POST";
	}

	function getSecret(){
		return $this->formType;
	}
}


?>