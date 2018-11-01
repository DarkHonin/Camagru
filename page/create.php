<?php

require_once("models/User.class.php");
if($err = User::verify()){
	include_once("page/logout.php");
	return;
}

?>

<div class="anounce">
	Create a post
</div>
<div class="element-pool list hidden">
	<label class="feed" id="filter_feed">
		<div for="showfilters" class="title">Elements</div>
		<input type="text" id="showfilters" class="hidden col-toggle" name="action">
		<div class="items hide">
			<div class="filter_class">
				Your posts
				<div class="items">
				</div>
			</div>
			<div class="filter_class">
				Frames
				<div class="items">
				</div>
			</div>
			<div class="filter_class" onclick="fillWithStickers(this)">
				Stickers
				<div class="items">
				</div>
			</div>
		</div>
	</label>

</div>
<div class="col-hold col-r">
	<div class="col-1" id="image_preview">
		<video class="preview" autoplay ></video>
		<fieldset>
			<input type="file" accept=".jpg, .jpeg, .png" onchange="captureFileImage(this)" title="Choose a file">
			<button onclick="editImage()">Use Webcam Image</button>
		</fieldset>
	</div>
</div>
<div id="image_controlls" class="list hidden">
	<button onclick="post()">Post</button><button onclick="reset()">Reset</button><button onclick="del()">Delete Filter</button>
	<fieldset>
		<label for="scale">Item Scale</label>
		<input type="range" min="0" max="200" value="100" id="scale" oninput="scaleimage(this)">
	</fieldset>
	<fieldset>
		<label for="rotate">Rotation</label>
		<input type="range" min="0" max="360" value="0" id="rotate" oninput="rotateimage(this)">
	</fieldset>
</div>

<script src="assets/js/create.js"></script>