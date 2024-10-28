<?php
session_start();
include 'conexion.php';

// Verificar si hay un usuario logueado
if (!isset($_SESSION['id_cliente'])) {
    header('Location: index.php'); // Redirige si no está logueado
    exit();
}
$id_cliente = $_SESSION['id_cliente'];

?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ogani | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>

    <?php include('header.php'); ?>


    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Mis Pedidos</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.html">Home</a>
                            <span>Pedidos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->
    <?php
// Asegúrate de tener una sesión iniciada y un cliente válido.
$id_cliente = $_SESSION['id_cliente'];

// Consulta para obtener las ventas del cliente
$consult_pedidos = "SELECT * FROM ventas WHERE id_cliente = '$id_cliente'";
$result_pedidos = mysqli_query($conectar, $consult_pedidos)
    or die("Error en la consulta: " . mysqli_error($conectar));
?>

<!-- Sección de Pedidos -->
<section class="shoping-cart spad">
    <div class="container">
        <h2 class="mb-5 text-center">Historial de Pedidos</h2>

        <div class="accordion" id="accordionExample">
            <?php
            $contador = 0;  // Contador para IDs únicos

            while ($row = mysqli_fetch_array($result_pedidos)) {
                $id_venta = $row['id_venta'];  // ID de la venta actual

                // Consulta para obtener los detalles de la venta
                $consulta_detalles = "SELECT * FROM detalle_venta WHERE id_venta = '$id_venta'";
                $result_detalles = mysqli_query($conectar, $consulta_detalles)
                    or die("Error en los detalles: " . mysqli_error($conectar));
            ?>
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white" id="heading<?php echo $contador; ?>">
                        <h5 class="mb-0 d-flex justify-content-between align-items-center">
                            <span>Fecha: <?php echo $row['fecha']; ?> | Total: S/ <?php echo $row['monto_total']; ?></span>
                            <button class="btn btn-link text-white" type="button"
                                    data-toggle="collapse" data-target="#collapse<?php echo $contador; ?>"
                                    aria-expanded="false" aria-controls="collapse<?php echo $contador; ?>">
                                Ver Detalles
                            </button>
                        </h5>
                    </div>

                    <div id="collapse<?php echo $contador; ?>" class="collapse"
                         aria-labelledby="heading<?php echo $contador; ?>" data-parent="#accordionExample">
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($detalle = mysqli_fetch_array($result_detalles)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $detalle['descripcion']; ?></td>
                                            <td><?php echo $detalle['cantidad']; ?></td>
                                            <td>S/ <?php echo $detalle['precio']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <!-- Botón para proceder al pago -->
                            <div class="d-flex justify-content-end">
                                <a href="shoping-payment.php?id_venta=<?php echo $id_venta; ?>" 
                                   class="btn btn-success mt-3">
                                    Proceder al Pago
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                $contador++;  // Incrementar el contador
            }
            ?>
        </div>
    </div>
</section>

    <!-- Shoping Cart Section End -->

    <?php include('footer.php'); ?>

    <?php include('cart.php'); ?>
    <?php include('login/login.php'); ?>
    <?php include('login/register.php'); ?>

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/cart.js"></script>


</body>

</html>