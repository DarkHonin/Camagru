<?php?>

<div class="anounce">
    Find users
</div>

<div class='anounce search'>
    <fieldset>
        <label>User search</label>
        <input type='text' oninput="user_lookup(this)">
    </fieldset>
</div>

<div id='user_container'>

</div>

<script>
const cont = document.querySelector("#user_container");

function user_lookup(me){
    var query = me.value;
    var fd = new FormData();
    fd.set("query", query);
    ajax("post", window.location, fd, function(resp){
        cont.innerHTML = resp;
    });
}

</script>