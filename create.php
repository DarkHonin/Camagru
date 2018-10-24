<?php
	include_once("parts/head.php");
?>
<body>
    <div class="sidebar">
	Overlays come here
	</div>
	<div class="content">
        <form class="frame" enctype="multipart/form-data" method="post">
            <label class="anounce" for="webcam" id="useWebCam">Use Webcam</label>
            <input type="radio" value="web" class="toggle" name="src" style="display: none" id="webcam">
                <video autoplay class='toggle'></video>
            <label id="useFile" class="anounce" for="file">Upload File</label>
            <input type="radio" value="file" name="src" class="toggle" style="display: none" id="file">
                <input type='file' class="toggle" accept=".jpg, .jpeg, .png, image/*;capture=camera" name='userimage'>
            <input type="submit" value="Post">
        </form>
	</div>
	<div class="sidebar">
	<?php
	    include_once("parts/menue.php");
    ?>
    </div>
    
    
    <script>
        document.querySelector("#useWebCam").addEventListener("click", activateWebcam);
        document.querySelector("#useFile").addEventListener("click", deactivateWebcam);
        const constraints = {
            video: true
            };
        const video = document.querySelector('video');
        var stream;

        function deactivateWebcam(){
            this.style.display = "none";
            document.querySelector('#useWebCam').style.display = "block";
            if(stream && stream.active){
                var track = stream.getTracks()[0];
                track.stop();
            }
        }

        function activateWebcam(event){
            this.style.display = "none";
            document.querySelector('#useFile').style.display = "block";
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

    </script>
</body>
</html>