<?php
  

//   include "conexao.php";
  
// $simbolos = array("&divide;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&Acirc;", "&Ecirc;", "&Ocirc;", "&acirc;", "&ecirc;", "&ocirc;", "&Agrave;", "&agrave;", "&Uuml;", "&uuml;", "&Ccedil;", "&ccedil;", "&Atilde;", "&Otilde;", "&atilde;", "&otilde;", "&Ntilde;", "&ntilde;", "&amp;", "&quot;", "<", "&gt;", "&bdquo;", "&rdquo;", "&ldquo;", "&ndash;", "&ordm;", "&nbsp;", "&bull;", "&yacute;", "&Yacute;", "&Eacute;", "&eacute;", "&Uacute;", "&uacute;", "&deg;", "&sect;");
// $letras =  array("÷", "Á", "É", "Í", "Ó", "Ú", "á", "é", "í", "ó", "ú", "Â", "Ê", "Ô", "â", "ê", "ô", "À", "à", "Ü", "ü", "Ç", "ç", "Ã", "Õ", "ã", "õ", "Ñ", "ñ", "&", "“", "<", ">", "\\\"", "\\\"", "\\\"", "–", "º", " ", "•", "ý", "Ý", "É", "é", "Ú", "ú", "°", "§");

// $result = mysql_query("SELECT idlistaresposta, resposta FROM lista_resposta
// WHERE resposta LIKE '%&%' ORDER BY idlistaresposta DESC LIMIT 20000;");
// $linhas = mysql_num_rows($result);

// echo $linhas;

// for ($i=0; $i < $linhas; $i++) {
//   $id = mysql_result($result, $i, "idlistaresposta");
//   $resposta = mysql_result($result, $i, "resposta");

//   $resposta = str_replace($simbolos, $letras, $resposta, $count);

//   mysql_query("UPDATE lista_resposta SET resposta='$resposta' WHERE idlistaresposta=$id LIMIT 1");
// }