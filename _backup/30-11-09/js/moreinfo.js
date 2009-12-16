function moreinfo(id, filename, code){

		$.post("includes/"+filename+"?code="+code, function(data){
			var div = document.createElement("div");
				div.innerHTML = data;
				
			var divCont = $("#"+id);			
			divCont.css("height", divCont.height()+"px")
				   .empty()
				   .append(div)
				   .stop()
				   .animate({ height : $(div).height()+"px" }, 700);
				   
		});

}
