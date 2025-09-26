<?php
class Util {
    
    function carregarCombo($tabela, $id,$campo) {
    
        //include './conexao.php';
        $sql = "SELECT * FROM $tabela";
        $result = mysql_query($sql);
        $linhas = mysql_num_rows($result);
        $dados = array();
        for($i = 0;$i < $linhas;$i++){
            $dados[$i][0] = mysql_result($result, $i, "$id");
            $dados[$i][1] = mysql_result($result, $i, "$campo");
        }
        return $dados;
        
    }

}
