/* 
 * Aplica los efectos sobre los menus
 */

$(document).ready(function(){
    $('#header .top_page .top_menu li .hover').css("opacity", "0");

    $('#header .top_page .top_menu li').hover(function() {
        // Stuff that happens when you hover on

        $('.hover', this).stop().animate({
            'opacity': 1
        }, 700, 'easeInSine')


    },function() {
        // Stuff that happens when you unhover

        $('.hover', this).stop().animate({
            'opacity': 0
        }, 500, 'easeOutExpo')

    })
});
