
var Presup = new (function(){
	
	this.send = function(){
		if( !valid() ) return false;
				
		document.formPresup.submit();
					
		return false;
	}
	
	this.Reset = function(){
		$(".msgerror").slideUp("slow");
		document.formPresup.reset();		
	}
	
	var valid = function(){
		if( !$(document.formPresup.txtName).triggerHandler("blur") ) return false;
		if( !$(document.formPresup.txtEmail).triggerHandler("blur") ) return false;
		return true;		
	}
	
	$(document).ready(function(){
		if( document.formPresup ){				   
			$(document.formPresup.txtName).blur(function(){
				if( this.value.length==0 ){
					$(".msgerror.name").html('Debe ingresar el Nombre.').slideDown("slow");
					this.focus();
					return false;
				}else {
					$(".msgerror.name").slideUp("slow");
					return true;
				}
			})
			$(document.formPresup.txtEmail).blur(function(){
				if( this.value.length==0 ){
					$(".msgerror.email").html('Debe ingresar el Email').slideDown("slow");
					document.formPresup.txtEmail.focus();
					return false;
				}else {
					if( /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.value)==false ){
						$(".msgerror.email").html('El Email ingresado es incorrecto.').slideDown("slow");
						document.formPresup.txtEmail.focus();
						return false;
					}
					$(".msgerror.email").slideUp("slow");
				}
				return true;
			})
		}
    });
						   
})();
