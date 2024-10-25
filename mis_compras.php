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
    // Ensure the $id_cliente variable is sanitized or use prepared statements for security
    $consult_pedidos = "SELECT * FROM ventas WHERE id_cliente = '$id_cliente'";

    // Execute query and handle potential errors
    $result_pedidos = mysqli_query($conectar, $consult_pedidos)
        or die("Error in query: " . mysqli_error($conectar));
    ?>

    <!-- Shoping Cart Section Begin -->
    <section class="shoping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Monto Total</th>
                                    <th>Estado de Pago</th>
                                    <th>Estado de Envío</th>
                                    <th>ID Rastreo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_array($result_pedidos)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row['fecha']; ?></td>
                                        <td><?php echo $row['monto_total']; ?></td>
                                        <td><?php echo $row['estado_pago']; ?></td>
                                        <td><?php echo $row['estado_envio']; ?></td>
                                        <td><?php echo $row['codigo_rastreo']; ?></td>
                                        <td><span class="icon_close"></span></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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