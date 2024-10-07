<?php 
require "../conexion.php";
session_start();

$email = $_POST['email'];
$pass = $_POST['pass'];

// Consultar solo el email y el hash de la contraseña
$sql = "SELECT id_cliente, pass FROM clientes WHERE email='$email'";
$resultado = mysqli_query($conectar, $sql);

if (mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    
    // Usar password_verify para comparar la contraseña ingresada con el hash almacenado
    if (password_verify($pass, $fila['pass'])) {
        $_SESSION['id_cliente'] = $fila['id_cliente'];
        echo "<script>alert('Iniciado :3')</script>";
        echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";
    } else {
        echo "<script>alert('Contraseña incorrecta')</script>";
        echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado')</script>";
    echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";
}
?>
