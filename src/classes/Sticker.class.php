<?php

require_once("Query.class.php");

class Sticker extends Query{

	public $table = "stickers";
	public $image;
	public $title;
	public $id;

	public function get($what=null){return parent::get($what);}
}

?>