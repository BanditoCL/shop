<?php
session_start();
include 'conexion.php';

// Verificar si hay un usuario logueado
if (!isset($_SESSION['id_cliente'])) {
    header('Location: index.php'); // Redirige si no está logueado
    exit();
}
$id_cliente = $_SESSION['id_cliente'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recibir los datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $pais = $_POST['pais'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $distrito = $_POST['distrito'];
    $postal = $_POST['postal'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    $update_consult = "UPDATE clientes SET nombres = '$nombres', apellidos = '$apellidos', pais = '$pais', direccion = '$direccion', ciudad = '$ciudad', estado = '$estado', distrito = '$distrito', postal = '$postal', telefono = '$telefono', email = '$email' WHERE id_cliente = '$id_cliente'";

    if (!mysqli_query($conectar, $update_consult)) {
        echo "Error al insertar en la tabla 'clientes': " . mysqli_error($conectar);
        exit();
    }

    // Mostrar mensaje de éxito y luego redirigir
    echo "<script>
        alert('Actualizado :3');
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 2000); // Redirige después de 2 segundos
    </script>";
    exit(); // Asegurarse de que no continúe ejecutando el script después de la redirección
}
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
                        <h2>Mis Datos</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.html">Inicio</a>
                            <span>Perfil</span>
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
            <div class="checkout__form">
                <h4>Actualizar datos</h4>
                <form action="profile.php" method="post">
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
                        <p>Estado<span>*</span></p>
                        <input type="text" value="<?php echo $cliente['estado']; ?>" name="estado">
                    </div>
                    <div class="checkout__input">
                        <p>Ciudad<span>*</span></p>
                        <input type="text" value="<?php echo $cliente['ciudad']; ?>" name="ciudad">
                    </div>
                    <div class="checkout__input">
                        <p>Distrito<span>*</span></p>
                        <input type="text" value="<?php echo $cliente['distrito']; ?>" name="distrito">
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
                    <button type="submit" class="site-btn" style="width: 100%; margin-top: 20px;">Guardar Datos</button>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->

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