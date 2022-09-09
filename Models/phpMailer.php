<?php 
 
// Import PHPMailer classes into the global namespace 

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 

require ($_SERVER['DOCUMENT_ROOT'].'/PHPMailer/Exception.php');
require ($_SERVER['DOCUMENT_ROOT'].'/PHPMailer/PHPMailer.php');
require ($_SERVER['DOCUMENT_ROOT'].'/PHPMailer/SMTP.php');
 
$mail = new PHPMailer; 
 
$mail->isSMTP();                      // Set mailer to use SMTP 
$mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                    )
                );
$mail->SMTPDebug = 2;
$mail->Host = 'ssl://smtps.aruba.it';       // Specify main and backup SMTP servers 
$mail->SMTPAuth = true;               // Enable SMTP authentication 
$mail->Username = 'ufficiotecnico@taurosistemi.it';   // SMTP username 
$mail->Password = 'Tauro@2017';   // SMTP password 
$mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
$mail->Port = 465;                    // TCP port to connect to 
 
// Sender info 
$mail->setFrom('ufficiotecnico@taurosistemi.it', 'TauroSistemi'); 
$mail->addReplyTo('reply@taurosistemi.it', 'TauroSistemi'); 
 
// Add a recipient 
$mail->addAddress('simo.ostuni@gmail.com'); 
 
//$mail->addCC('cc@example.com'); 
//$mail->addBCC('bcc@example.com'); 
 
// Set email format to HTML 
$mail->isHTML(true); 
 
// Mail subject 
$mail->Subject = 'Intervento assegnato'; 
 
// Mail body content 
$bodyContent = '<h1>Ti è stato assegnato un intervento!</h1>'; 
$mail->Body    = $bodyContent;
 
// Send email 
if(!$mail->send()) { 
    echo 'Messaggio non inviato, errore: '.$mail->ErrorInfo; 
} else { 
    echo 'Messaggio inviato.'; 
} 
 
?>
