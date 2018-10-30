<?php

interface Form{
	function getInputs();
	function getMethod();
	function getSecret();
	function getSubmitLabel();
	function getSubmitClass();
	function getSubmitID();
}

?>