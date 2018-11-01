<?php

require_once("src/classes/Query.class.php");
require_once("User.class.php");

class Comment extends Query{

	public $table = "comments";

	public function get($what=null){return parent::get($what);}

	public $user;
	public $comment;
	public $date;
	public $id;
	public $post;
	
	function __construct(){
		parent::__construct();
		if($this->user)
			$this->user = User::get("uname")->where("id=$this->user")->send();
	}
}

?>