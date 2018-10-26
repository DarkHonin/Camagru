<?php header("Content-Type: text/html"); ?>


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
				<button class="switch" action="resetImage">Delete layer</button>
				<button class="switch" action="resetImage">Post</button>
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
