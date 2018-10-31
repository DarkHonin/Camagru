<?php
require_once("src/classes/Query.class.php");
require_once("Token.class.php");

class User extends Query{
	private const LoginError = "Invalid username / password";
	private const PasswordMissmatchError = "Passwords do not match";
	private const UserExistError = "Username/email aready in use";
	private const UserCreateError = "Error creating user";
	public $uname;
	public $email;
	public $email_valid;
	public $sha;
	public $session_token;
	public $id;
	public $active;
	public $recieve_updates;

	public $table = "users";

	public function get($what=null){return parent::get($what);}

	public function __construct(){
		parent::__construct();
	}

	public static function error1062($e){
		
	}

	public static function error23000($e){
		
		Utils::finalResponse(print_r($e, true));
	}

	public static function error21S01(){
		die("Could not create User, its empty ok");
	}

	function login(){
		error_log("Validating user information: ".$this->uname);
		$user = self::get("sha, session_token, active, id, uname, email")->where("uname='$this->uname'")->send();
		error_log("User information found: ".($user ? "Yes" : "No"));
		if(!$user) return self::LoginError;
		$pass = password_verify($this->password, $user->sha);
		error_log("Password OK: ".($pass ? "Yes" : "No"));
		if(!$pass) return self::LoginError;
		$user->sha = "";
		$user->session_token = sha1(time().$user->uname);
		$user->update()->where("uname='$user->uname'")->send();
		error_log("Updating local user");
		$this->parseArray(array_filter(get_object_vars($user)));
	}

	function register(){
		$users = User::get("uname, email")->where("uname='{$this->uname}' OR email='{$this->email}'")->send();
		if(!empty($users))
			return self::UserExistError;
		$this->sha = password_hash($this->password1, PASSWORD_BCRYPT);
		$this->token = sha1(time());
		if(!$this->insert()->send())
			return self::UserCreateError;
		$user = User::get("id")->where("uname='{$this->uname}'")->send();
		$token = Token::create($user, $user->uname, "activate_account");
		Utils::send_token_email($this->email, $token->token);
		$token->insert()->send();
	}

	static function verify(){
		if(!isset($_SESSION['user']) || empty($_SESSION['user'])) return self::LoginError;

		$user = self::get("session_token, active")->where("uname='{$_SESSION['user']['uname']}'")->send();
		if(!$user) return self::LoginError;
		$user->token = sha1(time().$user->uname);
		$_SESSION['user']['token'] = $user->token;
		$_SESSION['user']['active'] = 1;
		return false;
	}
}


?>