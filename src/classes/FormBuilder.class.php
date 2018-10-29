<?php

require_once(__DIR__."/Form.interface.php");
require_once(__DIR__."/Utils.class.php");

final class FormBuilder{

	public const verbose = false;

	function renderFields($form){
		$fields = $form->getFields();
		$fields["csrf"] = [
			"type"=>"hidden",
			"value"=> Utils::create_csrf_token($form->getSecret()),
			"required" => true];
		$fields["submit"] = [
			"type" => "submit",
			"value"=> $form->getSubmitLabel()
		];
		foreach($fields as $k=>$field){
			echo "<input name='$k' ";
			foreach($field as $k=>$v)
				echo "$k='$v'";
			echo ">";
		}
	}

	function renderForm(Form $form, $tags = []){
		echo "<form method='{$form->getMethod()}'";
		foreach($tags as $k=>$v)
				echo "$k='$v'";
		echo ">";
		$this->renderFields($form);
		echo "</form>";
	}

	function validate(Form $form, $input, $additional = null){
		if(self::verbose) echo "Validating form\n";
		foreach($form->getFields() as $k=>$f){
			if(self::verbose) echo "Field $k = $input[$k] : ";
			if(isset($f["required"]) && (!isset($input[$k]) || empty($input[$k]))){
				if(self::verbose) echo "Required but not set\n";
				return [$k => "Field is required"];
			}
			if(isset($f["maxlength"]) && strlen($input[$k]) > $f["maxlength"])
				return [$k => "Maximum amount of characters: {$f["maxlength"]}" ];
			if(isset($input[$k]))
				$form->$k = $input[$k];
			if(self::verbose) echo "Valid\n";
		}
		if(!Utils::check_csrf_token($form->getSecret(), $input['csrf']))
			return ["error"=>"The page has expired"];
		if(method_exists($form, "onFormValid"))
			return $form->onFormValid($input);
	}
}

?>