<?php
include_once("parts/head.php");
header("Content-Type: text/html");
?>
<body class="col-r col-full">
	<div class="contentT col-4" id="page_content">

	</div>
	
	<label class="tag show-s col-1" for="toggle_menue" style="position: fixed;">
		<img src="assets/img/icon.svg">
	</label>
	<input type="text" class="col-toggle" id="toggle_menue" style="width: 0px; height: 0px; position: absolute; z-index: -999;">
	<div class="col-1 hide-s sidebar" id="menue">
		<?php
			include_once("parts/menue.php");
		?>
	</div>
</body>
<script src="assets/js/pagenav.js?token=<?php echo random_int(0, 999); ?>>"></script>
<script src="assets/js/triggers.js?token=<?php echo random_int(0, 999); ?>>"></script>
<script type="module" src="assets/js/create.js?token=<?php echo random_int(0, 999); ?>>"></script>
<script src="assets/js/login.js?token=<?php echo random_int(0, 999); ?>>"></script>
</html>