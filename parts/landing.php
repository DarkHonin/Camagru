<?php
include_once("parts/head.php");
header("Content-Type: text/html");
?>
<body>
	<div class="content">
	<?php include_once($part) ?>
	</div>
	<div class="sidebar" id="menue">
		<?php
			include_once("parts/menue.php");
		?>
	</div>
</body>
<script>
	const content = document.querySelector(".content");

	function init_triggers(){
		document.querySelectorAll(".nav").forEach((i) => {i.addEventListener("click", navigate)})
	}


	var target;
	function navigate(event){
		event.preventDefault(); 
		target = "/part?id=page::"+event.target.target;
		animate(500, content, slideup, loadPage);
		return false;
	}

	function loadPage(){
		ajax("get", target, null, function(responseText, type){
			if(type !="application/json"){
				if(document['partunload'])
					document.partunload();
				document.partunload = undefined;
				content.innerHTML = responseText;
				content.querySelectorAll("script").forEach((i) => {eval(i.innerHTML)})
				if(document['partload'])
					document.partload();
				document.partload = undefined;
			}
		});
		animate(500, content, slidedown);
	}

	function ajax(method, target, data, ondone){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				if(ondone)
					ondone(this.responseText, this.getResponseHeader("Content-Type"));
				if(this.getResponseHeader("Content-Type")=="application/json")
					handle_page_direction(JSON.parse(this.responseText));
			}
		};
		xhttp.open(method, target, true);
		xhttp.send(data);
	}

	function handle_page_direction(json){
		for(var action in json){
			if(!json.hasOwnProperty(action))
				continue;
			var data = json[action];
			if(window["system_"+action])
				window["system_"+action](data);
		}
	}

	function system_redirect(page){
		console.log("Redirecting to: "+page);
		target = ("?id=page::".page);
		loadPage();
	}

	function system_reload(parts){
		parts.forEach(i => {
			let part = document.querySelector("#"+i);
			if(!part)
				return;
			animate(250, part, fadeout, function(){
				ajax("POST", "/part", object_to_fd({id : i}), function(response, type){
					if(type !="application/json"){
						part.innerHTML = response;
						init_triggers();
						animate(250, part, fadein);
					}
				});
			});
		});
	}

	function object_to_fd(obj){
		var ret = new FormData();
		for(var action in obj){
			if(!obj.hasOwnProperty(action))
				continue;
			var data = obj[action];
			ret.set(action, data);
		}
		return ret;
	}

	function animate(duration, subject, action, oncomplete){
		var timer, anstart;
		anstart = Date.now();
		timer = setInterval(function(){
			var timePassed = Date.now() - anstart;
			if (timePassed >= duration) {
				clearInterval(timer);
				action(1, subject);
				if(oncomplete)
					oncomplete();
				return;
			}
			action(timePassed/duration, subject);
		},20);
	}

	function fadeout(per, item){
		item.style.opacity = 1 - per;
	}

	function slideup(per, item){
		item.style.marginTop = -(per * item.offsetHeight) + "px";
	}

	function slidedown(per, item){
		item.style.marginTop = ( - item.offsetHeight) + (per * item.offsetHeight) + "px";
	}

	function fadein(per, item){
		item.style.opacity = per;
	}

	
	init_triggers();
	if(document.partload)
		document.partload()
</script>
</html>