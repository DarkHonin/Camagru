<?php
require_once("Query.class.php");

class User extends Query{
	private const LoginError = "Invalid username / password";
	private const PasswordMissmatchError = "Passwords do not match";
	private const UserExistError = "Username/email aready in use";
	private const UserCreateError = "Error creating user";
	public $uname;
	public $email;
	public $sha;
	public $token;
	public $id;
	public $active;
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

	function register(){
		$users = User::get("uname, email")->where("uname='{$this->uname}' OR email='{$this->email}'")->send();
		if(!empty($users))
			return self::UserExistError;
		$this->sha = password_hash($this->password1, PASSWORD_BCRYPT);
		$this->token = sha1(time().$this->uname);
		if(!$this->insert()->send())
			return self::UserCreateError;
		send_token_email($this->email, $this->token);
	}

	static function verify($doi = false){
		if(!isset($_SESSION['user']) || empty($_SESSION['user'])) return self::LoginError;

		$user = self::get("token, active")->where("uname='{$_SESSION['user']['uname']}'")->send();
		if(!$user) return self::LoginError;
		if($user->active){
			$user->token = sha1(time().$user->uname);
			$_SESSION['user']['token'] = $user->token;
			$_SESSION['user']['active'] = 1;
		}else{
			if($doi)
				return self::LoginError;
		}
		return false;
	}
}


?>