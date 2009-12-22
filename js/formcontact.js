var FormSimple = new (function(){
		
    this.send = function(){
        if( !valid() ) return false;

        Loading.show('<img src="images/ajax-loader.gif" alt="" />'+
                     '<p>Enviando formulario.</p>',
         function(){
            $.ajax({
                type: "POST",
                url: "includes/sendmail.php",
                data: $("#form_contact").serialize(),
                success: function(data){
                    if( data=="ok" ){
                        Loading.change_message('<img src="images/ok.png" alt="" />'+
                                               '<p>Gracias por su mensaje.<br>'+
                                               'Atenderemos su requerimiento a la brevedad.</p>');
                    }else{
                        Loading.change_message('<img src="images/error_icon.png" alt="" />'+
                                               '<p>Mensaje no enviado.<br>'+
                                               'Ha ocurrido un error durante el envio del mensaje.</p>');

                    }
                },
                error: function(xml){
                    alert("Error Ajax\n"+xml.responseHtml);
                    Loading.hidden();
                }
            });
         });

        return false;
    };

    var valid = function(){
        if( !$(document.form_contact.txtName).triggerHandler("blur") ) return false;
        if( !$(document.form_contact.txtEmail).triggerHandler("blur") ) return false;
        if( !$(document.form_contact.txtConsult).triggerHandler("blur") ) return false;
        return true;
    };
	
    var Loading = {
        show : function(msg, callback){
            $("#form .container_contact").animate({
                left : ($("#form").width()+20)+"px",
                opacity : '0'
            }, 600);

            var div = $("#form .formcontact_loading");
                div.html(msg);

            setTimeout(function(){
                div.animate({
                    left : '0',
                    opacity:'1'
                }, 600, callback);

            }, 200);

        },
        change_message : function(msg){
            $("#form .formcontact_loading").html(msg);
            setTimeout(this.hidden, 5000);
        },
        hidden : function(){
            $("#form .container_contact").css("left", "0")
                                         .animate({opacity: '1'}, 800);

            $("#form .formcontact_loading").animate({opacity: '0'}, 400)
                                           .css("left", "-225px");

            document.form_contact.txtName.value="";
            document.form_contact.txtPhone.value="";
            document.form_contact.txtEmail.value="";
            document.form_contact.txtConsult.value="";
            document.form_contact.txtName.focus();
        }
    };


    $(document).ready(function(){

        $(window).scroll(function (e) {
            e.preventDefault();
            var win = $(this);
            var form = $("#form");
            var divInit = $("#programs");
            var posTop = (win.scrollTop() - form.height() - parseInt(form.css("paddingTop")));

            if( form.length>0 && divInit.length>0 ){
                if( win.scrollTop() >= divInit.position().top ){
                    form.stop().animate({ top : (posTop-17)+"px" }, 300);
                }else{
                    form.stop().animate({ top : "0" }, 300);
                }
            }
        });

        $(document.form_contact.txtName).blur(function(e){
            e.preventDefault();
            if( this.value.length==0 ){
                $(".msgerror.name").html('Debe ingresar el Nombre.').slideDown("slow");
                this.focus();
                return false;
            }else {
                $(".msgerror.name").slideUp("slow");
                return true;
            }
        });
        $(document.form_contact.txtEmail).blur(function(e){
            e.preventDefault();
            if( this.value.length==0 ){
                $(".msgerror.email").html('Debe ingresar el Email').slideDown("slow");
                document.form_contact.txtEmail.focus();
                return false;
            }else {
                if( /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.value)==false ){
                    $(".msgerror.email").html('El Email ingresado es incorrecto.').slideDown("slow");
                    document.form_contact.txtEmail.focus();
                    return false;
                }
                $(".msgerror.email").slideUp("slow");
            }
            return true;
        });
        $(document.form_contact.txtConsult).blur(function(e){
            e.preventDefault();
            if( this.value.length==0 ){
                $(".msgerror.consult").html('Debe ingresar una Consulta.').slideDown("slow");
                document.form_contact.txtConsult.focus();
                return false;
            }else {
                $(".msgerror.consult").slideUp("slow");
                return true;
            }
        });

    });

					  
})();

