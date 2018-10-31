<?php

require_once("src/classes/Query.class.php");
require_once("User.class.php");

class Post extends Query{

	public $table = "posts";

	public function get($what=null){return parent::get($what);}

	public $user;
	public $Image;
	public $date;
	public $id;
	public $Title;
	public $comments;

	function __construct(){
		parent::__construct();
		if($this->user)
			$this->user = User::get("uname, id")->where("id=$this->user")->send();
	}
}

?>