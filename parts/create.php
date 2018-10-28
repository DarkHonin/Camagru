<?php
header("Content-Type: text/html");
require_once("src/classes/User.class.php");
require_once("src/classes/Sticker.class.php");
require_once("src/classes/Post.class.php");
if(User::verify() || !(isset($_SESSION['user']['active']) && $_SESSION['user']['active']))
	die('<div class="anounce error">
	Please sign in / Activate your account
</div>');

if(!empty($query->payload)){
	$payload = json_decode($query->payload, true);
	$img = $payload["image"];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$img = base64_decode($img);
	$file_name = "posts_images/".uniqid("user_image").".png";
	$post = new Post();
	$post->Image = $file_name;
	$post->user = $_SESSION['user']['id'];
	$post->Title = "A post by me";
	file_put_contents($file_name, $img);
	if(is_array($error = $post->insert()->send())) 
		Utils::finalResponse($error);
	else
		Utils::finalResponse(["redirect" => "/post?id=postidgoeshere"]);
}

?>

<div class="panel active " id="editImage">
	<div class='control'>
		<div class='filters group sidebar'>
			<div class='anounce'>
				Filters
			</div>
			<div class="body">
			<?php 
				$fills = Sticker::get()->send();
				if(!is_array($fills))
					$fills = [$fills];
				
					foreach($fills as $item)
						include("parts/filter.php");
					 ?>
			</div>
		</div>

		<div class="control-col">
			<video autoplay ></video>
				<button class="switch" onclick="document.page.parts.create.captureFromCam">Use Webcam</button>
			<hr>
				<input type='file' id='file' accept=".jpg, .jpeg, .png" name='userimage'>
			<button class="switch" onclick="document.page.parts.create.captureFromFile">Upload File</button>
		</div>
		<canvas class="content" id="viewImage"></canvas>
		<div class='filters group sidebar'>
			<div class='anounce'>
				Controlls
			</div>
			<div class="body">
				<button class="switch" onclick="resetImage">Start Over</button>
				<button class="switch" onclick="delLayer">Delete layer</button>
				<button class="switch" onclick="postImage">Post</button>
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
