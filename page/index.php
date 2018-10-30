<?php
	include_once("parts/head.php");
?>

<?php
header("Content-Type: text/html");
?>
<body class="col-r col-full col-hold">
	<div class="contentT col-4" id="page_content">
		<?php
			include_once($content);
		?>
		<div class="anounce">
			This is the bottom of the page
		</div>
	</div>
	
	<label class="tag show-s col-1" for="toggle_menue" style="position: fixed;">
		<img src="assets/img/icon.svg">
	</label>
	<input type="text" class="col-toggle hidden" id="toggle_menue">
	<div class="col-1 hide-s sidebar" id="menue">
		<?php
			include_once("parts/menue.php");
		?>
	</div>
</body>
</html>