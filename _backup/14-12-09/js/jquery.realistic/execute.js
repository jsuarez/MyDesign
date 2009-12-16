$(document).ready(function() {

	$(".image_icon a").hover(function() {
		$(this).stop().animate({ marginTop: "5px" }, 200);
	},function(){
		var e = this;
		$(e).stop().animate({ marginTop: "30px" }, 300, function(){
			$(e).animate({ marginTop: "20px" }, 250);
		});
	});

});