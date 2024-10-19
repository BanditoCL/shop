<?php
session_start();
include '../conexion.php';  // Asegúrate de tener tu conexión a la base de datos

if (isset($_SESSION['id_cliente'])) {
    $id_cliente = $_SESSION['id_cliente'];

    // Consulta para obtener los ítems del carrito del usuario
    $sql = "SELECT id_producto, descripcion, precio, cantidad FROM cart WHERE id_cliente = ?";
    $stmt = $conectar->prepare($sql);
    $stmt->bind_param('i', $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = array();

    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;  // Agregar cada ítem a la lista
    }

    // Devolver los ítems en formato JSON
    echo json_encode($cartItems);
}
?>
