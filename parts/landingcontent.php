<?php 
header("Content-Type: text/html");

if(!isset($posts) || empty($posts)) {?>
	<div class="anounce error">
		There was a problem fetching the posts
	</div>
<?php } ?>