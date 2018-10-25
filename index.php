<?php
	include_once("parts/head.php");
?>

<body>
	<div class="content">
	
	</div>
	<div class="sidebar">
	<?php
	include_once("parts/menue.php");
?>
	</div>
</body>
<script>
	document.querySelectorAll(".nav").forEach((i) => {i.addEventListener("click", navigate);})
	const content = document.querySelector(".content");
	var target;
	function navigate(event){
		event.preventDefault();
		target = event.target;
		animate(500, content, slideup, loadPage);
		return false;
	}

	function loadPage(){
		var page = target.getAttribute("href");
		ajax("get", page, null, function(responseText){
			content.innerHTML = responseText;
			content.querySelectorAll("script").forEach((i) => {eval(i.innerHTML)})
		});
		animate(500, content, slidedown);
	}

	function ajax(method, target, data, ondone){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				if(ondone)
					ondone(this.responseText);
			}
		};
		xhttp.open(method, target, true);
		xhttp.send(data);
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

	
</script>
</html>