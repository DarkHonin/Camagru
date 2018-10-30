<?php
require_once("src/classes/form/Form.interface.php");
require_once("src/classes/form/FormBuilder.class.php");

class RegisterForm implements Form{
    function getInputs(){
        $uname =  Input::USERNAME("uname", null, null, "Username");
        $uname->onblur = "check_available(this)";
        $email = Input::EMAIL("email", null, null, "Email");
        $email->onblur = "check_available(this)";
		return [
            $uname,
            $email,
            Input::PASSWORD("password1", null, null, "Password"),
            Input::PASSWORD("password2", null, null, "Re-enter Password"),
            Input::HIDDEN("role", "register")
        ];
	}
	function getMethod(){
		return "post";
	}
	function getSecret(){
		return "register";
	}
	function getSubmitLabel(){
		return "Register";
	}
	function getSubmitClass(){
		return "";
	}
	function getSubmitID(){
		return "";
	}
}

?>