function moreinfo(id, filename, code){

    $.post("includes/"+filename+"?code="+code, function(data){
            var div = document.createElement("div");
                $(div).html(data)
                      .css("position", "relative")
                      .css("left", "0")
                      .css("top", "0")
                      .css("padding", "0")
                      .css("margin", "0");

            var divCont = $("#"+id);
            divCont.css("height", divCont.height()+"px")
                       .empty()
                       .append(div)
                       .stop()
                       .animate({ height : ($(div).height()+20)+"px" }, 700);

    });

}
