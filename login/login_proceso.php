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
    echo 'success';
} else {
    echo 'error';
}
?>
