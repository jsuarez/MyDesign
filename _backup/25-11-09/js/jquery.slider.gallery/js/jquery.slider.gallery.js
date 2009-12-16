/*
 * jQuery JavaScript Library v1.3
 * http://jquery.com/
 *
 * Copyright (c) 2009 Ivan Mattoni
 * Empresa MyDesign
 * Dual licensed under the MIT and GPL licenses.
 * http://docs.jquery.com/License
 *
 */
 
var ClassSliderGallery = function(options){
    ////////////////////////////////////////////////////////////////////////
    ///		 	      CONSTRUCTOR				 ///
    ////////////////////////////////////////////////////////////////////////
    var DEFAULTS={
        selector    :   '',	  // [STRING]
        load        :   null,     // [STRING]
        speed       :   500       // [INTEGER]
    };

    options = $.extend({}, DEFAULTS, {}, options);

    var container = false;
    $(document).ready(function(){
        container = $(options.selector);
        if( container.length>0 ) {
            if( options.load!=null && options.load!="" ) show();
            else init();
        }
        else error=true;
    });


    ////////////////////////////////////////////////////////////////////////
    ///          	      PUBLIC PROPERTIES			 	 ///
    ////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////
    ///         	      PUBLIC METHODS			 	 ///
    ////////////////////////////////////////////////////////////////////////
    this.next = function(){
        if( error || working ) return;

        if( index <= data.countPage ){
            index++;
            data.thumbContainer.stop().animate({
                left : '-='+data.thumbWidth
            }, options.speed);
        }else{
            index=1;
            data.thumbContainer.stop().animate({ left : '+=50' }, 550)
                                      .animate({ left : '0' }, 800);
        }
        
        return false;
    };
    this.prev = function(){
        if( error || working ) return;

        if( index > 1 ){
            index--;
            data.thumbContainer.stop().animate({
                left : '+='+data.thumbWidth
            }, options.speed);
        }else{
            index=data.countPage+1;
            data.thumbContainer.stop().animate({ left : '+=50' }, 550)
                                      .animate({ left : '-='+(data.countPage*data.thumbWidth+50) }, 800);
        }

        return false;
    };



    ////////////////////////////////////////////////////////////////////////
    ///		  	      PRIVATE PROPERTIES                         ///
    ////////////////////////////////////////////////////////////////////////
    var error = false;
    var working = true;
    var data = {};
    var index = 1;
	

    ////////////////////////////////////////////////////////////////////////
    ///			      PRIVATE METHODS	  	  	         ///
    ////////////////////////////////////////////////////////////////////////
    var show = function(){
        $.ajax({
            type: "POST",
            url: options.load,
            //dataType: "json",
            success: function(result){
                container.html(result);
            },
            error: function(xml, status){
                if( status=="error" ){
                    if( xml.status==404 ){
                        alert('Error en Script: JQuery Slider Gallery.\n'+
                              'La ruta "'+options.load+'" no existe.');
                    }
                    error=true;
                }
            },
            complete: function(){
                init();
            }
        });
    };

    var init = function(){
        data.images = $(options.selector+" img");
        data.imageCount = data.images.length;
        data.thumbWidth = $(options.selector+" li:first").width();
        data.thumbContainer = $(options.selector+" ul");


        // Aplica el ancho al contenedor de thumbs
        data.thumbContainer.css("width", (data.thumbWidth*data.imageCount)+"px");

        var src = new Array();
        data.images.each(function(i){
            if( !data.CountThumbByPage && (this.offsetLeft+this.offsetWidth)>container[0].offsetWidth ) data.CountThumbByPage = i;
            src.push(this.src);
        })
        preloadImages(src);

        data.countPage = data.imageCount - data.CountThumbByPage;
        working=false;
    };

    var preloadImages = function(a){
        var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
        var i,j=d.MM_p.length;
        for(i=0; i<a.length; i++) if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
    }
		
}