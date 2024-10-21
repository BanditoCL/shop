<?php
session_start();
include 'conexion.php';
$id_cliente = $_SESSION['id_cliente'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $pais = $_POST['pais'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $estado = $_POST['estado'];
    $ciudad = $_POST['ciudad'];
    $distrito = $_POST['distrito'];
    $postal = $_POST['postal'];
    $notas = $_POST['notas'];
    $monto_total = $_POST['monto_total']; // Total calculado previamente
    $fecha = date('d/m/Y'); // Fecha actual
    $estado_pago = 'pendiente'; // Default
    $estado_envio = 'en proceso'; // Default

    // Construir la dirección completa
    $direccion_completa = "$direccion, $distrito, $ciudad, $estado, $pais, ZIP: $postal";

    // **Inserción en la tabla ventas**
    $sql_venta = "INSERT INTO ventas (id_cliente, fecha, monto_total, direccion_envio, estado_pago, estado_envio, notas) 
                  VALUES ('$id_cliente', '$fecha', '$monto_total', '$direccion_completa', '$estado_pago', '$estado_envio', '$notas')";

    if (mysqli_query($conectar, $sql_venta)) {
        $id_venta = mysqli_insert_id($conectar); // Obtener el ID de la venta recién creada

        // **Inserción en la tabla detalle_venta**
        foreach ($_SESSION['carrito'] as $producto) {
            $id_producto = $producto['id_producto'];
            $descripcion = $producto['descripcion'];
            $cantidad = $producto['cantidad'];
            $precio = $producto['precio'];

            $sql_detalle = "INSERT INTO detalle_venta (id_venta, id_producto, descripcion, cantidad, precio) 
                            VALUES ('$id_venta', '$id_producto', '$descripcion', '$cantidad', '$precio')";

            if (!mysqli_query($conectar, $sql_detalle)) {
                echo "Error al registrar el detalle: " . mysqli_error($conectar);
                exit; // Detener ejecución si ocurre un error
            }
        }

        // **Eliminar el carrito del cliente**
        $sql_delete_cart = "DELETE FROM cart WHERE id_cliente = '$id_cliente'";
        if (!mysqli_query($conectar, $sql_delete_cart)) {
            echo "Error al vaciar el carrito: " . mysqli_error($conectar);
            exit;
        }

        // Redirigir a shoping-payment.php con el ID de la venta
        header("Location: shoping-payment.php?id_venta=$id_venta");
        exit;
    } else {
        echo "Error al registrar la venta: " . mysqli_error($conectar);
        echo "<script>setTimeout(function() { window.history.go(-1); }, 1000);</script>";
    }
}
?>
