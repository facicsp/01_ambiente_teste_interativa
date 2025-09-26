<?php
  
  require_once "./phpmailer/class.phpmailer.php";
  
  try {
    $mail = new PHPMailer();
    
    $mail->IsSMTP();
    $mail->HOST     = "protocolo@interativa1educa1.hospedagemdesites.ws";
    $mail->SMTPAuth = true;
    $mail->Username = "protocolo@interativa1.educacao.ws";
    $mail->Password = "Int3rativa#f@cic2020";
    
    /*$mail = new PHPmailer(); 
    $mail->IsHTML(true); 
    $mail->SetLanguage('br', './phpmailer/language/phpmailer.lang-br.php');
    $mail->IsSMTP();*/
    
    $mail->From = utf8_decode('protocolo@interativa1.educacao.ws');
    $mail->FromName = utf8_decode('Darth Vader');
    
    $mail->addAddress(utf8_decode('guilhermee.andrade@hotmail.com'), utf8_decode('Emperor'));
    
    $mail->Subject = utf8_decode('Force');
    $mail->Body = utf8_decode('There is a great disturbance in the Force.');
    
    if ($mail->send()) 
      echo "enviado...";
    else 
      echo $mail->ErrorInfo;
    
  } catch (\Exception $e) {
    echo $e->errorMessage();
  } catch (\Exception $e) {
    echo $e->getMessage();
  }
  
  
  exit;
  
  //PHPMailer Object
  $mail = new PHPMailer(true); //Argument true in constructor enables exceptions
  
  $mail->SetLanguage('br', './phpmailer/language/phpmailer.lang-br.php');
  
  
  //From email address and name
  $mail->From = utf8_decode("guilhermee.andrade@hotmail.com");
  $mail->FromName = utf8_decode("FACIC INTERATIVA");
  
  //To address and name
  $mail->addAddress("guilhermee.andrade@hotmail.com");
  //Send HTML or Plain Text email
  $mail->isHTML(true);
  
  $mail->Subject = utf8_decode("Subject Text");
  $mail->Body = utf8_decode("<i>Mail body in HTML</i>");
  $mail->AltBody = utf8_decode("This is the plain text version of the email content");
  
  if($mail->send()){
    echo 'Message has been sent';
  }else{
    echo 'Mailer Error: ' . $mail->ErrorInfo;
  }