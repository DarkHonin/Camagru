<?php

require_once(__DIR__."/Form.interface.php");
require_once(__DIR__."/Input.class.php");
require_once("src/classes/Utils.class.php");

final class FormBuilder{

	public const verbose = false;
	public const IGNORECSRF = false;

	function renderFields(Form $form){
		$fields = $form->getInputs();
		array_push($fields, Input::CSRF_TOKEN($form->getSecret()));
		array_push($fields, Input::SUBMIT($form->getSubmitLabel(), $form->getSubmitID(), $form->getSubmitClass()));
		$late_block = [];
		foreach($fields as $f){
			if($f->type == "textarea")
				array_push($late_block, $f);
			else
				$f->render();
		}
		return $late_block;
	}

	function renderForm(Form $form, $tags = []){
		echo "<form method='{$form->getMethod()}'";
		foreach($tags as $k=>$v)
				echo "$k='$v'";
		echo ">";
		$late = $this->renderFields($form);
		if(!empty($late)){
			foreach($late as $l){
				$l->render_label();
				echo "<{$l->type} ";
				$l->render_tags();
				echo ">";
				echo $l->value;
				echo "</{$l->type}>";
			}
		}
		echo "</form>";
	}

	function valid(Form $form, $input, &$err = []){
		if(self::verbose) echo "Validating form\n";
		foreach($form->getInputs() as $i){
			if($input[$i->name])
				$i->value = $input[$i->name];
			$errs = [];
			if(!$i->valid($errs))
				$err[$i->name] = $errs;
		}
		if(!self::IGNORECSRF)
		if(!Utils::check_csrf_token($form->getSecret(), $input['csrf-token']))
			$err["csrf-token"] = "The page has expired";
		if(!empty($err))
			return false;
		return true;
	}
}

?>