<?php
session_start();
include '../conexion.php';  // Asegúrate de tener tu conexión a la base de datos

if (isset($_SESSION['id_cliente'])) {
    $id_cliente = $_SESSION['id_cliente'];

    // Consulta para contar los ítems en el carrito del usuario
    $sql = "SELECT SUM(cantidad) as total FROM cart WHERE id_cliente = ?";
    $stmt = $conectar->prepare($sql);
    $stmt->bind_param('i', $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo $row['total'] ? $row['total'] : 0;  // Si no hay productos, devuelve 0
}
?>
