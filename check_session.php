<?php
session_start();

if (isset($_SESSION['id_cliente'])) {
    echo $_SESSION['id_cliente'];
} else {
    echo false;
}
?>
