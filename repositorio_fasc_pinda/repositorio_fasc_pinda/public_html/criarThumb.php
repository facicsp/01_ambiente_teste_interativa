<?php

// recebendo a url da imagem
include 'LoginRestrito/conexao.php';
$seguranca = new Seguranca();
$filename = $seguranca->antisql($_GET['img']);
$ext = $seguranca->antisql($_GET['ext']);
$percent = 0.10;

// Cabeçalho que ira definir a saida da pagina
//header('Content-type: image/jpeg');
// pegando as dimensoes reais da imagem, largura e altura
list($width, $height) = getimagesize($filename);

//setando a largura da miniatura
$new_width = 120;
//setando a altura da miniatura
$new_height = 100;

//gerando a a miniatura da imagem
$image_p = imagecreatetruecolor($new_width, $new_height);
if ($ext == 'jpg') {
    $image = imagecreatefromjpeg($filename);
} else if ($ext == 'gif') {
    $image = imagecreatefromgif($filename);
} else if ($ext == 'png') {

    $image = imagecreatefrompng($filename);
}
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

//o 3º argumento é a qualidade da imagem de 0 a 100
$nome = '' . $filename . '.jpg';
imagejpeg($image_p, $nome, 50);
imagedestroy($image_p);
echo "<script>
window.location='uploadfoto.php';
</script>";
?>