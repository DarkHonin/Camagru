export class Layer{
	constructor(src, title){
		console.log(src);
		var instance = this;
		this.image = new Image();
		this.image.onload=function(){
			instance.actual_size = {w: this.width, h: this.height}
		};
		this.actual_size = {w: 0, h: 0};
		this.image_scale = 1;
		this.pos = {x: 0, y: 0};
		this.title = title;
		this.layer_index = -1;
		this.image.src = src;
		this.grabbedAt = {}
		console.log(this);
	}

	render(context){
		context.drawImage(this.image, this.pos.x * context.canvas.width, this.pos.y * context.canvas.height, this.size.w, this.size.h);
	}

	draw(ctx, s) {
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
		return {w:this.actual_size.width * this.image_scale, 
				h:this.actual_size.height * this.image_scale
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
		var dom = document.createElement("span");
		dom.setAttribute("index", this.layer_index)
		dom.setAttribute("class", "dark-accent-bg light-accent");
		dom.addEventListener("click", document.create.activateLayer);
		dom.appendChild(document.createTextNode(this.title));
		return dom;
	}

	grab(event){
		this.grabbedAt = { x: event.layerX / event.target.width, y:event.layerY / event.target.height};
	}

	letgo(event){
		this.grabbedAt = null;
		console.log("released", this);
	}

	move(event){
		if(!this.grabbedAt)
			return;
		var ex = { x: event.layerX / event.target.width, y:event.layerY / event.target.height}
		this.pos = {x: ex.x - this.grabbedAt.x, y: ex.y - this.grabbedAt.y}
		document.create.render_preview();
		console.log(this.pos);
	}
}

export default Layer