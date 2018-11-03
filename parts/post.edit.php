<?php

require_once("models/User.class.php");
if($err = User::verify()){
	include_once("page/logout.php");
	return;
}

require_once("parts/forms/Comment.form.php");
require_once("src/classes/form/FormBuilder.class.php");

$builder = new FormBuilder();
$frm = new CommentFrom($post->description, "Describe your post", 0);


?>

<div class="anounce">
	Create a post
</div>
<div class="element-pool list">
	<label class="feed" id="filter_feed">
		<div for="showfilters" class="title">Elements</div>
		<input type="text" id="showfilters" class="hidden col-toggle" name="action">
		<div class="items hide">
			<div class="filter_class" onclick="fillWithPosts(this)">
				Your posts
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
		<img class="preview" src="data:image/png;base64,<?php echo $post->image_data ?>">
		<?php
			$overlay = json_decode($post->overlay, true);
		foreach($overlay as $ov){
			echo "<img class='sticker' style='left: {$ov['offset']['x']}; top: {$ov['offset']['y']};' sticker_id='{$ov['id']}' scale='{$ov['scale']}' rotate='{$ov['rotate']}' src='/post/{$ov['id']}/img' type='{$ov['type']}'>";
		} ?>
	</div>
</div>
<div id="image_controlls" class="list">
	<button onclick="del()">Delete Filter</button>
	<fieldset>
		<label for="scale">Item Scale</label>
		<input type="range" min="0" max="200" value="100" id="scale" oninput="scaleimage(this)">
	</fieldset>
	<fieldset>
		<label for="rotate">Rotation</label>
		<input type="range" min="0" max="360" value="0" id="rotate" oninput="rotateimage(this)">
	</fieldset>
	<br>
	<?php $builder->renderForm($frm, ["id"=>"comment", "action"=>"/edit"]); ?>
</div>

<script src="/assets/js/create.js"></script>

<script>
	document.querySelectorAll("#image_preview>img.sticker").forEach(q => {
		q.addEventListener("mousedown", grab);
		q.addEventListener("mousemove", move);
		q.style.left =  (preview.offsetLeft + q.style.left) + "px";
		q.style.top =  (preview.offsetTop + q.style.top) + "px";
		q.style.transform = "rotate("+q.getAttribute('rotate')+"deg)" + "scale("+q.getAttribute('scale')+")";
		q.scale = q.getAttribute('scale');
		q.rotate = q.getAttribute('rotate');
	});

	post = function (event){
		event.preventDefault();
		FD = new FormData(event.target);
		var jo = {
			stickers: []
		};
		document.querySelectorAll("#image_preview>img.sticker").forEach(function(sticker){
			console.log(img.offsetLeft, sticker.offsetLeft);
			var js = {
				offset: {x:((sticker.offsetLeft - img.offsetLeft) * (img.width/img.offsetWidth)) + ((sticker.width) / 2), y:(sticker.offsetTop - img.offsetTop) + (( sticker.height)/2)},
				width: 	sticker.width * sticker.scale,
				rotate: sticker.rotate,
				id:  	sticker.getAttribute("sticker_id"),
				type: 	sticker.getAttribute("type"),
				scale:	sticker.scale
			};
			jo.stickers.push(js);
		});
		FD.set("image", JSON.stringify(jo));
		ajax("post", "/edit", FD, handleResponse);
	}
</script>