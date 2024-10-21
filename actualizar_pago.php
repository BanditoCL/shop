<?php
include 'conexion.php';
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_venta'])) {
    $id_venta = $data['id_venta'];
    $sql = "UPDATE ventas SET estado_pago = 'completado' WHERE id_venta = '$id_venta'";

    if (mysqli_query($conectar, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conectar)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID de venta no proporcionado.']);
}
?>
