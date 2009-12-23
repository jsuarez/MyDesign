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
 
var ClassComboBox = function(options){
    /*  CONSTRUCTOR				
      *************************************************************************/
    var DEFAULTS={
        selector    :   '',	  		// [STRING]
        effect      :   1,			// [INTEGER]
        className   :   'jquery-combobox',	// [STRING]
        zIndex      :	500	  		// [INTEGER]
    };

    options = $.extend({}, DEFAULTS, {}, options);

    var combo = false;
    $(document).ready(function(){
        show_combo(options.selector);
    });


     /* PUBLIC PROPERTIES
      *************************************************************************/
     this.elements = new Array();


     /* PUBLIC METHODS
      *************************************************************************/
      this.update = function(selector){
          if( !selector ) selector = options.selector;
          $(selector).each(function(i){
                var div = $(this).next();
                div.find(".jquery-combo-input").html(this.options[0].text);
                var ul = div.find("ul");
                ul.empty();

                for( var i=0; i<=this.options.length-1; i++ ){
                    var li = document.createElement("LI");
                    var a = document.createElement("A");

                    $(a).attr("href", "#"+i)
                        .html(this.options[i].text)
                        .bind("click", show_item)
                        .hover(function(){itemHover=true}, function(){itemHover=false;});

                    $(li).addClass("jquery-combo-list-item")
                         .append(a);

                    $(ul).append(li);
                }

          });

          return false;
      };


     /* PRIVATE PROPERTIES                
      *************************************************************************/
	var This = this;
	var itemHover = false;

     /* PRIVATE METHODS	  	  	       
      *************************************************************************/
	var show_combo = function(selector){
            $(selector).each(function(i){

                $(this).css("display", "none");

                var div = document.createElement("DIV");
                div.ElementSelect = this;
                $(div).addClass(options.className);
                $(div).css("z-index", options.zIndex);

                var div_input = document.createElement("DIV");
                $(div_input).addClass("jquery-combo-input")
                            .html(this.value)
                            .attr("title", this.value);

                var a_arrow = document.createElement("A");
                $(a_arrow).attr("href", "#")
                          .addClass("jquery-combo-arrow")
                          .bind("click", comboSlideDown)
                          .bind("blur", comboSlideUp);

                var ul = document.createElement("UL");
                for( var i=0; i<=this.options.length-1; i++ ){
                    var li = document.createElement("LI");
                    var a = document.createElement("A");

                    $(a).attr("href", "#"+i)
                        .html(this.options[i].text)
                        .bind("click", show_item)
                        .hover(function(){itemHover=true}, function(){itemHover=false;});

                    $(li).addClass("jquery-combo-list-item")
                         .append(a);

                    $(ul).append(li);

                    if( this.options[i].getAttribute("selected")!=null ) $(div_input).html(this.options[i].value);
                }

                var div_list = document.createElement("DIV");
                $(div_list).addClass("jquery-combo-list")
                           .css("z-index", options.zIndex)
                           .append(ul);

                $(div).append(div_input, a_arrow, div_list);

                $(this).after(div);
                if( this.id!="" ) This.elements[this.id]=div;
            });
	};
	// END FUNCTION  (show_combo)

	var comboSlideDown = function(e){
            var e = e || window.event;
	    var el = e.target || e.srcElement;
            e.preventDefault();
				
            var div = $(el.parentNode).find(".jquery-combo-list");
                div.css("z-index", options.zIndex+40);

            if( div.is(":hidden") ) {
                switch(options.effect){
                case 1:
                    div.stop().slideDown("slow");
                break;
                case 2:
                    div.css("opacity", "0")
                       .stop()
                       .animate({
                            height : "show",
                            opacity : "1"
                        }, 900);
                break;
                }
            }else comboSlideUp(el.parentNode);
	};
	// END FUNCTION  (comboSlideDown)

	var comboSlideUp = function(e){
            if( e.nodeName ) var el = e;
            else{
                if( itemHover ) return;
                var e = e || window.event;
                var el = e.target || e.srcElement;
                e.preventDefault();
                el = el.parentNode;
            }

            var div = $(el).find(".jquery-combo-list");
            switch(options.effect){
            case 1:
                div.stop().slideUp("slow");
            break;
            case 2:
                div.css("opacity", "1")
                   .stop()
                   .animate({
                        height : "hide",
                        opacity : "0"
                    }, 900);
            break;
            }
	};
	// END FUNCTION  (comboSlideUp)
	

	var show_item = function(e){
            var e = e || window.event;
	    var el = e.target || e.srcElement;
		e.preventDefault();
		
            var index = this.toString().replace(/^.*#/, '');
            var parentDiv = el.parentNode.parentNode.parentNode.parentNode;

            $(parentDiv).find(".jquery-combo-input").html($(el).text())
                        .attr("title", $(el).text());

            parentDiv.ElementSelect.selectedIndex = index;

            if( typeof parentDiv.ElementSelect.onchange == "function" ) parentDiv.ElementSelect.onchange();

            comboSlideUp(parentDiv);
	};
	// END FUNCTION  (show_item)
	
}