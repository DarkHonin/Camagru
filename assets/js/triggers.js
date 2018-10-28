function part_trigger_preview(){
	preview = document.getElementById("viewImage");
	var ratio = document.create.ratio;
	preview.width = preview.offsetWidth;
	preview.height = preview.offsetWidth / ratio;
	document.create.preview = preview;
	document.create.render_preview();
	//canvas.addEventListener("mousemove", updateActiveLayer)
	//canvas.addEventListener("mousedown", grab);
	//canvas.addEventListener("mouseup", letgo);
}

function page_init_create(){
	document.create.init();
}