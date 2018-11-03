var display_error;

function ready(){
    console.log("ready");
    display_error = document.querySelector("#global_error");
    document.querySelectorAll("form").forEach(i => i.addEventListener("submit", form_submit));
}

function form_submit(event){
    event.preventDefault();
    $fd = new FormData(event.target);
	ajax("post", event.target.action, $fd);
}

function default_response($resp){
    $js = JSON.parse($resp);
    display_error.classList.remove("error");
    display_error.classList.remove("success");
    if($js.status) display_error.classList.add("success");
    if(!$js.status) display_error.classList.add("error");
    display_error.innerHTML = $js.message;
    setTimeout(function(){
        display_error.innerHTML = "";
        if($js.redirect) window.location.href = $js.redirect;
    },2000);
}

function ajax(method, target, data, resp=default_response){
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

function comment(){

}

function like_post(){

}

function delete_post(){

}