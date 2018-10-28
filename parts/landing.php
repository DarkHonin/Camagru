<?php
include_once("parts/head.php");
header("Content-Type: text/html");
?>
<body class="content">
	<div class="content" id="page_content">

	</div>
	<div class="sidebar" id="menue">
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