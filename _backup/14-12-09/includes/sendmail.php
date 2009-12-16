<?php
include("../configure.php");
include_once('../flourish/init.php');

try {

    /* Envia email
    -------------------------------------------------------------- */
    $message_html = "<b>Nombre:</b>&nbsp;". $_POST["txtName"] ."<br />";
    $message_html.= "<b>Email:</b>&nbsp;". $_POST["txtEmail"] ."<br />";
    $message_html.= "<b>Telefono:</b>&nbsp;". $_POST["txtPhone"] ."<br />";
    $message_html.= "<b>Consulta:</b>";
    $message_html.= '<hr color="#093D6C">';
    $message_html.= nl2br($_POST["txtConsult"]);

    $message_plaintext = "Nombre: ". $_POST["txtName"] ."\n";
    $message_plaintext.= "Email: ". $_POST["txtEmail"] ."\n";
    $message_plaintext.= "Telefono: ". $_POST["txtPhone"] ."\n";
    $message_plaintext.= "Consulta:\n\n";
    $message_plaintext.= $_POST["txtConsult"];

    $email = new fEmail();
    $email->addRecipient(EMAIL_CONTACT, EMAIL_CONTACT_NAME);  // Destinatario
    $email->setFromEmail($_POST["txtEmail"], $_POST["txtName"]);    // Remitente
    $email->setBounceToEmail(EMAIL_CONTACT);  // En caso de que rebote llegara a la direccion ingresada.
    $email->setSubject(EMAIL_CONTACT_SUBJECT);     // Asunto
    $email->setBody($message_plaintext);	 // Cuerpo del mensaje (texto plano)
    $email->setHTMLBody($message_html);	 // Cuerpo del mensaje (texto html)
    $email->send();


}catch (fValidationException $e) {
    echo $e->getMessage();
    die();
}

die("ok");
?>