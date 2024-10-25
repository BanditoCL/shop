<?php
include_once "db_ecommerce.php"; 

if (isset($_REQUEST['idBorrar'])) {
    $id = mysqli_real_escape_string($conn, $_REQUEST['idBorrar'] ?? '');

    // Obtener la categoría y subcategoría del producto
    $queryCatSubcat = "SELECT categoria, subcategoria FROM productos WHERE id_producto = '$id'";
    $resCatSubcat = mysqli_query($conn, $queryCatSubcat);
    $productoCatSubcat = mysqli_fetch_assoc($resCatSubcat);
    $categoria = $productoCatSubcat['categoria'];
    $subcategoria = $productoCatSubcat['subcategoria'];

    // Obtener la ruta del PDF
    $queryPdf = "SELECT documentacion FROM productos WHERE id_producto = '$id'";
    $resPdf = mysqli_query($conn, $queryPdf);
    $pdf = mysqli_fetch_assoc($resPdf);
    $rutaPdf = $pdf['documentacion'];

    // Eliminar el PDF del servidor si existe
    if ($pdf && file_exists($rutaPdf)) {
        unlink($rutaPdf);
    }

    // Eliminar imágenes asociadas al producto
    $queryImagenes = "SELECT ruta FROM imagenes WHERE id_producto = '$id'";
    $resImagenes = mysqli_query($conn, $queryImagenes);

    while ($imagen = mysqli_fetch_assoc($resImagenes)) {
        $rutaImagen = $imagen['ruta'];
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }

    // Eliminar las referencias de las imágenes en la base de datos
    $queryEliminarImagenes = "DELETE FROM imagenes WHERE id_producto = '$id'";
    mysqli_query($conn, $queryEliminarImagenes);

    // Eliminar el producto de la base de datos
    $queryEliminarProducto = "DELETE FROM productos WHERE id_producto = '$id'";
    $resProducto = mysqli_query($conn, $queryEliminarProducto);

    // Eliminar el directorio del producto basado en la categoría y subcategoría
    $directorioProducto = "imagenes_productos/$categoria/$subcategoria/$id";
    if (is_dir($directorioProducto)) {
        array_map('unlink', glob("$directorioProducto/*.*"));
        rmdir($directorioProducto);
    }

    // Verificar si el directorio de la subcategoría está vacío y eliminarlo si es necesario
    $directorioSubcategoria = "imagenes_productos/$categoria/$subcategoria";
    if (is_dir($directorioSubcategoria) && count(glob("$directorioSubcategoria/*")) === 0) {
        rmdir($directorioSubcategoria);
    }

    // Verificar si el directorio de la categoría está vacío y eliminarlo si es necesario
    $directorioCategoria = "imagenes_productos/$categoria";
    if (is_dir($directorioCategoria) && count(glob("$directorioCategoria/*")) === 0) {
        rmdir($directorioCategoria);
    }

    // Manejo de la eliminación del directorio del PDF
    $directorioPdf = dirname($rutaPdf);
    if (is_dir($directorioPdf) && count(glob("$directorioPdf/*")) === 0) {
        rmdir($directorioPdf); // Elimina el directorio del PDF si está vacío
    }

    if ($resProducto) {
        ?>
        <div class="alert alert-warning float-right" role="alert">
            Producto borrado con éxito.
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-danger float-right" role="alert">
            Error al borrar el producto: <?php echo mysqli_error($conn); ?>
        </div>
        <?php
    }
}

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Productos</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tablaProductos" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Categoría</th>
                                        <th>Subcategoría</th>
                                        <th>Descripción</th>
                                        <th>Detalles</th>
                                        <th>Material</th>
                                        <th>Origen</th>
                                        <th>Peso</th>
                                        <th>Fecha de Ingreso</th>
                                        <th>Acciones 
                                        <a href="panel.php?modulo=crearProducto"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT id_producto, marca, modelo, precio, stock, categoria, subcategoria, descripcion, detalles, material, origen, peso, fecha_ingreso FROM productos";
                                    $res = mysqli_query($conn, $query);

                                    while ($row = mysqli_fetch_assoc($res)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row["id_producto"]; ?></td>
                                            <td><?php echo $row["marca"]; ?></td>
                                            <td><?php echo $row["modelo"]; ?></td>
                                            <td><?php echo $row["precio"]; ?></td>
                                            <td><?php echo $row["stock"]; ?></td>
                                            <td><?php echo $row["categoria"]; ?></td>
                                            <td><?php echo $row["subcategoria"]; ?></td>
                                            <td><?php echo $row["descripcion"]; ?></td>
                                            <td><?php echo $row["detalles"]; ?></td>
                                            <td><?php echo $row["material"]; ?></td>
                                            <td><?php echo $row["origen"]; ?></td>
                                            <td><?php echo $row["peso"]; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($row["fecha_ingreso"])); ?></td> <!-- Cambiar formato de fecha -->
                                            <td>
                                                <a href="panel.php?modulo=editarProducto&id=<?php echo $row['id_producto']; ?>" style="margin-right: 5px;"> <i class="fas fa-edit"></i> </a>
                                                <a href="panel.php?modulo=productos&idBorrar=<?php echo $row['id_producto']; ?>" class="text-danger borrarp"><i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row
