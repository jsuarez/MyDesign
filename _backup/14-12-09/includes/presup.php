<?php
include("configure.php");
include_once('flourish/init2.php');

function get_result($val){
    return isset($_POST[$val]) ? "Si" : "No";
}

$error=false;

try {	
    $message_html = '<style type="text/css">';
    $message_html.= 'span{color:red}';
    $message_html.= '</style>';
    $message_html.= '<h2>Información de Contacto</h2>';
    $message_html.= '<b>Nombre:&nbsp;</b><span>'. $_POST["txtName"] .'</span><br>';
    $message_html.= '<b>Email:&nbsp;</b><span>'. $_POST["txtEmail"] .'</span><br>';
    $message_html.= '<b>Telefono:&nbsp;</b><span>'. $_POST["txtPhone"] .'</span><br>';
    $message_html.= '<b>Empresa:&nbsp;</b><span>'. $_POST["txtCompany"] .'</span><br>';

    $message_html.= '<h2>Descripción del Proyecto</h2>';
    $message_html.= '<b>¿Cuenta con una imágen gráfica corporativa?:&nbsp;</b><span>'. get_result("chk1") .'</span><br>';
    $message_html.= '<b>Tarea:&nbsp;</b><span>'. $_POST["cboTask"] .'</span><br>';
    $message_html.= '<b>Tipo de sitio web:&nbsp;</b><span>'. $_POST["cboTypeWeb"] .'</span><br>';
    $message_html.= '<b>Prespuestar Diseño de la marca:&nbsp;</b><span>'. get_result("chk2") .'</span><br>';
    $message_html.= '<b>Sitio web actual:&nbsp;</b><span>'. $_POST["txtWebCurrent"] .'</span><br>';
    $message_html.= '<b>Sitio web Corporativa:&nbsp;</b><span>'. $_POST["txtSiteCorporate"] .'</span><br>';

    $message_html.= '<h2>Otras Características</h2>';
    $message_html.= '<b>Formulario de Consulta:&nbsp;</b><span>'. get_result("chk3") .'</span><br>';
    $message_html.= '<b>Música de fondo:&nbsp;</b><span>'. get_result("chk4") .'</span><br>';
    $message_html.= '<b>Video:&nbsp;</b><span>'. get_result("chk5") .'</span><br>';
    $message_html.= '<b>Sistema de carga y actualización de contenidos del sitio:&nbsp;</b><span>'. get_result("chk9") .'</span><br>';
    $message_html.= '<b>Español e Inglés:&nbsp;</b><span>'. get_result("chk10") .'</span><br>';
    $message_html.= '<b>Configuración de un Foro&nbsp;</b><span>'. get_result("chk11") .'</span><br>';
    $message_html.= '<b>Animaciones en flash:&nbsp;</b><span>'. get_result("chk6") .'</span><br>';
    $message_html.= '<b>Catálogo, Base de datos de productos (sin venta on-line):&nbsp;</b><span>'. get_result("chk7") .'</span><br>';
    $message_html.= '<b>Catálogo, Base de datos de productos (con venta on-line y carrito de compras):&nbsp;</b><span>'. get_result("chk8") .'</span><br>';
    $message_html.= '<b>Chat On-Line:&nbsp;</b><span>'. get_result("chk12") .'</span><br>';
    $message_html.= '<b>Otros comentarios:</b><br>'.nl2br($_POST["txtComment"]);


    $message_plaintext = strip_tags($message_html);

    $email = new fEmail();
    $email->addRecipient(EMAIL_PRESP, EMAIL_PRESP_NAME);  // Destinatario
    $email->setFromEmail($_POST["txtEmail"], $_POST["txtName"]);    // Remitente
    $email->setBounceToEmail(EMAIL_PRESP);  // En caso de que rebote llegara a la direccion ingresada.
    $email->setSubject(EMAIL_PRESP_SUBJECT);     // Asunto
    $email->setBody($message_plaintext);	 // Cuerpo del mensaje (texto plano)
    $email->setHTMLBody($message_html);	 // Cuerpo del mensaje (texto html)
    $email->send();


}catch (fValidationException $e) {
    $error = true;
    $error_message = $e->getMessage();
}
?>