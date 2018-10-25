<?php header("Content-Type: text/html"); ?>

<div class="sidebar">
	<?php foreach(select(["what"=>"id, name", "from"=>"filters"]) as $item){
		include("parts/filter.php");
	} ?>
</div>
	<div class="control" id="getImage">
		<div class="group">
			<video autoplay ></video>
			<button id='useWebcam'>Use Webcam</button>
			<hr>
			<input type='file' id='file' accept=".jpg, .jpeg, .png" name='userimage'>
			<button id="useFile">Upload File</button>
		</div>
	</div>

	<div class="control disabled" id="editImage">
		<canvas id="viewImage"></canvas>
		<div class='filters group'>
			<div class='anounce'>
				Filters
			</div>
			<div class="body">
			</div>
		</div>
	</div>
<script>
        const constraints = {
            video: true
            };
        const video = document.querySelector('video');
        var stream;
		document.querySelector("#useWebcam").addEventListener("click", captureWebcam);
		document.querySelector("#useFile").addEventListener("click", captureImage);
		document.querySelector("#useWebcam").addEventListener("click", goToEdit);
		document.querySelector("#useFile").addEventListener("click", goToEdit);

		function goToEdit(){
			var geti = document.querySelector("#getImage");
			var edii = document.querySelector("#editImage");
			animate(500, geti, fadeout, function(){
				geti.style.display = "none";
				edii.style.display = "block";
				animate(500, edii, fadein);
			});
			
		}
        function deactivateWebcam(){
            if(stream && stream.active){
                var track = stream.getTracks()[0];
                track.stop();
            }
        }

        function activateWebcam(event){
            if(stream && stream.active)
                return;
            if (hasGetUserMedia()) {
                navigator.mediaDevices.getUserMedia(constraints).then(
                    (theStream) => {stream = theStream; video.srcObject = theStream}
                );
            } else {
                alert('getUserMedia() is not supported by your browser');
            }
        }
        function hasGetUserMedia() {
             return !!(navigator.mediaDevices &&
                 navigator.mediaDevices.getUserMedia);
        }

        
        var canvas, ctx, userImage;
		userImage = new Image();
		canvas = document.getElementById("viewImage");
    	ctx = canvas.getContext('2d');

		function captureWebcam(event){
			canvas.width = video.videoWidth ;
			canvas.height = video.videoHeight;
			ctx.drawImage(video, 0,0, canvas.width, canvas.height);
			userImage.src = canvas.toDataURL("image/png");
		}

		function captureImage(event){
			var file = event.target.files[0];
			if(!file){
				console.log("no File selected");
				return;
			}
			console.log("File "+file);
			var reader = new FileReader();
			reader.addEventListener("error",function(err){
				console.log(err);
			});
			reader.onload = function(){
				console.log("File read complete");
				userImage.onload = function(){
					canvas.width = userImage.width;
					canvas.height = userImage.height;
					ctx.drawImage(userImage, 0,0, canvas.width, canvas.height);
				}
				userImage.src = reader.result;
			};
			console.log("reading File");
			if (reader.readAsDataUrl)
				reader.readAsDataUrl(file);
			else if (reader.readAsDataurl)
				reader.readAsDataurl(file);
			else if (reader.readAsDataURL)
				reader.readAsDataURL(file);
		}

	document.partload = function(){
		activateWebcam();
	};

	document.partunload  = function (){
		deactivateWebcam();
	}


    </script>