var display_error;
var content;
if(!form_sub)
    var form_sub = form_submit;

function ready(){
    console.log("ready");
    display_error = document.querySelector("#global_error");
    document.querySelectorAll("form").forEach(i => i.addEventListener("submit", form_sub));
    content = document.querySelector("#page_content");
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

function like_post(item){
    var fd = new FormData();
    var id = item.getAttribute("post_id");
    fd.set("post", id);
    ajax("post", "/like", fd, function(rsp){
        var js = JSON.parse(rsp);
        if(js.status)
            item.innerHTML = js.value;
        default_response(rsp);
    });
}

function follow_user(item){
    var fd = new FormData();
    fd.set("user", item.getAttribute("user_id"));
    ajax("post", "/follow", fd, function(rsp){
        var js = JSON.parse(rsp);
        if(js.status)
            item.innerHTML = js.value;
        default_response(rsp);
    });
}

function delete_post(item){
    var fd = new FormData();
    fd.set("post", item.getAttribute("post_id"));
    ajax("post", "/delete", fd, function(rsp){
        var js = JSON.parse(rsp);
        if(js.status)
            document.querySelector("#post_"+item.getAttribute("post_id")).remove();
        default_response(rsp);
    });
}
var scroll_timeout;
function check_for_feed_update(){
    var mark = document.querySelector("#feedmarker");
    if(!mark || scroll_timeout)
        return;
    if(content.offsetHeight + content.scrollTop == content.scrollHeight){
        var lid = mark.getAttribute("last_id");
        var user = mark.getAttribute("user");
        var fd = new FormData();
        fd.set("user", user);
        fd.set("last", lid);
        ajax("post", "/feed", fd, function(rsp){
            if(!rsp.length)
                return;
            var help = document.createElement("div");
            help.innerHTML = rsp;
            help.childNodes.forEach(f => mark.parentNode.insertBefore(f, mark));
            mark.remove();
        });
        scroll_timeout = setTimeout(function(){
            scroll_timeout = null;
        }, 1000);
    }
}
