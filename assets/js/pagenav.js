const content = document.querySelector(".content");

function init_triggers(){
    console.log("loading nav triggers");
    document.querySelectorAll(".nav").forEach((i) => {i.addEventListener("click", navigate)})
}

var target;

var page_state = {
    current_path: window.location.pathname,
    request_path: "",
    payload: "",
    request_type: "page"
};

console.log(page_state);

function navigate(event){
    event.preventDefault();
    page_state.request_path = this.target;
    excecute_nav();
    return false;
}

function excecute_nav(){
    console.log("excecuting nav");
    ajax("post", "/", object_to_fd({page: JSON.stringify(page_state)}), {onhtml:function(responseText){
        animate(500, content, slideup, function(){
            load_html(responseText);
            animate(500, content, slidedown);
        });
    },js:update_page} )
    
        
}

function load_html(responseText){
    console.log("loadig html content");
    content.innerHTML = responseText;
    window.history.pushState("", "", page_state.current_path);
    if(page_state.current_path != "/")
        import("./"+page_state.current_path+".js");
}

function ajax(method, target, data, {onhtml, js}){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var type = this.getResponseHeader("Content-Type");
            if(page_state.request_path){
                page_state.current_path = page_state.request_path ;
                page_state.request_path = "";
            }
            console.log("response type: "+type);
            if(type != "application/json" && onhtml)
                onhtml(this.responseText, type);
            else{
                if(js)
                    js(this.responseText, type);
            page_state.payload = "";
            
        }
        }
    };
    xhttp.open(method, target, true);
    console.log("sending now");
    xhttp.send(data);
}

function update_page(json){
    console.log(json)
    if(typeof json == "string")
        json = JSON.parse(json);
    for(var action in json){
        if(!json.hasOwnProperty(action))
            continue;
        var data = json[action];
        console.log("Attempting action: "+action);
        console.log("Attempting data: "+ data);
        if(window["system_"+action])
            window["system_"+action](data);
        console.log("Action complete: "+action);
        
    }
}

function system_redirect(page){
    console.log("Redirecting to: "+page);
    page_state.request_path = page;
    excecute_nav();
}

function system_reload(parts){
    parts.forEach(i => {
        let part = document.querySelector("#"+i);
        if(!part)
            return alert("No item "+i);
            var req = JSON.parse(JSON.stringify(page_state));
            req.current_path = "/part";
            req.request_path = "";
            req.payload = JSON.stringify({
                "id": i
            });
        ajax("post", "/", object_to_fd({page: JSON.stringify(req)}), {onhtml:function(responseText){
            console.log("part recieved");
            animate(250, part, fadeout, function(){
                part.innerHTML = responseText;
                animate(250, part, fadein, init_triggers);
            });
        }});
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

function fd_to_json(fd){
    ret = {};
    fd.forEach(function (v, k){
        ret[k] = v;
    });
    return JSON.stringify(ret);
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

excecute_nav();

if(document.partload)
    document.partload()