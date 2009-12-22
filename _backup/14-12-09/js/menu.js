/* 
 * Aplica los efectos sobre los menus
 */

$(document).ready(function(){
    $('#header .top_page .top_menu li').hover(function() {
													   
		var span = $('.menu', this);
		if( span[0].className.indexOf("current")==-1 ){
			span.stop().animate({ opacity: 1 }, 700, 'easeInSine');
		}

    },function() {
		
		var span = $('.menu', this);
		if( span[0].className.indexOf("current")==-1 ){
			span.stop().animate({opacity: 0}, 500, 'easeOutExpo')
		}
    })
});
