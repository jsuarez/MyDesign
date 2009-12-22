<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Dise&ntilde;o de paginas web en Madrid Espa&ntilde;a | Dise&ntilde;o Web Profesional</title>
    <?php include("includes/head.php");?>
    <script type="text/javascript" src="js/formcontact.min.js"></script>
</head>

<body>
    <div id="container">
        <div id="header">
            <?php include('includes/header.php'); ?>
        </div>
        
        <div id="mainContent">
            <div class="servicios">
                <div class="title_web"><h2 class="grad1">MARKETING ON-LINE</h2></div>
                
                <div id="programs" class="programs"><p>&nbsp;</p></div>
                
                <div class="left_web">
                    <div class="content_web">
                        <div class="content_top">Search Engine Marketing<a href="#" class="button"><img src="images/button_ver_mas.png" alt="Más Info"  onmouseover="this.src='images/button_ver_mas_over.png'" onmouseout="this.src='images/button_ver_mas.png'" onclick="moreinfo('cont1','info_marketingonline.php','1'); return false;" /></a></div>
                        <div class="content_bottom">
                            <div class="image_icon"><a href="#" onclick="return false;"><img src="images/icons/icon_marketing_search.png" class="img" alt="Search Engine Marketing"  /></a></div>
                            <div id="cont1" class="cont_slide">
                                <p>Planificaremos y gestionaremos su campa&ntilde;a de publicidad en buscadores mediante el sistema de pago por clic.</p>
                                <p>Podr&aacute; aparecer a la derecha y en recuadro superior a la lista de resultados naturales. Significa estar en las primeras p&aacute;ginas de los buscadores en el listado preferencial de enlaces patrocinados...</p>
                            </div>
                        </div>
                        <!--end .content_bottom-->
                    </div>
                    <!--end .content_web-->
    
                    <div class="content_web">
                        <div class="content_top">E-mail Marketing<a href="#" class="button"><img src="images/button_ver_mas.png" alt="Más Info"  align="right" onmouseover="this.src='images/button_ver_mas_over.png'" onmouseout="this.src='images/button_ver_mas.png'" onclick="moreinfo('cont2','info_marketingonline.php','2'); return false;" /></a></div>
                        <div class="content_bottom">
                            <div class="image_icon"><a href="#" onclick="return false;"><img src="images/icons/icon_marketing_email.png" class="img" alt="E-Mail Marketing"  /></a></div>
                            <div id="cont2" class="cont_slide">
                                <p>Es una poderosa herramienta de marketing directo que permite la comunicaci&oacute;n con sus clientes actuales o potenciales mediante el envío de e-mails. Adem&aacute;s le brinda la posibilidad de determinar y elegir el target de personas ya sea por país, idioma, etc...</p>
                           </div>
                        </div>
                    </div>
                    <!--end .content_web-->

                    <div class="content_web">
                        <div class="content_top">Marketing Viral<a href="#" class="button"><img src="images/button_ver_mas.png" alt="Más Info"  onmouseover="this.src='images/button_ver_mas_over.png'" onmouseout="this.src='images/button_ver_mas.png'" onclick="moreinfo('cont3','info_marketingonline.php','3'); return false;" /></a></div>
                        <div class="content_bottom">
                            <div class="image_icon"><a href="#" onclick="return false;"><img src="images/icons/icon_marketing_viral.png" class="img" alt="Marketing Viral"  /></a></div>
                            <div id="cont3" class="cont_slide">
                                <p>Mediante una campa&ntilde;a de marketing viral se busca que la misma llegue a la mayor cantidad de usuarios posibles. Se lo podría asemejar a un servicio de boca a boca virtual...</p>
                            </div>
                        </div>
                    </div>
                    <!--end .content_web-->
                </div>
                <!--end .left_web-->
                
                <div id="form" class="right_contact">
                   <?php include('includes/form_contact.php'); ?>
                </div>
            </div>
            <!--end .servicios-->
            <br class="clearfloat" />
            <p></p>
        </div>
        <!-- end #mainContent -->
    
        <div id="footer">
        <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <!-- end #container -->
</body>
</html>
