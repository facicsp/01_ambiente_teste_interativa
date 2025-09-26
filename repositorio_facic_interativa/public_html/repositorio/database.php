<?php
require 'config.php';
require 'connection.php';

// DELETAR REGISTROS
function DBDelete($table, $where = null) {
    $where = ($where) ? " WHERE {$where}" : null;
    $query = "DELETE FROM {$table}{$where}";
    return DBExecute($query);
}

// ALTERAR REGISTRO
function DBUpDate($table, array $data, $where = null) {
    $data = DBEscape($data);
    foreach ($data as $key => $value) {
        $fields[] = "{$key} = '{$value}'";
    }
    $fields = implode(', ', $fields);
    $where = ($where) ? " WHERE {$where}" : null;
    $query = "UPDATE {$table} SET {$fields}{$where}";
    return DBExecute($query);
}

// LER REGISTROS
function DBRead($table, $params = null, $fields = '*') {
    $params = ($params) ? " {$params}" : null;
    $query = "SELECT {$fields} FROM {$table}{$params}";
    $result = DBExecute($query);
    if (!mysqli_num_rows($result)) {
        return false;
    } else {
        while ($res = mysqli_fetch_assoc($result)) {
            $data[] = $res;
        }
        return $data;
    }
}

// GRAVAR REGISTROS
function DBCreate($table, array $data, $insertId = false) {
    $data = DBEscape($data);
    $fields = implode(', ', array_keys($data));
    $values = "'" . implode("', '", $data) . "'";
    $query = "INSERT INTO {$table} ( {$fields} ) VALUES ( {$values} )";
    return DBExecute($query, $insertId);
}

// EXECUTA QUERY
function DBExecute($query, $insertId = false) {
    $link = DBConnect();
    $result = @mysqli_query($link, $query) or die(mysqli_error($link));
    if ($insertId) {
        $result = mysqli_insert_id($link);
    }
    DBClose($link);
    return $result;
}

// SUBSTITUE CARACTER ESPECIAL
function caracterEspecial($str) {
    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
    // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
    $str = preg_replace('/[^a-z0-9]/i', '_', $str);
    $str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
    return strtolower($str);
}

// VERIFICA SE USUARIO PODE ACESSAR PAGINA REQUISITADA
function acesso($usuario, $tela) { 
    $verifica = DBRead("acesso", "WHERE idusuario = '$usuario'");
    if ($verifica) { 
        $ids = $verifica[0]["idtela"];
        $ids = explode(",", $ids);
        $count = 0;
        for ($i=0; $i < sizeof($ids); $i++) {
            echo "<script>$('#link".trim($ids[$i])."').removeClass('btn disabled');</script>";
            if (trim($ids[$i]) == 17) {
              echo "<script>$('#link17_2').removeClass('btn disabled');</script>";
            }
            if ($tela == $ids[$i]) {
                $count++;
            }
        }
    }
    if ($count > 0) {
        return true;
    } else {
        echo "<script>document.location.href='index.php';</script>";
    } 
}
?> 