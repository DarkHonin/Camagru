
document.querySelectorAll("form").forEach(i => {i.addEventListener("submit", submit_form)});

function submit_form(event){
	event.preventDefault();
	$fd = new FormData(event.target);
	ajax("post", event.target.action, $fd, check_response);
}

function check_available(me){
	var name = me.value;
	var fd = new FormData();
	fd.set("item", name);
	ajax("post", "/info/"+me.name+"_available", fd, function(data){
		var js = JSON.parse(data);
		if(!js.status){
			me.nextSibling.innerHTML = "Already in use";
		}else{
			me.nextSibling.innerHTML = "";
		}
	});
}

function check_response(data){
	var js = JSON.parse(data);
	if(!js.status){
		var error = js.data.error;
		var form = js.data.form;
		display_error.classList.remove("success");
		display_error.classList.add("error");
		for( var k in error){
			if(k == "csrf-token" || k == "global")
				display_error.innerHTML = error[k]
			else{
				document.querySelector("#"+form+" input[name="+k+"] + .invalid.error").innerHTML = error[k];
			}
		}
	}else{
		display_error.classList.add("success");
		display_error.classList.remove("error");
		document.querySelector("#global_error").innerHTML = js.message;
		if(js.data.redirect)
			setTimeout(function(){
				if(js.data.redirect)
					window.location.href = js.data.redirect;
				display_error.innerHTML = "";
			}, 1000);
	}
}