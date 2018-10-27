<?php
require_once("Query.class.php");
require_once("Form.interface.php");

class User extends Query implements Form{
	public $uname;
	public $email;
	public $sha;
	public $id;
	private $formType = "register";

	public static $Table = "users";

	public function get($what=null){return parent::get($what);}

	public function __construct(){

	}

	public static function error1062($e){
		
	}

	public static function error23000($e){
		var_dump($e);
		die("User already exists");
	}

	public static function error21S01(){
		die("Could not create User, its empty ok");
	}

	function login($password){
		die("Login works");
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
					"placeholder" => "Re-enter Password"
				],
				"password1" => [
					"type" => "password",
					"required" => true,
					"placeholder" => "Password"
				],
				[
					"name" => "action",
					"type" => "hidden",
					"value"=> "register"
				]
			];
		else
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
				"placeholder" => "Re-enter Password"
			],
			[
				"name" => "action",
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
		return "UserForm";
	}
}


?>