<?php

final class FormBuilder{

	function renderFields($form){
		$fields = $form->getFields();
		$fields["csrf"] = [
			"type"=>"hidden",
			"value"=>create_csrf_token($form->getSecret()),
			"required" => true];
		$fields["submit"] = [
			"type" => "submit",
			"value"=> $form->getSubmitLabel(),
			"class"=>"anounce"
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
		foreach($this->_fields as $k=>$f){
			if(isset($f["required"]) && (!isset($input[$f['name']]) || empty($input[$f['name']])))
				return [$f['name'] => "Field is required"];
			if(isset($f["maxlength"]) && strlen($input[$f['name']]) > $f["maxlength"])
				return [$f['name'] => "Maximum amount of characters: {$f["maxlength"]}" ];
			if(isset($input[$f['name']]))
				$this->_fields[$k]['value'] = $input[$f['name']];
		}
		if(!check_csrf_token($this->_id, $input['csrf']))
			return ["error"=>"The page has expired"];
		if($additional && is_callable($additional))
			return $additional($input);
	}
}

?>