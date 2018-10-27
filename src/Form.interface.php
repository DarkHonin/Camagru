<?php

interface Form{
	function getFields();
	function getMethod();
	function getSecret();
	function getSubmitLabel();
}

?>