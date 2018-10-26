const constraints = {
	video: true
	};

class Layer{
	constructor(image, size, title){
		this.image = image;
		this.actual_size = size;
		this.image_scale = 1;
		this.pos = {x: 0, y: 0};
		this.title = title;
		this.layer_index = -1;
		console.log(this);
	}

	draw() {
		var s = this.size;

		ctx.drawImage(this.image, this.pos.x, this.pos.y, s.width, s.height);
	}

	set offset(pos){
		this.pos = pos;
	}

	get offset(){
		return this.pos;
	}

	set index(i){
		this.layer_index = i;
	}

	get size(){
		return {width:this.actual_size.width * this.image_scale, height:this.actual_size.height * this.image_scale};
	}

	get scale(){
		return this.image_scale;
	}

	set scale(s){
		this.image_scale = s;
	}

	get html(){
		var dom = document.createElement("li");
		dom.setAttribute("index", this.layer_index)
		dom.appendChild(document.createTextNode(this.title));
		dom.addEventListener("click", editLayer);
		return dom;
	}
}

var activeLayer;

function editLayer(event){
	document.querySelectorAll("li.active").forEach(i => {i.classList.remove("active")});
	this.classList.add("active");
	console.log(event.target.getAttribute('index'));
	console.log(layers);
	activeLayer = layers[event.target.getAttribute('index')];
	document.querySelector('#scale').value = activeLayer.scale;
}

function changeScale(event){
	if(!activeLayer)
		event.target.value = 0;
	activeLayer.scale = event.target.value;
	drawLayers();	
}

var page;
const video = document.querySelector('video');
const file_o = document.querySelector('#file');
const layer_o = document.querySelector('#layers');
document.querySelector('#scale').addEventListener("change", changeScale);
var stream;

var layers = [];

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
	
	var canvas, ctx;
	canvas = document.getElementById("viewImage");
	ctx = canvas.getContext('2d');
	ctx.fillRect(0, 0, canvas.width, canvas.height);

function aspectRatio(maxWidth, maxHeight, width, height){
	var ratio = maxWidth / width;
	if(ratio * height > maxHeight)
		ratio = maxHeight / height;
	return {width: ratio*width, height: ratio * height};
	}

function captureWebcamImage(){
	return function(){
		var old = document.querySelector(".panel.active");
		var newi = document.querySelector("#editImage");
		animate(100, old, fadeout, function(){
			old.classList.remove("active");
			newi.classList.add("active");
			animate(100, newi, fadein, function(){
				canvas.width = video.videoWidth ;
				canvas.height = video.videoHeight;
				ctx.drawImage(video, 0,0, canvas.width, canvas.height);
				deactivateWebcam();
				loadLayer(canvas.toDataURL("image/png"), "background", function(layer){
					canvas.width = layer.size.width;
					canvas.height = layer.size.height;
					canvas.style.maxWidth = canvas.width;
					canvas.style.maxHeight = canvas.height;
					setupLayers();
				});
			});	
		}
		);
	}
}

function loadLayer(src, title, ready){
	var img = new Image();
	var layer;
	img.onload = function(){
		var size = aspectRatio(canvas.offsetWidth, canvas.offsetHeight, img.width, img.height);
		console.log("Layer created");
		layer = new Layer(img, size, title);
		layer.index = layers.length;
		layers.push(layer);
		if(ready)
			ready(layer);
	}
	img.src = src;
}

function captureFileImage(){
	var file = file_o.files[0];
	if(!file){
		console.log("no File selected");
		return;
	}
	deactivateWebcam();
	console.log("File "+file);
	var reader = new FileReader();
	reader.addEventListener("error",function(err){
		console.log(err);
	});
	reader.onload = function(){
		console.log("File read complete");
		loadLayer(reader.result, "background", function(layer){
			canvas.width = layer.size.width;
			canvas.height = layer.size.height;
			canvas.style.maxWidth = canvas.width;
			canvas.style.maxHeight = canvas.height;
			setupLayers();
		});
	};
	console.log("reading File");
	
	return function(){
		var old = document.querySelector(".panel.active");
		var newi = document.querySelector("#editImage");
		animate(100, old, fadeout, function(){
			old.classList.remove("active");
			newi.classList.add("active");
			animate(100, newi, fadein, function(){
				if (reader.readAsDataUrl)
					reader.readAsDataUrl(file);
				else if (reader.readAsDataurl)
					reader.readAsDataurl(file);
				else if (reader.readAsDataURL)
					reader.readAsDataURL(file);
			});
		}
		);
	}
}

function bindFilters(){
	document.querySelectorAll(".filter").forEach(i => {i.addEventListener("click", addFilter)});
}

function addFilter(event){
	var img = this.querySelector("#filter_"+this.getAttribute("image"));
	var title = this.getAttribute("title");
	var size = {width: img.width, height: img.height};
	var layer = new Layer(img, size, title);
	layer.index = layers.length;
	layers.push(layer);
	setupLayers();
}

function setupLayers(){
	layer_o.innerHTML = "";
	layers.forEach(function(v, i){
		v.draw();
		layer_o.appendChild(v.html);
	});
}



function drawLayers(){
	ctx.fillRect(0, 0, canvas.width, canvas.height);
	layers.forEach(function(v, i){
		v.draw();
	});
}

function switchEvent(event){
	var act = event.target.getAttribute("action");
	console.log("action trigger: "+act);

	if(page.parts.create[act])
		var after = page.parts.create[act]();
	if(after)
		after();
}

canvas.addEventListener("mousemove", updateActiveLayer)
canvas.addEventListener("mousedown", grab);
canvas.addEventListener("mouseup", letgo);

var grabbed = false;
var grabbedAt;
function grab(event){
	if(!activeLayer)
		return;
	grabbed = (event.button == 0)
	var pos = activeLayer.offset;
	var s = activeLayer.size;
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
	var s = activeLayer.size;
	var grab = {x:event.offsetX, y:event.offsetY};
	var pos = {
		x:  grab.x - (s.width * grabbedAt.x),
		y:  grab.y - (s.height * grabbedAt.y)
	};
	activeLayer.offset = pos
	drawLayers();
}

function loabBindings(){
	console.log("Loaded create js");
	page  = document.page;
	document.querySelectorAll(".switch").forEach((i) => {i.addEventListener("click", switchEvent)});
	activateWebcam();
}

bindFilters();

function resetImage(){
	userImage = new Image();
	activateWebcam();
	ctx.fillRect(0, 0, canvas.width, canvas.height);
	return function(){
		var old = document.querySelector(".panel.active");
		var newi = document.querySelector("#getImage");
		animate(100, old, fadeout, function(){
			old.classList.remove("active");
			layer_o.innerHTML = "";
			layers = {};
			newi.classList.add("active");
			animate(100, newi, fadein);
		}
	);
	}
}

ctx.globalAlpha = 0.5;
export var mod = {
	init: loabBindings,
	captureFromFile : captureFileImage,
	captureFromCam: captureWebcamImage,
	resetImage : resetImage
}