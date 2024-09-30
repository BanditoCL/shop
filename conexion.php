<?php

$host = 'localhost';
$user = 'root';
$pass = ''; 
$bd   = 'ecommerce';

$conectar = new mysqli($host, $user, $pass, $bd);

if ($conectar->connect_error) {
    die("Fallo la conexion: " . $conectar->connect_error);
}
if (!$conectar->set_charset("utf8")) {
    die("Error al establecer el conjunto de caracteres: " . $conectar->error);
}
return $conectar;

?>
