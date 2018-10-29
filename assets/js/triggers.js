function part_trigger_preview(){
	
}

function page_init_create(){
	document.create.init();
}

function page_init_login(){
	console.log("Loaded part js for Forms");
	document.querySelectorAll("form").forEach((i) => {i.addEventListener("submit", submit_form);})
}

function part_trigger_imageControlls(){
	
}

function page_init_(){
	document.create.deactivateWebcam();
}

function filter_nav(obj){
	if(!obj)
		system_reload(["Stickers"]);
	else
		system_reload(["Stickers"], {filter_step:obj.id});

}