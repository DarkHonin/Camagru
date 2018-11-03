<?php
require_once("src/classes/Query.class.php");
require_once("Token.class.php");
require_once("Event.class.php");

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
		$this->session_token = sha1(time());
		if(!$this->insert()->send())
			return self::UserCreateError;
		$user = User::get("id")->where("uname='{$this->uname}'")->send();
		$token = Token::create($user, $user->uname, "activate_account");
		Utils::send_token_email($this->email, $token->token);
		$token->insert()->send();
	}

	function doesFollow($user){
		$follows = Event::get()->where("post={$this->id} AND action='follow' AND acting_user={$user->id}")->send();
		if(is_object($follows))
			return true;
		else if(!is_array($likes))
			return false;
	}

	function getFollowing(){
		$follows = Event::get("post")->where("action='follow' AND acting_user={$this->id}")->send();
		if(!$follows) return [];
		if(is_object($follows))
			$follows = [$follows];
		$ret = [];
		foreach($follows as $f)
			array_push($ret, $f->post);
		return $ret;
	}
}


?>