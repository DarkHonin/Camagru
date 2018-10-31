<?php
require_once("src/classes/form/Form.interface.php");
require_once("src/classes/form/FormBuilder.class.php");

class UpdateGeneral implements Form{

        private $_uname;
        private $_updates;

	function __construct($current_uname, $updates){
		$this->_uname = $current_uname;
		$this->_updates = $updates;
	}

    function getInputs(){
        $uname =  Input::USERNAME("uname", null, null, $this->_uname);
        $uname->onblur = "check_available(this)";
        $uname->required = false;
        $send_messages = new Input([
                "type"=>"checkbox", 
                "_label"=>"Recieve updates?",
                "checked"=>$this->_updates,
				"name"=>"updates"
                ]);
	return [
                $uname,
                $send_messages,
	        Input::HIDDEN("role", "update_genneral")
        ];
	}
	function getMethod(){
		return "post";
	}
	function getSecret(){
		return "genneral_info";
	}
	function getSubmitLabel(){
		return "Update";
	}
	function getSubmitClass(){
		return "";
	}
	function getSubmitID(){
		return "";
	}
}