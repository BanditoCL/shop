<?php
session_start();
include 'conexion.php';

if (isset($_POST['id_producto']) && isset($_SESSION['id_cliente'])) {
    $id_producto = $_POST['id_producto'];
    $id_cliente = $_SESSION['id_cliente'];

    // Verificar la cantidad actual
    $sql = "SELECT cantidad FROM cart WHERE id_cliente = ? AND id_producto = ?";
    $stmt = $conectar->prepare($sql);
    $stmt->bind_param('ii', $id_cliente, $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['cantidad'] > 1) {
        // Disminuir la cantidad
        $new_quantity = $row['cantidad'] - 1;
        $sql_update = "UPDATE cart SET cantidad = ? WHERE id_cliente = ? AND id_producto = ?";
        $stmt_update = $conectar->prepare($sql_update);
        $stmt_update->bind_param('iii', $new_quantity, $id_cliente, $id_producto);
        $stmt_update->execute();
        echo 'success';
    } else {
        // Si la cantidad es 1, eliminar el ítem
        $sql_delete = "DELETE FROM cart WHERE id_cliente = ? AND id_producto = ?";
        $stmt_delete = $conectar->prepare($sql_delete);
        $stmt_delete->bind_param('ii', $id_cliente, $id_producto);
        $stmt_delete->execute();
        echo 'success';
    }
} else {
    echo 'error';
}
?>
