<?php
require_once("src/classes/form/Form.interface.php");
require_once("src/classes/form/FormBuilder.class.php");

class LoginForm implements Form{
    function getInputs(){
		return [
            Input::USERNAME("uname", null, null, "Username/email"),
			Input::PASSWORD("password", null, null, "Password"),
			Input::HIDDEN("role", "login")
        ];
	}
	function getMethod(){
		return "post";
	}
	function getSecret(){
		return "login";
	}
	function getSubmitLabel(){
		return "Login";
	}
	function getSubmitClass(){
		return "";
	}
	function getSubmitID(){
		return "";
	}
}

?>