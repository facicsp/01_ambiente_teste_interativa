<?php
  
require 'PHPMailerAutoload.php';
require 'class.phpmailer.php';
 



function enviarEmail($destinatario, $assunto, $mensagem) {
    $indice = rand(0, 3);
    $emails = [ "protocolo2", "protocolo3", "protocolo4", "protocolo5" ];
    $emailDaVez = $emails[$indice];
    
    $email        = "$emailDaVez@facicinterativa.com.br";
    $host         = "email-ssl.com.br";
    $usuario      = "$emailDaVez@facicinterativa.com.br";
    $senha        = "Int3rativa#f@cic2020";
    $criptografia = "ssl";
    $porta        = "465";

$arquivo = array();
  
  $mailer = new PHPMailer;

$mailer->isSMTP();                                      // Set mailer to use SMTP

$mailer->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);




$mailer->Host = $host;  // Servidor que realiza o envio
$mailer->SMTPAuth = true; // Enable SMTP authentication
$mailer->isHTML(true);  // Set email format to HTML
$mailer->CharSet = 'utf-8';
$mailer->Port = "$porta";   // Porta de Envio
if(isset($criptografia)){

    $mailer->SMTPSecure = "$criptografia";

}

$mailer->Username = "$usuario";  // SMTP username
$mailer->Password = "$senha";  // SMTP password

$mailer->SMTPDebug = 0;
$mailer->Debugoutput = 'html';
$mailer->setLanguage('pt');

//setando formato HTML da mensagem, para envio ser aceito corretamente.

$corpoMSG = "<!DOCTYPE html>";
$corpoMSG .= "<html>";
$corpoMSG .= "<head>";
$corpoMSG .= "</head>";
$corpoMSG .= "<body>";
$corpoMSG .= $mensagem;
$corpoMSG .= "</body>";
$corpoMSG .= "</html>";


$mailer->AddAddress($destinatario);
$mailer->From = $email;  // E-mail que está enviando
$mailer->FromName = $email; //Nome que será exibido
$mailer->Sender = $email;  // E-mail que está enviando
$mailer->Subject = $assunto; // assunto da mensagem
$mailer->MsgHTML($corpoMSG); // corpo da mensagem
//if ($arquivo['error'] == 0){
//$mailer->AddAttachment($arquivo['tmp_name'], $arquivo['name']);
//}



  if(!$mailer->Send()) {
   return "Houve um erro ao enviar a confirmação de envio da prova para o email: $destinatario<br>Erro: " . $mailer->ErrorInfo;
  } else {
   return "A confirmação de envio de prova foi enviada para o email: $destinatario";
  }
  
}

  

?>      
