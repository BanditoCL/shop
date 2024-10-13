<?php
session_start();
include 'conexion.php';

if (isset($_POST['id_producto']) && isset($_SESSION['id_cliente'])) {
    $id_producto = $_POST['id_producto'];
    $id_cliente = $_SESSION['id_cliente'];

    // Incrementar la cantidad
    $sql = "UPDATE cart SET cantidad = cantidad + 1 WHERE id_cliente = ? AND id_producto = ?";
    $stmt = $conectar->prepare($sql);
    $stmt->bind_param('ii', $id_cliente, $id_producto);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
