<?php

require_once("src/classes/Query.class.php");
require_once("User.class.php");
require_once("Comment.class.php");
require_once("Event.class.php");

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
		if(is_object($comments))
			return 1;
		else if(!is_array($comments))
			return 0;
		return count($comments);
	}

	function getLikes(){
		$likes = Event::get()->where("post={$this->id} AND action='like'")->send();
		if(is_object($likes))
			return 1;
		else if(!is_array($likes))
			return 0;
		return count($likes);
	}
}

?>