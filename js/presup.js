var Presup = new (function(){
	
    this.send = function(){
        if( !valid() ) return false;
        document.formPresup.submit();
        return false;
    };

    this.Reset = function(){
        $(".msgerror").slideUp("slow");
        document.formPresup.reset();
    };
    
    this.change_task = function(el){
        if( el.selctedIndex==0 )
            $("div.hidden-div3").css("display", "block");
        else
            $("div.hidden-div3").css("display", "none");
    };

    var valid = function(){
        if( !$(document.formPresup.txtName).triggerHandler("blur") ) return false;
        if( !$(document.formPresup.txtEmail).triggerHandler("blur") ) return false;
        return true;
    };
	
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

            $(".box2.option").each(function(){
                $(this).find("input").click(function(){
                    switch( this.value ){
                        case "dweb":
                            $("#cboTask").empty()
                                         .append('<option value="Creaci&oacute;n de un nuevo sitio">Creaci&oacute;n de un nuevo sitio</option>')
                                         .append('<option value="Redise&ntilde;o de una web existente">Redise&ntilde;o de una web existente</option>');
                            combobox.update("#cboTask");
                            $("div.hidden-div").css("display", "block");
                            $("div.hidden-div2, div.hidden-div3").css("display", "none");
                        break;

                        case "dgrafico":
                            $("#cboTask").empty()
                                         .append('<option value="Identidad Corporativa">Identidad Corporativa</option>')
                                         .append('<option value="Dise&ntilde;o Publicitario">Dise&ntilde;o Publicitario</option>')
                                         .append('<option value="Dise&ntilde;o de Packaging">Dise&ntilde;o de Packaging</option>')
                                         .append('<option value="Dise&ntilde;o de Newsletters">Dise&ntilde;o de Newsletters</option>')
                                         .append('<option value="Dise&ntilde;o Editorial">Dise&ntilde;o Editorial</option>');
                            combobox.update("#cboTask");
                            $("div.hidden-div, div.hidden-div2, div.hidden-div3").css("display", "none");
                        break;

                        case "marketing":
                            $("#cboTask").empty()
                                         .append('<option value="E-Mail Marketing">E-Mail Marketing</option>')
                                         .append('<option value="Search Engine Marketing">Search Engine Marketing</option>')
                                         .append('<option value="Marketing Viral">Marketing Viral</option>');
                            combobox.update("#cboTask");
                            $("div.hidden-div").css("display", "none");
                            $("div.hidden-div2, div.hidden-div3").css("display", "block");
                        break;
                    }

                })

            });
        }
    });
						   
})();