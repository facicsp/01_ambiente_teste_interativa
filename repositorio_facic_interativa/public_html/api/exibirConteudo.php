<?php

include 'hasAccess.php';
include 'util/mime_content_type.php';

$seguranca = new Seguranca();
$idAluno = $seguranca->antisql($_REQUEST["id"]);
$idAula = $seguranca->antisql($_REQUEST["idAula"]);

$sql = "SELECT 
            DATEDIFF(NOW(), dataAtividade) AS intervalo,
            aula.*, 
            DATE_FORMAT(dataAula,'%d/%m/%Y') AS dataAula2,
            DATE_FORMAT(dataAtividade,'%d/%m/%Y') AS dataAtividade2 
        FROM aula 
        WHERE idAula = '$idAula' 
        ORDER BY dataAula";

$result = mysql_query($sql);
$linhas = mysql_num_rows($result);

if ($linhas > 0) {
    $data = mysql_fetch_assoc($result);
    
    //$data["dataAtividade"] = date('Y-m-d', strtotime("+1 day", strtotime($data["dataAtividade"])));
    
    $data["material"] = [];

    $resultConteudo = mysql_query("SELECT idConteudo, titulo, arquivo FROM conteudo WHERE idAula = '$idAula'");
    $linhaConteudo = mysql_num_rows($resultConteudo);

    if($linhaConteudo > 0) {
        while($row = mysql_fetch_assoc($resultConteudo)) {
            $row["type"] = getMimeType($row["arquivo"]);
            $data["material"][] = $row;
        }
    }

    echo json_encode($data);

} else echo json_encode(false);

exit;