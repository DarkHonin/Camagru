<?php
	include_once("parts/head.php");
?>

<body>
	<div class="content">
	<?php if(!isset($posts) || empty($posts)) {?>
	<div class="anounce error">
		There was a problem fetching the posts
	</div>
<?php } ?>
	</div>
	<div class="sidebar">
	<?php
	include_once("parts/menue.php");
?>
	</div>
</body>
</html>