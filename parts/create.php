<?php
header("Content-Type: text/html");

if(!update_user())
	die('<div class="anounce error">
	Please sign in first
</div>');

if(!empty($query->payload)){
	$payload = json_decode($query->payload, true);
	$img = $payload["image"];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$img = base64_decode($img);
	$file_name = "posts_images/".uniqid("user_image").".png";
	file_put_contents($file_name, $img);
	$data = ['tabel' => "posts", "fields" => [
		"title" => "Some title",
		"image"	=> $file_name,
		"user" => $_SESSION['user']['id']
	]];
	$res = insert_into_db($data);
	header("Content-Type: application/json");
	if(is_array($res)) 
		die(json_encode($res));
	else
		die(json_encode(["redirect" => "/post?id=$res"]));
}




?>

<div class="panel active" id="getImage">
	<div class="control-col">
		<video autoplay ></video>
		<button class="switch" action="captureFromCam">Use Webcam</button>
		<hr>
		<input type='file' id='file' accept=".jpg, .jpeg, .png" name='userimage'>
		<button class="switch" action="captureFromFile">Upload File</button>
	</div>
</div>

<div class="panel" id="editImage">
	<div class='control'>
		<div class='filters group sidebar'>
			<div class='anounce'>
				Filters
			</div>
			<div class="body">
			<?php foreach(select(["what"=>"id, name, image", "from"=>"filters"]) as $item){
				include("parts/filter.php");
			} ?>
			</div>
		</div>
		<canvas class="content" id="viewImage"></canvas>
		<div class='filters group sidebar'>
			<div class='anounce'>
				Controlls
			</div>
			<div class="body">
				<button class="switch" action="resetImage">Start Over</button>
				<button class="switch" action="delLayer">Delete layer</button>
				<button class="switch" action="postImage">Post</button>
				<input type='number' id="scale" minvalue='0'>
			</div>
			<div class='anounce'>
				Layers
			</div>
			<ul class="body" id='layers'>
				
			</ul>
		</div>
	</div>
</div>
