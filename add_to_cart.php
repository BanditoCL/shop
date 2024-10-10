<?php
session_start();
include 'conexion.php';  // Incluye la conexión a tu base de datos

if (isset($_POST['id_cliente']) && isset($_POST['id_producto']) && isset($_POST['descripcion']) && isset($_POST['precio'])) {
    $id_cliente = $_POST['id_cliente'];
    $id_producto = $_POST['id_producto'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    
    // Verificar si ya existe este producto en el carrito para este usuario
    $checkCart = "SELECT * FROM cart WHERE id_cliente = ? AND id_producto = ?";
    $stmt = $conectar->prepare($checkCart);
    $stmt->bind_param('ii', $id_cliente, $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si el producto ya está en el carrito, incrementa la cantidad
        $updateCart = "UPDATE cart SET cantidad = cantidad + 1 WHERE id_cliente = ? AND id_producto = ?";
        $stmt = $conectar->prepare($updateCart);
        $stmt->bind_param('ii', $id_cliente, $id_producto);
        $stmt->execute();
    } else {
        // Si el producto no está en el carrito, inserta uno nuevo
        $insertCart = "INSERT INTO cart (id_cliente, id_producto, descripcion, precio, cantidad) VALUES (?, ?, ?, ?, 1)";
        $stmt = $conectar->prepare($insertCart);
        $stmt->bind_param('iiss', $id_cliente, $id_producto, $descripcion, $precio);
        $stmt->execute();
    }

    if ($stmt->affected_rows > 0) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $conectar->close();
} else {
    echo 'error';
}
?>
