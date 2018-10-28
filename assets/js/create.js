
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
		if(layers[0]){
			this.aspect_ratio = aspectRatio(layers[0].display_size.width, layers[0].display_size.height, size.width, size.height);
		}
		else
			this.aspect_ratio = aspectRatio(canvas.offsetWidth, canvas.offsetHeight, size.width, size.height);
		console.log(this);
	}

	draw(s) {
		ctx.drawImage(this.image, this.pos.x * canvas.width, this.pos.y * canvas.height, s.width, s.height);
	}
	get ratio(){
		return this.aspect_ratio;
	}
	set offset(pos){
		this.pos = pos;
	}

	update_pos({x, y}){
		var pos = {
			x:  x - grabbedAt.x,
			y:  y - grabbedAt.y
		};
		this.pos = pos;
	}

	get offset(){
		return this.pos;
	}

	set index(i){
		this.layer_index = i;
	}

	get index(){
		return this.layer_index;
	}

	get display_size(){
		return {width:(this.actual_size.width * this.aspect_ratio) * this.image_scale, 
				height:(this.actual_size.height * this.aspect_ratio) * this.image_scale
		}
	}

	get size(){
		return this.actual_size;
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

var grabbedAt;
var grabbed = false;
var canvas, ctx;
var activeLayer;
var video, file_o, layer_o;
var stream;

function init_create(){
	console.log("Loaded create js");
	document.querySelector('#scale').addEventListener("change", changeScale);
	canvas = document.getElementById("viewImage");
	ctx = canvas.getContext('2d');
	canvas.addEventListener("mousemove", updateActiveLayer)
	canvas.addEventListener("mousedown", grab);
	canvas.addEventListener("mouseup", letgo);
	video = document.querySelector('video');
	file_o = document.querySelector('#file');
	layer_o = document.querySelector('#layers');
	ctx.globalAlpha = 0.5;
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

function deactivateWebcam(){
	if(stream && stream.active){
		var track = stream.getTracks()[0];
		track.stop();
	}
}

function activateWebcam(event){
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
	

function aspectRatio(maxWidth, maxHeight, width, height){
	var ratio = maxWidth / width;
	if(ratio * height > maxHeight)
		ratio = maxHeight / height;
	return ratio;
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
					canvas.width = layer.display_size.width;
					canvas.height = layer.display_size.height;
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
			canvas.width = layer.display_size.width;
			canvas.height = layer.display_size.height;
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
