<?php 
    session_start();
    session_unset(); // Limpiar todas las variables de sesión
    session_destroy(); // Destruir la sesión
    echo "<script>alert('Saliste :c')</script>";
    echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";
    exit();
?>