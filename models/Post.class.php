<?php

require_once("src/classes/Query.class.php");
require_once("User.class.php");
require_once("Comment.class.php");

class Post extends Query{

	public $table = "posts";

	public function get($what=null){return parent::get($what);}

	public $user;
	public $image_data;
	public $date;
	public $id;
	public $description;
	public $overlay;
	
	function __construct(){
		parent::__construct();
		if($this->user)
			$this->user = User::get("id, uname, recieve_updates, email")->where("id=$this->user")->send();
	}

	function getCommentCount(){
		$comments = Comment::get('id')->where("post={$this->id}")->send();
		if($comments && !is_array($comments) && !empty($comments))
			$comments = [$comments];
		else return 0;
		return count($comments);
	}
}

?>