<?php
require_once("src/classes/form/Form.interface.php");
require_once("src/classes/form/FormBuilder.class.php");

class UpdateEmail implements Form{

	private $_email;

	function __construct($current_email){
		$this->_email = $current_email;       
	}

    function getInputs(){
		$email = Input::EMAIL("email", null, null, $this->_email);
        $email->onblur = "check_available(this)";
		return [
           	$email,
			Input::HIDDEN("role", "update_email")
        ];
	}
	function getMethod(){
		return "post";
	}
	function getSecret(){
		return "email update";
	}
	function getSubmitLabel(){
		return "Update Email";
	}
	function getSubmitClass(){
		return "";
	}
	function getSubmitID(){
		return "";
	}
}

?>