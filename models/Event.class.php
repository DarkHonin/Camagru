<?php

require_once("src/classes/Query.class.php");
require_once("User.class.php");
require_once("Post.class.php");

class Event extends Query{

	public $table = "events";

	public function get($what=null){return parent::get($what);}

	public $acting_user;
	public $date;
	public $post;
	public $id;
	public $action;
	
	function __construct(){
		parent::__construct();
		if($this->acting_user)
			$this->acting_user = User::get("id, uname")->where("id=$this->acting_user")->send();
		if($this->post && $this->action == "like")
			$this->post = Post::get("id")->where("id=$this->post")->send();
		if($this->post && $this->action == "follow")
			$this->post = User::get("id")->where("id=$this->post")->send();
	}
}

?>