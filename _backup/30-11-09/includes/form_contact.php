
<div class="container_contact">
	<h3>Solicitar Informaci&oacute;n</h3>

    <form id="form_contact" name="form_contact" action="" enctype="application/x-www-form-urlencoded">
        <span>*Nombre:</span> <div class="input"><input type="text" name="txtName" /></div>
        <div class="msgerror name"></div>
        
        <span>Tel&eacute;fono:</span> <div class="input"><input type="text" name="txtPhone" /></div>
        
        <span>*E-mail:</span> <div class="input"><input type="text" name="txtEmail" /></div>
        <div class="msgerror email"></div>
        
        <span>*Consulta</span> <div class="textarea"><textarea cols="20" rows="10" name="txtConsult"></textarea></div>
        <div class="msgerror consult"></div>
    </form>
    
    <a href="#" class="submit" onclick="javascript:FormSimple.send(); return false;"><img src="images/button_enviar.png" /></a>
</div>
