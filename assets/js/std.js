var display_error;

function ready(){
    console.log("ready");
    display_error = document.querySelector("#global_error");
}

function default_response($resp){
    $js = JSON.parse($resp);
    if(!$js.status){

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

function comment(){

}

function like_post(){

}

function delete_post(){

}