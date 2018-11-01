<?php

require_once("src/classes/Query.class.php");
require_once("User.class.php");

class Post extends Query{

	public $table = "posts";

	public function get($what=null){return parent::get($what);}

	public $user;
	public $image_data;
	public $date;
	public $id;
	public $description;
	
	function __construct(){
		parent::__construct();
		if($this->user)
			$this->user = User::get("id, uname")->where("id=$this->user")->send();
	}
}

?>