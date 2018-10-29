<?php
require_once("src/classes/User.class.php");
if(!isset($_SESSION['user']) || !isset($_SESSION['user']['active']) || !$_SESSION['user']['active'] || User::verify())
	die('<div class="anounce error">
	Please sign in / Activate your account
</div>');
?>

<div class="col-full col-r">
<div class='sidebar col-1 hide-s' id="Stickers">
		
</div>
<div class="col-3" id="editImage">
	<div class="col-full" id="preview">
		<video autoplay ></video>
			<button class="switch" onclick="document.create.captureWebcamImage()">Use Webcam</button>
		<hr>
			<input type='file' id='file' accept=".jpg, .jpeg, .png" name='userimage'>
		<button class="switch" onclick="document.create.captureFileImage()">Upload File</button>
	</div>
</div>
<div class='sidebar col-1 hide-s' id='imageControlls'>
	
</div>
</div>