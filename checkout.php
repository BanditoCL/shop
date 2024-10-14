<?php
session_start();
include 'conexion.php';

// Verificar si hay un usuario logueado
if (!isset($_SESSION['id_cliente'])) {
    header('Location: index.php'); // Redirige si no está logueado
    exit();
}

$id_cliente = $_SESSION['id_cliente'];

// Consulta para obtener los productos del carrito
$consult_cart = "
    SELECT c.id_producto, p.descripcion, p.precio, c.cantidad 
    FROM cart c 
    INNER JOIN productos p ON c.id_producto = p.id_producto 
    WHERE c.id_cliente = '$id_cliente'
";
$result_cart = mysqli_query($conectar, $consult_cart);

// Calcular el total del pedido
$total = 0;
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
                        <h2>Comprobacion de Pedido</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.html">Inicio</a>
                            <span>Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->
    <?php
    $consult_cliente = "SELECT * FROM clientes WHERE id_cliente = '$id_cliente'";
    $result_cliente = mysqli_query($conectar, $consult_cliente);
    $cliente = mysqli_fetch_assoc($result_cliente);
    ?>
    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h6><span class="icon_tag_alt"></span> Have a coupon? <a href="#">Click here</a> to enter your code
                    </h6>
                </div>
            </div>
            <div class="checkout__form">
                <h4>Detalles de facturación</h4>
                <form action="#">
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Nombres<span>*</span></p>
                                        <input type="text" value="<?php echo $cliente['nombres']; ?>" name="nombres">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Apellidos<span>*</span></p>
                                        <input type="text" value="<?php echo $cliente['apellidos']; ?>" name="apellidos">
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Pais<span>*</span></p>
                                <input type="text" value="<?php echo $cliente['pais']; ?>" name="pais">
                            </div>
                            <div class="checkout__input">
                                <p>Direccion<span>*</span></p>
                                <input type="text" placeholder="Street Address" class="checkout__input__add" value="<?php echo $cliente['direccion']; ?>" name="direccion">
                            </div>
                            <div class="checkout__input">
                                <p>Ciudad<span>*</span></p>
                                <input type="text" value="<?php echo $cliente['ciudad']; ?>" name="ciudad">
                            </div>
                            <div class="checkout__input">
                                <p>Estado<span>*</span></p>
                                <input type="text" value="<?php echo $cliente['estado']; ?>" name="estado">
                            </div>
                            <div class="checkout__input">
                                <p>Postcode / ZIP<span>*</span></p>
                                <input type="text" value="<?php echo $cliente['postal']; ?>" name="postal">
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Telefono<span>*</span></p>
                                        <input type="text" value="<?php echo $cliente['telefono']; ?>" name="telefono">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Email<span>*</span></p>
                                        <input type="text" value="<?php echo $cliente['email']; ?>" name="email">
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Notas de pedido<span>*</span></p>
                                <input type="text" placeholder="Notas sobre su pedido, por ejemplo, notas especiales para la entrega." name="notas">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4>Su Pedido</h4>
                                <div class="checkout__order__products">Producto <span>Total</span></div>
                                <ul>
                                    <?php while ($row = mysqli_fetch_assoc($result_cart)) :
                                        $subtotal = $row['precio'] * $row['cantidad'];
                                        $total += $subtotal;
                                    ?>
                                        <li>
                                            <?php echo $row['descripcion']; ?> (x<?php echo $row['cantidad']; ?>)
                                            <span>$<?php echo number_format($subtotal, 2); ?></span>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                                <div class="checkout__order__subtotal">Subtotal <span>$<?php echo number_format($total, 2); ?></span></div>
                                <div class="checkout__order__total">Total <span>$<?php echo number_format($total, 2); ?></span></div>
                                <button type="submit" class="site-btn">PROCEDER AL PAGO</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->

    <?php include('footer.php'); ?>

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



</body>

</html>