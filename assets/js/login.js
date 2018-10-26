
	var status;

	function submit_form(event){
		event.preventDefault();
		let method = event.target.method;
		let fd = new FormData(event.target);
		fd.set("action", event.target.id);
		console.log("sending data to server");
        document.page.state.payload = window.fd_to_json(fd);
        ajax("post", "/", window.object_to_fd({page: JSON.stringify(page_state)}), {js:handle_form_response})
	}

	function handle_form_response(data){
        console.log("respone recieved");
		var item = JSON.parse(data);
		if(item.error){
			status.innerHTML = item.error;
			status.classList.add("error");
        }
        update_page(item);
	}

	function partload(){
		console.log("Loaded part js for Forms");
		document.page.content.querySelectorAll("form").forEach((i) => {i.addEventListener("submit", submit_form);})
		status = document.page.content.querySelector("#status");
	};
	
	export var mod = {
		init : 	partload
}