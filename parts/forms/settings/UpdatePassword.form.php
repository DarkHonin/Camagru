<?php
require_once("src/classes/form/Form.interface.php");
require_once("src/classes/form/FormBuilder.class.php");

class UpdatePassword implements Form{

    function getInputs(){
		return [
			Input::PASSWORD("current", null, null, "Current password"),
			Input::PASSWORD("new_password", null, null, "New password"),
			Input::HIDDEN("role", "update_password")
        ];
	}
	function getMethod(){
		return "post";
	}
	function getSecret(){
		return "change_password";
	}
	function getSubmitLabel(){
		return "Update Password";
	}
	function getSubmitClass(){
		return "";
	}
	function getSubmitID(){
		return "";
	}
}

?>