<?php
if( $_SERVER['REQUEST_METHOD']=="POST" && $_POST["action"]=="sendpresp" ){
	include("includes/presup.php");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Dise&ntilde;o de paginas web en Madrid Espa&ntilde;a | Dise&ntilde;o Web Profesional</title>
    <?php include("includes/head.php");?>    
    
    <!--======= SCRIPT "COMBOBOX" =======-->
    <link href="js/jquery.combobox/css/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/jquery.combobox/js/jquery.combobox.min.js"></script>
    <script type="text/javascript" src="js/jquery.combobox/js/execute.js"></script>
    <!--======= END SCRIPT =======-->

    <script type="text/javascript" src="js/presup.min.js"></script>

</head>

<body>
    <div id="container">
        <div id="header">
            <?php include('includes/header.php'); ?>
        </div>
        <div id="mainContent">
            <div class="servicios">
                <div class="title_web">
                    <h2 class="grad1">PRESUPUESTO ON-LINE</h2>
                </div>
                
                <div class="container_presupuesto">
                <?php
                    if( !isset($error) ){?>
                	<form action="contacto.php" method="post" id="formPresup" name="formPresup" onsubmit="return Presup.send();" enctype="application/x-www-form-urlencoded">
                            <div class="formularios linea">
                                <div class="form_left">
                                    <h3>Informaci&oacute;n de Contacto</h3>
                                    <div class="row">
                                        <span>* Nombre:</span>
                                        <div class="input"><input type="text" name="txtName" /></div>
                                        <div class="msgerror name right"></div>
                                    </div>
                                    <div class="row">
                                        <span>* E-mail:</span>
                                        <div class="input"><input type="text" name="txtEmail" /></div>
                                        <div class="msgerror email right"></div>
                                    </div>
                                </div>
                                <div class="form_right">
                                    <h3>&nbsp;</h3>
                                    <div class="row">
                                        <span>Tel&eacute;fono:</span>
                                        <div class="input"><input type="text" name="txtPhone" /></div>
                                    </div>
                                    <div class="row">
                                        <span>Empresa:</span>
                                        <div class="input"><input type="text" name="txtCompany" /></div>
                                    </div>
                                </div>
                            </div>
                            <!-- end .formularios -->

                            <div class="formularios linea">
                                <div class="form_center">
                                    <h3>Descripci&oacute;n del Proyecto</h3><br />
                                <div class="row_option">
                                    <div class="box2 option"><input type="radio" name="option" value="dweb" checked="checked" /></div>
                                    <span>Dise&ntilde;o Web.</span>
                                    <div class="box2 option"><input type="radio" name="option" value="dgrafico" /></div>
                                    <span>Dise&ntilde;o Grafico.</span>
                                    <div class="box2 option"><input type="radio" name="option" value="marketing" /></div>
                                    <span>Marketing On-Line.</span>
                                    <br  />
                                    </div>
                                </div>

                                <div class="form_left">
                                    <div class="row">
                                        <span class="t1">¿Cuenta con una im&aacute;gen gr&aacute;fica corporativa?</span>
                                        <div class="box"><input type="checkbox" name="chk1" /></div>
                                        <span class="t2">(logo, dise&ntilde;o de la marca)</span>
                                    </div>
                                    <div class="row">
                                        <span>Tarea:</span>
                                        <select name="cboTask" id="cboTask" onchange="Presup.change_task(this);">
                                          <option value="Creaci&oacute;n de un nuevo sitio">Creaci&oacute;n de un nuevo sitio</option>
                                          <option value="Redise&ntilde;o de una web existente">Redise&ntilde;o de una web existente</option>
                                        </select>
                                    </div>
                                    <div class="row hidden-div"><span>Tipo de sitio web</span>
                                        <select name="cboTypeWeb" id="cboTypeWeb">
                                            <option value="Web Corporativa">Web Corporativa</option>
                                            <option value="Web Autoadministrable">Web Autoadministrable</option>
                                            <option value="Cat&aacute;logo de Productos">Cat&aacute;logo de Productos</option>
                                            <option value="Carrito de Compras">Carrito de Compras</option>
                                            <option value="Sistema Inmobiliario">Sistema Inmobiliario</option>
                                            <option value="Sistema Tur&iacute;stico">Sistema Tur&iacute;stico</option>
                                        </select>
                                    </div>
                                    <div class="row hidden-div3">
                                        <span>Presupuestar Dise&ntilde;o Newsletter</span>
                                        <div class="box"><input type="checkbox" name="chk13" /></div>
                                    </div>
                                    <div class="row hidden-div3">
                                        <span>Presupuestar Base de Datos de mails</span>
                                        <div class="box"><input type="checkbox" name="chk14" /></div>
                                    </div>
                                 </div>

                                <div class="form_right">                                    
                                    <div class="row">
                                        <span class="t1">Prespuestar Dise&ntilde;o de la marca</span>
                                        <div class="box"><input type="checkbox" name="chk2" /></div>
                                        <span class="t2">(logotipo, isotipo, isologotipo)</span>
                                    </div>
                                    <div class="row hidden-div">
                                        <span>Sitio web actual</span>
                                        <div class="input"><input type="text" name="txtWebCurrent" /></div>
                                    </div>
                                    <div class="row hidden-div">
                                        <span>Sitio web Comparativo</span>
                                        <div class="input"><input type="text" name="txtSiteCorporate" /></div>
                                        <span class="t2">Alg&uacute;n sitio que refleje en parte las funcionalidades de su propio desarrollo, no necesariamente su dise&ntilde;o est&eacute;tico.</span>
                                    </div>
                                    <div class="row hidden-div2">
                                        <span>Poblaci&oacute;n Objetivos</span>
                                        <select name="cboPoblacion" id="cboPoblacion">
                                            <option value="Espa&ntilde;a">Espa&ntilde;a</option>
                                            <option value="Paises Habla Hispana">Paises Habla Hispana</option>
                                            <option value="Todos">Todos</option>
                                            <option value="otros">otros</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <!-- end .formularios -->

                            <div class="formularios">
                                <div class="form_left">
                                    <h3>Otras Caracter&iacute;sticas</h3>
                                    <div class="row hidden-div">
                                        <div class="box2"><input type="checkbox" name="chk3" /></div>
                                        <span>Formulario de Consulta</span>
                                    </div>
                                    <div class="row hidden-div">
                                        <div class="box2"><input type="checkbox" name="chk4" /></div>
                                        <span>M&uacute;sica de fondo</span>
                                    </div>
                                    <div class="row hidden-div">
                                        <div class="box2"><input type="checkbox" name="chk5" /></div>
                                        <span>Video</span><span class="t2">(hasta 5 clips)</span>
                                    </div>
                                </div>
                                <div class="form_right hidden-div">
                                    <h3>&nbsp;</h3>
                                    <div class="row">
                                        <div class="box2"><input type="checkbox" name="chk6" /></div>
                                        <span>Animaciones en flash</span>
                                    </div>
                                    <div class="row">
                                        <div class="box2"><input type="checkbox" name="chk7" /></div>
                                        <span>Cat&aacute;logo, Base de datos de productos.</span><span class="t2">sin venta on-line</span>
                                    </div>
                                    <div class="row">
                                        <div class="box2"><input type="checkbox" name="chk8" /></div>
                                        <span>Adaptacion sistema de pagos on-line.</span><span class="t2">con venta on-line y carrito de compras</span>
                                    </div>
                                </div>
                                <div class="form_center hidden-div">
                                    <div class="box2"><input type="checkbox" name="chk9" /></div>
                                    <span>Sistema de carga y actualizaci&oacute;n de contenidos del sitio.</span><br />
                                    <p><span class="t2">Este sistema le permite actualizar peri&oacute;dicamente a Ud. los contenidos de su sitio, textos, im&aacute;genes, etc. <br />(Tenga en cuenta que este sistema tiene un costo importante en el desarrollo del sitio y no siempre es necesario en funci&oacute;n del proyecto a desarrollar)</span></p>&nbsp;<br  />
                                    <div class="box2"><input type="checkbox" name="chk10" /></div>
                                    <span>Espa&ntilde;ol e Ingl&eacute;s</span><br />
                                    <p><span class="t2">Traducci&oacute;n a cargo del cliente o presupuesto por separado</span></p>
                                </div>
                                <div class="form_left hidden-div">
                                    <div class="row">
                                        <div class="box2"><input type="checkbox" name="chk11" /></div>
                                        <span>Configuraci&oacute;n de un Foro</span>
                                    </div>
                                </div>
                                <div class="form_right hidden-div">
                                    <div class="row">
                                        <div class="box2"><input type="checkbox" name="chk12" /></div>
                                        <span>Chat On-Line</span>
                                    </div>
                                </div>
                                <div class="form_center">
                                    <span>Comentarios:</span>
                                    <div class="text_area"><textarea name="txtComment" cols="50" rows="5"></textarea></div>

                                    <div class="button_presupuesto">
                                        <input type="submit" class="send" value="Enviar Solicitud" />
                                        <input type="button" class="delet" value="Borrar Informaci&oacute;n" onclick="Presup.Reset()" />
                                    </div>
                                </div>
                          </div>
                           <!-- end .formularios -->

                            <input type="hidden" name="action" value="sendpresp" />
                            <input type="hidden" name="typeproyect" value="Diseño Web" />
		        </form>
                <?php }elseif( !$error ){?>
                	
                        <div class="message">
                            <p>Muchas gracias por comunicarse,</p>
                            <p>En breve estaremos en contacto.</p>
                        </div>
                    
                <?php }elseif( $error ){?>
                	
                        <div class="message">
                            <p>Formulario no enviado.</p>
                            <p>Ha ocurrido un error durante el envio del formulario.</p>
                            <p>Mensaje de Error: <br /><?php echo $error_message;?></p>
                        </div>
                <?php }?>
                </div>
                <!-- end .container_presupuesto-->
            </div>
            <!-- end .servicios -->
            <br class="clearfloat" />
        </div>
        <!-- end #mainContent -->
        
        <div id="footer">
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <!--end #container-->
</body>
</html>
