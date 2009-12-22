var FormSimple = new (function(options){
		
	this.send = function(){
		return;
		if( !valid() ) return;
		
		Loading.show();
		
		$.ajax({
			type: "POST",
			url: "includes/sendmail.php",
			data: $("#form_contact").serialize(),
			success: function(data, textStatus){
				alert(data);
			}
		});
	}
	
	var valid = function(){
		if( document.form_contact.txtName.value.length==0 ){
			$(".msgerror.name").html('Debe ingresar el Nombre.').slideDown("slow");
			document.form_contact.txtName.focus();
			return false;
		}else $(".msgerror.name").slideUp("slow");
		
		if( document.form_contact.txtEmail.value.length==0 ){
			$(".msgerror.email").html('Debe ingresar el Email').slideDown("slow");
			document.form_contact.txtEmail.focus();
			return false;
		}else $(".msgerror.email").slideUp("slow");
		
        if( /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.form_contact.txtEmail.value)==false ){
			$(".msgerror.email").html('El Email ingresado es incorrecto.').slideDown("slow");
			document.form_contact.txtEmail.focus();
            return false;
        }else $(".msgerror.email").slideUp("slow");
		
		if( document.form_contact.txtConsult.value.length==0 ){
			$(".msgerror.consult").html('Debe ingresar una Consulta.').slideDown("slow");
			document.form_contact.txtConsult.focus();
			return false;
		}else $(".msgerror.consult").slideUp("slow");
		
		return true;
	}
	
	var Loading = {
		show : function(){
			$("#form .container_contact").css("position", "relative");
			$("#form .container_contact").animate({ 
				left : ($("#form").width()+20)+"px", 
				opacity : '0'
			 }, 600);
		},
		hidden : function(){
			
		}
	}
					  
})();



$(document).ready(function(){
		   
	/*$(window).scroll(function () {
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
		
	});*/
	
});
