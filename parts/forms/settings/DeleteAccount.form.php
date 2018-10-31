<?php
require_once("src/classes/form/Form.interface.php");
require_once("src/classes/form/FormBuilder.class.php");

class DeleteAccount implements Form{

    function getInputs(){
		return [
            Input::PASSWORD("current", null, null, "Current active password"),
			Input::HIDDEN("role", "delete_account")
        ];
	}
	function getMethod(){
		return "post";
	}
	function getSecret(){
		return "Delete account";
	}
	function getSubmitLabel(){
		return "Delete Account";
	}
	function getSubmitClass(){
		return "";
	}
	function getSubmitID(){
		return "";
	}
}

?>