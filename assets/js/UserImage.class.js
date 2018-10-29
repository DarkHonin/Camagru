import Layer from "./Layer.class.js";
export class UserImage extends Layer{
	constructor(src, size){
		var canvas = document.createElement("canvas");
		
		if(size){
			canvas.width = size.w;
			canvas.height = size.h;	
			canvas.getContext("2d").drawImage(src, 0, 0);
			super(canvas.toDataURL("image/png"), "Background");
			this.canvas = canvas;
			this.actual_size = size;
		}else{
			canvas.width = src.width;
			canvas.height = src.height;
			super(src, "Background");
		}
		this.canvas = canvas;
		this.background = this;
		this.layers = [];
		this.ctx  = this.canvas.getContext("2d");
	}

	render(context, {w, h}){
		this.canvas.width = this.size.w;
		this.canvas.height = this.size.h;
		var own_context = this.canvas.getContext("2d");
		super.render(own_context);
		this.layers.forEach(i => {i.render(own_context)});
		var img = new Image();
		img.onload = function(){
			context.drawImage(img, 0, 0, w, h);
		}
		img.src = this.canvas.toDataURL("image/png");
		return img;
	}

	addFilter(object){
		this.addLayer(new Layer(object.children[0].src,object.children[1].innerText));
		document.create.renderLayerHtml();
	}

	addLayer(layer){
		layer.index = this.layers.length;
		this.layers.push(layer);
	}

	reset(){
		this.layers = [];
		system_redirect("/create");
	}

}
export default UserImage