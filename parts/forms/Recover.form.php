<?php
require_once("src/classes/form/Form.interface.php");
require_once("src/classes/form/FormBuilder.class.php");

class RecoverForm implements Form{
    function getInputs(){
		return [
            Input::EMAIL("email", null, null, "Email"),
			Input::HIDDEN("role", "Recover")
        ];
	}
	function getMethod(){
		return "post";
	}
	function getSecret(){
		return "recover";
	}
	function getSubmitLabel(){
		return "Send Recovery email";
	}
	function getSubmitClass(){
		return "";
	}
	function getSubmitID(){
		return "";
	}
}

?>