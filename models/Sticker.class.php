<?php

require_once("src/classes/Query.class.php");

class Sticker extends Query{

	public $table = "filters";
	public $filter_name;
	public $filter_image;
	public $id;

	public function get($what=null){return parent::get($what);}
}

?>