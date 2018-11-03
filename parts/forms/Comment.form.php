<?php
require_once("src/classes/form/Form.interface.php");
require_once("src/classes/form/FormBuilder.class.php");

class CommentFrom implements Form{
	private $_decript;
	private $_label;
	private $_post;

	function __construct($s, $label, $postID){
		$this->_decript = $s;
		$this->_label = $label;
		$this->_post = $postID;
	}

    function getInputs(){
		return [
			new Input(["type"=>"textarea", "form"=>"comment", "maxlenfth"=>500, "value"=>$this->_decript,"name"=>"description", "_label"=>$this->_label, "required"=>true]),
			Input::HIDDEN("role", "message"),
			Input::HIDDEN("post", $this->_post)
        ];
	}
	function getMethod(){
		return "post";
	}
	function getSecret(){
		return "update_post";
	}
	function getSubmitLabel(){
		return "Post!";
	}
	function getSubmitClass(){
		return "";
	}
	function getSubmitID(){
		return "";
	}
}

?>