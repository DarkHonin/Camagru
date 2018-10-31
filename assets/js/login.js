
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
	var display = document.querySelector("#global_error");
	if(!js.status){
		var error = js.data.error;
		var form = js.data.form;
		display.classList.remove("success");
		display.classList.add("error");
		for( var k in error){
			if(k == "csrf-token" || k == "global")
				display.innerHTML = error[k]
			else{
				document.querySelector("#"+form+" input[name="+k+"] + .invalid.error").innerHTML = error[k];
			}
		}
	}else{
		display.classList.add("success");
		display.classList.remove("error");
		document.querySelector("#global_error").innerHTML = js.message;
		if(js.data.redirect)
			setTimeout(function(){
				if(js.data.redirect)
					window.location.href = js.data.redirect;
				display.innerHTML = "";
			}, 1000);
	}
}

function ajax(method, target, data, resp){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            resp(this.responseText);
        }
    };
    xhttp.open(method, target, true);
    console.log("sending now");
    xhttp.send(data);
}