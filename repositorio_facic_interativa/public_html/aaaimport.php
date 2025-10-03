<?php 

include "conexao.php";


// INSERT INTO turma (turma, idCurso, ativo) VALUES ('', '', 'sim');
// INSERT INTO disciplina (disciplina, cargaHoraria, credito, idProfessor, idTurma, semestre) VALUES (disciplina, 50, 0, 31, idTurma, '2019/2');

// 10 EP        -> Teoria da Decisão		
// 9 EP	        -> Lean Seis Sigma
// 8 EP	        -> Lean Seis Sigma		
// 5,6,7,8,9,10 -> Tópicos Avançados em Engenharia

// 91:  5 EP  ->
// 92:  6 EP  ->
// 93:  7 EP  ->
// 94:  8 EP  ->
// 95:  9 EP  -> 146: Lean Seis Sigma
// 96: 10 EP  -> 145: Teoria da Decisão	/ 147: Tópicos Avançados em Engenharia

$data = read_csv('alunos.csv', ['RA', 'NOME', 'TURMA', 'CIDADE', 'NASCIMENTO', 'EMAIL', 'CELULAR']);

$aux = [];

foreach ($data as $row) {
    $ra     = $row['RA']; 
    $nome   = $row['NOME']; 
    $turma  = $row['TURMA']; 
    $cidade = $row['CIDADE']; 
    $nascimento = implode('-', array_reverse(explode('/', $row['NASCIMENTO']))); 
    $email  = $row['EMAIL']; 
    $celular = $row['CELULAR'];
    $senha = md5($ra);
    $prefixo_turma = explode(' ', $turma)[0];
    $data = date('Y-m-d');
    
    $result = mysql_query("SELECT idUsuario FROM usuario WHERE ra = '$ra'");
    
    if (!in_array($turma, $aux)) {
        echo $turma;
        echo "<br>";
        array_push($aux, $turma);
    }
   
    // if ($prefixo_turma == 8) {
        // $idUsuario = mysql_result($result, 0, "idUsuario");
        // ALUNO ADAPTADO
        // echo ("INSERT INTO listadisciplina VALUES(null, '146', '$idUsuario', 'sim')");
        
        // MATRICULA COMUM
        // echo ("INSERT INTO matricula VALUES(null, '$idUsuario', '96', '$data')");
        
        // echo "<br>";
    // }



    // // CADASTRA OS ALUNOS NÃO CADASTRADOS
    // if (mysql_num_rows($result) == 0) { 
    //     echo ("INSERT INTO usuario (ra, nome, cidade, nascimento, email, celular, tipo, senha) 
    //         VALUES ('$ra', '$nome', '$cidade', '$nascimento', '$email', '$celular', 'aluno', '$senha')");
    //     echo "<br>";
    // } else {
    //     $idUsuario = mysql_result($result, 0, "idUsuario");
    //     echo $idUsuario . "<br>";
    // }
}




// --- DELETA REGISTROS DUPLICADOS ---
// DELETE t1 FROM matricula t1
//         INNER JOIN
//     matricula t2 
// WHERE
//     t1.idMatricula < t2.idMatricula AND t1.idAluno = t2.idAluno AND t1.idTurma = 77 AND t2.idTurma = 77;























function read_csv($file_name, $fields) {
  $file = fopen($file_name, 'r');

  while (($line = fgetcsv($file)) !== false) {
    $line = explode(";", $line[0]);
    $row = [];
    $i = 0;

    foreach ($fields as $field) {
      $row[$fields[$i]] = $line[$i];
      $i++;
    }

    $data[] = $row;
  }

  array_shift($data);

  fclose($file);

  return $data;
}