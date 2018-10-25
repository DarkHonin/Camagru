<?php
include_once("parts/head.php");
header("Content-Type: text/html");
?>
<body>
	<div class="content">

	</div>
	<div class="sidebar" id="menue">
		<?php
			include_once("parts/menue.php");
		?>
	</div>
</body>
<script src="assets/js/pagenav.js?token=<?php echo random_int(0, 999); ?>>">

</script>
</html>