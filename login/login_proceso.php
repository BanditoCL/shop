<?php 
require "../conexion.php";
session_start();

$email = $_POST['email'];
$pass = $_POST['pass'];

$sql = "SELECT id_cliente FROM clientes WHERE email='$email' AND pass='$pass'";
$resultado = mysqli_query($conectar, $sql);

if (mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    $_SESSION['id_cliente'] = $fila['id_cliente'];
    echo "<script>alert('Iniciado:3')</script>";
    echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";
}else{
    echo "<script>alert('Usuario no encontrado')</script>";
    echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";
}