
const constraints = {
	video: true
};

var grabbed;
var lastGrabbed;
var scale_elem = document.querySelector("#scale");
const video = document.querySelector('video');
var canvas = document.createElement("canvas"), ctx, layer_o = document.querySelector('#layer_feed>.items');
var preview = document.querySelector('#image_preview');

form_sub = post;
ctx = canvas.getContext('2d');
ctx.fillRect(0, 0, canvas.width, canvas.height);

function editImage(){
	document.querySelector("#image_preview>fieldset").classList.add("hidden");
	document.querySelectorAll(".list.hidden").forEach(f=> {f.classList.remove("hidden")});
}

function fillWithStickers(item){
	var container = item.querySelector(".items");
	var fd = new FormData();
	fd.set("item", "stickers");
	ajax("post", "/info/creator_images", fd, function(data){
		var js = JSON.parse(data);
		if(!js.data)
			container.innerHTML = "None available as of yeet";
		else
			container.innerHTML = js['data'];
	});
}

function fillWithPosts(item){
	var container = item.querySelector(".items");
	var fd = new FormData();
	fd.set("item", "posts");
	ajax("post", "/info/creator_images", fd, function(data){
		var js = JSON.parse(data);
		if(!js.data)
			container.innerHTML = "None available as of yeet";
		else
			container.innerHTML = js['data'];
	});
}

var stream;

var layers = [];

function deactivateWebcam(){
	if(stream && stream.active){
		var track = stream.getTracks()[0];
		track.stop();
	}
}

function activateWebcam(event){
	console.log(video);
	if(stream && stream.active || !video)
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
	

function captureWebcamImage(onready){
	var img = new Image();

	canvas.width = video.videoWidth ;
	canvas.height = video.videoHeight;
	ctx.drawImage(video, 0,0, canvas.width, canvas.height);
	deactivateWebcam();

	img.onload = function(){
		img.classList.add("preview");
		video.parentElement.appendChild(img);
		video.remove();
		onready(img);
	}
	img.src = canvas.toDataURL("image/png");
}

function addLayer(image){
	var q = image.cloneNode();
	q.onclick = null;
	q.addEventListener("mousedown", grab);
	q.addEventListener("mousemove", move);
	q.scale = 1;
	q.rotate = 0;
	preview.appendChild(q);
}

function captureFileImage(input){
	var file = input.files[0];
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
		var img = document.createElement("img");
		img.classList.add("preview");
		img.src = reader.result;
		video.parentElement.appendChild(img);
		video.remove();
		editImage();
	};
	console.log("reading File");
	if (reader.readAsDataUrl)
		reader.readAsDataUrl(file);
	else if (reader.readAsDataurl)
		reader.readAsDataurl(file);
	else if (reader.readAsDataURL)
		reader.readAsDataURL(file);
}

var grabbedAt;

function grab(event){
	if((event.button != 0)) return;
	if(grabbed){
		grabbed.style.zIndex = 0;
		grabbed = false;
		return;
	}
	console.log("grabbed");
	grabbedAt = {x:event.offsetX, y: event.offsetY}
	
	grabbed = event.target;
	grabbed.style.zIndex = 1;
	lastGrabbed = grabbed;
	scale_elem.value = grabbed.scale * 100;
}

function move(event){
	if(grabbed != event.target) return;
	event.target.style.left = (this.scale * (event.offsetX - grabbedAt.x)) + this.offsetLeft + "px";
	event.target.style.top = (this.scale * (event.offsetY - grabbedAt.y)) + this.offsetTop + "px";
}

function rotateimage(event){
	lastGrabbed.rotate = event.value;
	lastGrabbed.style.transform = "rotate("+lastGrabbed.rotate+"deg)" + "scale("+lastGrabbed.scale+")";
}

function scaleimage(event){
	lastGrabbed.scale = event.value/100;
	lastGrabbed.style.transform = "scale("+lastGrabbed.scale+")" + "rotate("+lastGrabbed.rotate+"deg)";
}

function reset(){
	deactivateWebcam();
	document.querySelectorAll("#image_preview>img.sticker").forEach(i => i.remove());
	document.querySelector("#image_preview>fieldset").classList.remove("hidden");
	document.querySelector("#image_preview>.preview").remove();
	preview.appendChild(video);
	document.querySelectorAll(".list").forEach(f=> {f.classList.add("hidden")});
	activateWebcam();
}

ctx.globalAlpha = 0.5;

function del(){
	lastGrabbed.remove();
	lastGrabbed = null; 
}

var FD;
var grab_image = true;
function preparePost(img){
	if(grab_image)
		var jo = {
			userImage: (img.src),
			stickers: []
		};
	else
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
	ajax("post", window.location, FD);
}

function post(event){
	event.preventDefault();
	FD = new FormData(event.target);
	var src_elem = document.querySelector(".preview");
	if(src_elem.localName == "video")
		captureWebcamImage(preparePost);
	else if (src_elem.localName == "img"){
		preparePost(src_elem);
	}
}

activateWebcam();