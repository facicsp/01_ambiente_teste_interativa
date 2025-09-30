<?php
    session_start();

    if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "aluno") {

        include "LoginRestrito/conexao.php";

        $apiKey = "4539110d-faa4-4fb9-8ee3-603f48fd1b69";
        $seguranca = new Seguranca();
        $idAluno = $seguranca->antisql($_SESSION["id"]);

        $sql = "SELECT * FROM api_biblioteca WHERE idAluno='$idAluno'";
        $result = mysqli_query($conexao, $sql);
        $linhas = mysqli_num_rows($result);

        if ($linhas > 0) {
            $nome = mysql_result($result, 0, "nome");
            $sobrenome = mysql_result($result, 0, "sobrenome");
            $email = mysql_result($result, 0, "email");

        $service_url = 'https://integracao.dli.minhabiblioteca.com.br/DigitalLibraryIntegrationService/AuthenticatedUrl';
        $curl = curl_init($service_url);

        $curl_post_data = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
        <CreateAuthenticatedUrlRequest
        xmlns=\"http://dli.zbra.com.br\"
        xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
        xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
        <FirstName>$nome</FirstName>
        <LastName>$sobrenome</LastName>
        <Email>$email</Email>
        <CourseId xsi:nil=\"true\"/>
        <Tag xsi:nil=\"true\"/>
        <Isbn xsi:nil=\"true\"/>
        </CreateAuthenticatedUrlRequest>
        ";


        $content_size = strlen($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/xml; charset=utf-8",
        "Host: integracao.dli.minhabiblioteca.com.br",
        "Content-Length: $content_size",
        "Expect: 100-continue",
        "Accept-Encoding: gzip, deflate",
        "Connection: Keep-Alive",
        "X-DigitalLibraryIntegration-API-Key: $apiKey")
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
        echo curl_error($curl);
        curl_close($curl);
        die();
        }
        curl_close($curl);
        $xml = new SimpleXMLElement($curl_response);
        if ($xml->Success != 'true') {
            echo "<script>alert('Ops! Serviço indisponível no momento.'); location.href='bibliotecas.php';</script>";    
            exit;
        }
        // user code below to redirect browser to the authenticated URL
        //echo header('Location: ' . $xml->AuthenticatedUrl);
        echo "<script>location.href='{$xml->AuthenticatedUrl}';</script>";
        
    } else {
            $email = $idAluno;
            $nomeCompleto = $_SESSION["nome"];
            $nomeCompleto = explode(" ", $nomeCompleto);
            $nome = $nomeCompleto[0];
            $sobrenome = array_reverse($nomeCompleto)[0];

            $service_url = 'https://integracao.dli.minhabiblioteca.com.br/DigitalLibraryIntegrationService/CreatePreRegisterUser';
            $curl = curl_init($service_url);
        
            $curl_post_data = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
            <CreatePreRegisterUserRequest
            xmlns=\"http://dli.zbra.com.br\"
            xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
            xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
            <FirstName>$nome</FirstName>
            <LastName>$sobrenome</LastName>
            <UserName>$email</UserName>
            </CreatePreRegisterUserRequest>
            ";
        
            $content_size = strlen($curl_post_data);
        
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/xml; charset=utf-8",
                "Host: integracao.dli.minhabiblioteca.com.br",
                "Content-Length: $content_size",
                "Expect: 100-continue",
                "Accept-Encoding: gzip, deflate",
                "Connection: Keep-Alive",
                "X-DigitalLibraryIntegration-API-Key: $apiKey")
            );
        
            curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        
            $curl_response = curl_exec($curl);
        
            if ($curl_response === false) {
                echo curl_error($curl);
                curl_close($curl);
                die();
            }
            
        
            curl_close($curl);
            $xml = new SimpleXMLElement($curl_response);

            if ($xml->Success != 'true') {
                echo "<script>alert('Ops! Serviço indisponível no momento.'); location.href='bibliotecas.php';</script>";    
                exit;
            }
            
            mysqli_query($conexao, "INSERT INTO api_biblioteca VALUES (NULL, '$email', '$nome', '$sobrenome', '$email');");
            
            echo "<script>location.href='minhabiblioteca.php';</script>";    
            exit;
        }

    }


