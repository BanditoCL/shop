<?php
session_start();
include 'conexion.php';
$id_cliente = $_SESSION['id_cliente']; // ID del cliente logueado

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // **Obtener productos del carrito antes de insertar la venta**
    $sql_carrito = "SELECT id_producto, descripcion, cantidad, precio FROM cart WHERE id_cliente = '$id_cliente'";
    $result_carrito = mysqli_query($conectar, $sql_carrito);

    if (mysqli_num_rows($result_carrito) > 0) {
        // **Obtener datos del formulario**
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
        $monto_total = $_POST['monto_total'];
        $fecha = date('d/m/Y');
        $estado_pago = 'pendiente';
        $estado_envio = 'en proceso';

        // **Construir la dirección completa**
        $direccion_completa = "$direccion, $distrito, $ciudad, $estado, $pais, ZIP: $postal";

        // **Insertar la venta en la tabla 'ventas'**
        $sql_venta = "INSERT INTO ventas (id_cliente, fecha, monto_total, direccion_envio, estado_pago, estado_envio, notas) 
                      VALUES ('$id_cliente', '$fecha', '$monto_total', '$direccion_completa', '$estado_pago', '$estado_envio', '$notas')";

        if (mysqli_query($conectar, $sql_venta)) {
            $id_venta = mysqli_insert_id($conectar); // Obtener el ID de la venta recién creada

            // **Insertar los detalles de la venta**
            while ($producto = mysqli_fetch_assoc($result_carrito)) {
                $id_producto = $producto['id_producto'];
                $descripcion = $producto['descripcion'];
                $cantidad = $producto['cantidad'];
                $precio = $producto['precio'];

                $sql_detalle = "INSERT INTO detalle_venta (id_venta, id_producto, descripcion, cantidad, precio) 
                                VALUES ('$id_venta', '$id_producto', '$descripcion', '$cantidad', '$precio')";

                if (!mysqli_query($conectar, $sql_detalle)) {
                    echo "Error al registrar el detalle: " . mysqli_error($conectar);
                    exit; // Detener si ocurre un error
                }
            }

            // **Vaciar el carrito del cliente**
            $sql_delete_cart = "DELETE FROM cart WHERE id_cliente = '$id_cliente'";
            if (!mysqli_query($conectar, $sql_delete_cart)) {
                echo "Error al vaciar el carrito: " . mysqli_error($conectar);
                exit;
            }

            // **Formulario oculto para enviar el id_venta por POST**
            echo "
            <form id='postVentaForm' action='shoping-payment.php' method='POST'>
                <input type='hidden' name='id_venta' value='$id_venta'>
            </form>
            <script>
                document.getElementById('postVentaForm').submit();
            </script>";
            exit;
        } else {
            echo "Error al registrar la venta: " . mysqli_error($conectar);
        }
    } else {
        // **Mostrar mensaje si el carrito está vacío**
        echo "<script>alert('El carrito está vacío.')</script>";
        echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";
    }
}
?>
