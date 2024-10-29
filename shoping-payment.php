<?php
session_start();
include 'conexion.php';

// Verificar si el cliente está logueado
if (!isset($_SESSION['id_cliente'])) {
    header('Location: index.php'); // Redirige si no está logueado
    exit();
}

$id_cliente = $_SESSION['id_cliente'];
$id_venta = isset($_POST['id_venta']) ? $_POST['id_venta'] : null;

if (!$id_venta) {
    echo "<script>alert('ID de venta no proporcionado.')</script>";
    echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";
    exit();
}

// Consultar la venta y los datos del cliente
$consulta = "SELECT v.*, c.nombres, c.apellidos, c.email, c.telefono 
             FROM ventas v 
             INNER JOIN clientes c ON v.id_cliente = c.id_cliente 
             WHERE v.id_venta = '$id_venta' AND c.id_cliente = '$id_cliente'";

$resultado = mysqli_query($conectar, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    $venta = mysqli_fetch_assoc($resultado);
} else {
    echo "<script>alert('No se encontró la venta o no tienes permiso para verla.')</script>";
    echo "<script>setTimeout(function() { window.history.go(-1); }, 1000)</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <title>MRP STORE | PAYMENT</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css" />
    <link rel="stylesheet" href="css/nice-select.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery-ui.min.css" type="text/css" />
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css" />
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />

    <script src="https://www.paypal.com/sdk/js?client-id=AX0P5pr5EOqpbFXKwqnK9dhDLMrsbYPDcX3Mu1nAKglo1KfOzZIJXcetwz6BtzAkfH-9fYAtpV2nQLra&currency=USD&components=buttons&enable-funding=venmo,paylater,card"></script>
</head>

<body>

    <?php include('header.php'); ?>

    <section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Confirmación de Pago</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.php">Inicio</a>
                            <span>Payment</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="shoping-cart spad">
        <div class="container">
            <h3>Detalles de la Orden</h3>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Nombre Completo</th>
                        <td><?php echo $venta['nombres'] . ' ' . $venta['apellidos']; ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo $venta['email']; ?></td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td><?php echo $venta['telefono']; ?></td>
                    </tr>
                    <tr>
                        <th>Dirección de Envío</th>
                        <td><?php echo $venta['direccion_envio']; ?></td>
                    </tr>
                    <tr>
                        <th>Monto Total</th>
                        <td>$<?php echo number_format($venta['monto_total'], 2); ?></td>
                    </tr>
                    <tr>
                        <th>Fecha de Compra</th>
                        <td><?php echo $venta['fecha']; ?></td>
                    </tr>
                    <tr>
                        <th>Estado de Pago</th>
                        <td><?php echo $venta['estado_pago']; ?></td>
                    </tr>
                    <tr>
                        <th>Estado de Envío</th>
                        <td><?php echo $venta['estado_envio']; ?></td>
                    </tr>
                    <tr>
                        <th>Notas</th>
                        <td><?php echo $venta['notas']; ?></td>
                    </tr>
                </tbody>
            </table>

            <div id="paypal-button-container"></div>

        </div>

    </section>

    <script>
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $venta['monto_total']; ?>' // Monto total de la venta
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Pago realizado con éxito, ' + details.payer.name.given_name + '!');

                    // Enviar actualización del estado de pago a la base de datos
                    fetch('actualizar_pago.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id_venta: '<?php echo $id_venta; ?>'
                        })
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            window.location.href = "mis_compras.php";
                        } else {
                            alert('Hubo un error al actualizar el estado de pago.');
                        }
                    }).catch(error => console.error('Error:', error));
                });
            },
            onCancel: function(data) {
                alert('Pago cancelado.');
            },
            onError: function(err) {
                console.error('Error en el pago:', err);
            }
        }).render('#paypal-button-container');
    </script>

    <?php include('footer.php'); ?>

    <!-- Plugins JS -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>