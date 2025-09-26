<?php

date_default_timezone_set('America/Sao_Paulo');

require "JWT.php";

$time = time();

$payload = [
    "external_id"   => $_GET["cod"],
    "root_url"      => "https://fascinterativa.com/",
    "expiration_at" => date("Y-m-d H:i:s", $time + 60 * 60 * 4),
    "iat"           => $time,
    "library_code"  => "FACIC_03122021_novabds"
];

$jwt = new JWT();
$token = $jwt->encode($payload, "hnmO95G9QpAwm6oesUCPhiKbUwFClZiqUo0HeOBBzig=");
$client_name = "FACIC";

$url = "https://bibliotecadigital.saraivaeducacao.com.br/jwt?client_name=$client_name&token=$token";

header("Location: $url");