<?php
session_start();
if (isset($_SESSION["usuario"])) {
$file_name = $_GET["arquivo"];
$description = $_GET["nome"];

$extension = explode(".", $file_name);
$extension = $extension[sizeof($extension) -1];

$download_name = "$description.$extension";

$content_type = mime_content_type($file_name);

header("Content-type: $content_type");
header("Content-Disposition: attachment; filename=$download_name");
readfile($file_name);

echo "<script>window.close();</script>";
} else {
    echo "<script>"
    . "window.location='login.html';"
    . "</script>";
}