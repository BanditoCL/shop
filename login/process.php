<?php
include('../conexion.php');

    // Recibir los datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $r_pass = $_POST['r_pass'];

    // Verificar si las contraseñas coinciden
    if ($pass !== $r_pass) {
        echo "<script>alert('Las contraseñas no coinciden');</script>";
        echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";
        exit();
    }

    // Verificar si el email ya está registrado
    $email_check_query = "SELECT * FROM clientes WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conectar, $email_check_query);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Este correo electrónico ya está registrado');</script>";
        echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";
        exit();
    }

    // Cifrar la contraseña antes de insertarla
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Si todo está bien, insertar el nuevo registro
    $sql = "INSERT INTO clientes (nombres, apellidos, email, pass) VALUES('$nombres', '$apellidos', '$email', '$hashed_pass')";

    if (!mysqli_query($conectar, $sql)) {
        echo "Error al insertar en la tabla 'clientes': " . mysqli_error($conectar);
        exit();
    }

    echo "<script>alert('Usuario Creado :3')</script>";
    echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";

?>