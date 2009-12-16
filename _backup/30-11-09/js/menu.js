/* 
 * Aplica los efectos sobre los menus
 */

$(document).ready(function(){
    $('#header .top_page .top_menu li .hover').each(function(){
		if( this.className.indexOf("current")==-1 ) $(this).css("opacity", "0");
	 });

    $('#header .top_page .top_menu li').hover(function() {
        // Stuff that happens when you hover on
		
		var span = $('.hover', this);
		if( span[0].className.indexOf("current")==-1 ){
			span.stop().animate({ opacity: 1 }, 700, 'easeInSine');
			//$("a", this).stop().animate({color:'#000000'}, 1000);
		}

    },function() {
        // Stuff that happens when you unhover
		var span = $('.hover', this);
		if( span[0].className.indexOf("current")==-1 ){
			span.stop().animate({opacity: 0}, 500, 'easeOutExpo')
			//$("a", this).stop().animate({color:'#ffffff'}, 500);
		}
    })
});
