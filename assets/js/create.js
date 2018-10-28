import Layer from "./Layer.class.js";
import UserImage from "./UserImage.class.js";
var userImage;
const constraints = {
	video: true
	};

var grabbedAt;
var grabbed = false;
var activeLayer;
var video, file_o, layer_o;
var stream;

export function init_create(){
	canvas = document.createElement("canvas");
	ctx = canvas.getContext("2d");
	console.log("Loaded create js");
	//document.querySelector('#scale').addEventListener("change", changeScale);
	video = document.querySelector('video');
	
	//layer_o = document.querySelector('#layers');
	activateWebcam();
}

function editLayer(event){
	document.querySelectorAll("li.active").forEach(i => {i.classList.remove("active")});
	this.classList.add("active");
	console.log(event.target.getAttribute('index'));
	console.log(layers);
	if(!layers[event.target.getAttribute('index')])
		return;
	activeLayer = layers[event.target.getAttribute('index')];
	document.querySelector('#scale').value = activeLayer.scale;
}

function changeScale(event){
	if(!activeLayer)
		return event.target.value = 0;
	activeLayer.scale = event.target.value;
	drawLayers();	
}


var layers = [];


function hasGetUserMedia() {
	return !!(navigator.mediaDevices &&
		navigator.mediaDevices.getUserMedia);
	}
	

function aspectRatio(maxWidth, maxHeight, width, height){
	var ratio = maxWidth / width;
	if(ratio * height > maxHeight)
		ratio = maxHeight / height;
	return ratio;
	}


function loadLayer(src, title, ready){
	var img = new Image();
	var layer;
	img.onload = function(){
		var size = {width:img.width, height:img.height};
		console.log("Layer created");
		layer = new Layer(img, size, title);
		layer.index = layers.length;
		layers.push(layer);
		if(ready)
			ready(layer);
	}
	img.src = src;
}

function bindFilters(){
	document.querySelectorAll(".filter").forEach(i => {i.addEventListener("click", addFilter)});
}

function addFilter(event){
	var title = this.getAttribute("title");
	loadLayer(event.target.getAttribute("src"), title, setupLayers);
}

function setupLayers(){
	layer_o.innerHTML = "";
	layers.forEach(function(v, i){
		v.draw(v.display_size);
		v.index = i;
		layer_o.appendChild(v.html);
		console.log("setup layer: ", v);
	});
}



function drawLayers(s=false){
	ctx.fillRect(0, 0, canvas.width, canvas.height);
	layers.forEach(function(v, i){
		if(!s)
		v.draw(v.display_size);
		else
		v.draw(v.size)
	});
}

function grab(event){
	if(!activeLayer)
	return;
	grabbed = (event.button == 0)
	var pos = activeLayer.offset;
	var s = activeLayer.display_size;
	//Grabbed at is the floating point of the mouse click
	//relative to the active layer image
	grabbedAt = {
		x: (event.offsetX - pos.x) / s.width,
		y: (event.offsetY - pos.y) / s.height
	};
}

function letgo(){
	grabbed = false;
}

function updateActiveLayer(event){
	if(!activeLayer)
	return;
	if(!grabbed)
	return;
	var pos = {
		x: event.offsetX / canvas.width,
		y: event.offsetY / canvas.height
	}
	activeLayer.update_pos(pos);
	drawLayers();
}

function resetImage(){
	activateWebcam();
	ctx.fillRect(0, 0, canvas.width, canvas.height);
	return function(){
		var old = document.querySelector(".panel.active");
		var newi = document.querySelector("#getImage");
		animate(100, old, fadeout, function(){
			old.classList.remove("active");
			layer_o.innerHTML = "";
			layers = [];
			newi.classList.add("active");
			animate(100, newi, fadein);
		}
	);
	}
}


function delLayer(){
	if(!activeLayer || activeLayer.index == 0)
		return;
	var akID = activeLayer.index;
	console.log(akID, activeLayer);
	layers.splice(akID, 1);
	layers.forEach(function(v, i){
		v.index = i;
	});
	activeLayer = null;
	setupLayers();
}

function postImage(){
	var fd = new FormData();
	canvas.width = layers[0].size.width
	canvas.height = layers[0].size.height
	drawLayers(true);
	fd.set("image", canvas.toDataURL("image/png"));
	document.page.state.payload = window.fd_to_json(fd);
	ajax("post", "/", object_to_fd({page: JSON.stringify(page_state)}), {js:function(response){
		alert("Message sent");
		document.page.state.payload = "";
		update_page(response);
	}})
}

class Creator{

	constructor(){
		this.userImage = {};
		this.video = {};
		this.file_o = {};
		this.previewCanvas = {};
	}

	set preview(p){this.previewCanvas=p;}

	init(){
		this.video = document.querySelector('video');
		this.activateWebcam();
		this.file_o = document.querySelector('#file');
	}

	deactivateWebcam(){
		if(stream && stream.active){
			var track = stream.getTracks()[0];
			track.stop();
		}
	}

	render(canvas){
		var context = canvas.getContext("2d");
		this.userImage.render(context, {w:canvas.width, h:canvas.height});
	}

	render_preview(){
		this.render(this.previewCanvas);
	}
	
	activateWebcam(event){
		if(stream && stream.active || !this.video)
			return;
		if (hasGetUserMedia()) {
			navigator.mediaDevices.getUserMedia(constraints).then(
				(theStream) => {stream = theStream; this.video.srcObject = theStream}
				);
			setTimeout(document.create.deactivateWebcam, 100000);
		} else {
			alert('getUserMedia() is not supported by your browser');
		}
	}

	captureWebcamImage(){
		this.userImage = new UserImage(this.video, {w: this.video.videoWidth, h: this.video.videoHeight});
		this.deactivateWebcam();
		system_reload(["preview", "imageControlls", "Stickers"]);
	}

	captureFileImage(){
		var file = this.file_o.files[0];
		if(!file){
			console.log("no File selected");
			return;
		}
		this.deactivateWebcam();
		var reader = new FileReader();
		reader.addEventListener("error",function(err){
			console.log(err);
		});
		reader.onload = function(){
			document.create.userImage = new UserImage(reader.result);
		};
		if (reader.readAsDataUrl)
			reader.readAsDataUrl(file);
		else if (reader.readAsDataurl)
			reader.readAsDataurl(file);
		else if (reader.readAsDataURL)
			reader.readAsDataURL(file);
		system_reload(["preview", "imageControlls", "Stickers"]);
	}

	get ratio(){
		var s = this.image.size;
		console.log(s);
		return s.w / s.h;
	}

	get image(){
		return this.userImage;
	}
}

document.create = new Creator();
