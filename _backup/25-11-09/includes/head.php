<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<!--<script type="text/javascript" src="js/jquery-ui-1.7.1/jquery-ui-1.7.2.custom.min.js"></script>-->
<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/chat.js"></script>

<!--===== JQuery Rainbows (GRADIENTES) =======-->
<link href="js/jquery.rainbows/rainbows.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.rainbows/rainbows.js"></script>
<script type="text/javascript">
<!--
	rainbows.init({
		selector: '.grad1',
		highlight: false,
		shadow: true,
		from: '#8ED9FA',
		to: '#1977A0'
	});
-->
</script>
<!--===== END SCRIPT =======-->

<!--===== JQuery jquery.slider.gallery (ULTIMOS TRABAJOS) ======-->
<link href="js/jquery.slider.gallery/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.slider.gallery/js/jquery.slider.gallery.js"></script>
<script type="text/javascript">
<!--
    var SG = new ClassSliderGallery({
        selector : '#cont_slider',
        load     : 'includes/portfolio.php'
    })
-->
</script>
<!--===== END SCRIPT =======-->

<script type="text/javascript">
<!--
function showinfo(id, code){
        /*var div = $("#"+id+" .cont_slide");

        var dataTextSimple={
           height : div[0].offsetHeight,
            data : div[0].innerHTML
        }

        $(div[1]).load("includes/info.php?code="+code, function(){
           alert(this.offsetHeight)
        });*/


        /*var height = $("#"+id).height();
        $("#"+id).css("height", height+"px")
                 .load("includes/info.php?code="+code, function(){
                     $(this).hide().slideToggle("slow")
                 });*/

        var div = $("#"+id);
        if( !div[0].textIntro ){
            div[0].textIntro = div.html();
            div.slideToggle(600)
               .load("includes/info.php?code="+code)
               .slideToggle(800)

        }else{
            div.slideToggle(600)
               .html(div[0].textIntro)
               .slideToggle(800);
               div[0].textIntro = false;
        }

}
-->
</script>

