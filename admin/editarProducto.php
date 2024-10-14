<?php
include_once "db_ecommerce.php";

// Verificar si se ha solicitado eliminar una imagen
if (isset($_REQUEST['eliminarImagen'])) {
    $idImagen = mysqli_real_escape_string($conn, $_REQUEST['eliminarImagen']);
    
    // Obtener la ruta de la imagen para eliminar el archivo del proyecto
    $queryImagen = "SELECT ruta FROM imagenes WHERE id_imagen = '$idImagen'";
    $resImagen = mysqli_query($conn, $queryImagen);
    $imagen = mysqli_fetch_assoc($resImagen);
    $rutaImagen = $imagen['ruta'];

    // Eliminar el archivo de imagen del proyecto
    if (file_exists($rutaImagen)) {
        unlink($rutaImagen);
    }

    // Eliminar la imagen de la base de datos
    $queryDelete = "DELETE FROM imagenes WHERE id_imagen = '$idImagen'";
    mysqli_query($conn, $queryDelete);
}

// Verificar si se está editando un producto existente
$idProducto = mysqli_real_escape_string($conn, $_REQUEST['id'] ?? '');
$query = "SELECT * FROM productos WHERE id_producto = '$idProducto'";
$res = mysqli_query($conn, $query);
$producto = mysqli_fetch_assoc($res);

// Obtener la categoría y subcategoría actuales del producto
$categoria = mysqli_real_escape_string($conn, $_REQUEST['categoria'] ?? $producto['categoria']);
$subcategoria = mysqli_real_escape_string($conn, $_REQUEST['subcategoria'] ?? $producto['subcategoria']);

// Crear el directorio específico para el producto si no existe
$directorio = "imagenes_productos/$categoria/$subcategoria/$idProducto/";

if (!is_dir($directorio)) {
    mkdir($directorio, 0777, true);
}

// Actualizar el producto si se ha enviado el formulario
if (isset($_REQUEST['guardar'])) {
    $marca = mysqli_real_escape_string($conn, $_REQUEST['marca'] ?? '');
    $precio = mysqli_real_escape_string($conn, $_REQUEST['precio'] ?? '');
    $stock = mysqli_real_escape_string($conn, $_REQUEST['stock'] ?? '');
    $descripcion = mysqli_real_escape_string($conn, $_REQUEST['descripcion'] ?? '');
    $detalles = mysqli_real_escape_string($conn, $_REQUEST['detalles'] ?? '');
    $material = mysqli_real_escape_string($conn, $_REQUEST['material'] ?? '');
    $origen = mysqli_real_escape_string($conn, $_REQUEST['origen'] ?? '');
    $peso = mysqli_real_escape_string($conn, $_REQUEST['peso'] ?? '');
    $imagenPrincipal = $_FILES['imagen_principal'];
    $imagenesSecundarias = $_FILES['imagenes_secundarias'];

    // Actualizar los datos del producto
    $queryUpdate = "UPDATE productos SET marca = '$marca', precio = '$precio', stock = '$stock', descripcion = '$descripcion', detalles = '$detalles', material = '$material', origen = '$origen', peso = '$peso' WHERE id_producto = '$idProducto'";
    mysqli_query($conn, $queryUpdate);

    // Verificar y actualizar la imagen principal si se ha subido una nueva
    if ($imagenPrincipal && $imagenPrincipal['error'] == 0) {
        // Eliminar la imagen principal actual
        $queryImagenActual = "SELECT ruta FROM imagenes WHERE id_producto = '$idProducto' AND img_principal = 1";
        $resImagenActual = mysqli_query($conn, $queryImagenActual);
        $imagenActual = mysqli_fetch_assoc($resImagenActual);

        if ($imagenActual && file_exists($imagenActual['ruta'])) {
            unlink($imagenActual['ruta']);
        }

        // Subir la nueva imagen principal y actualizar la base de datos
        $nombreArchivo = time() . '_' . basename($imagenPrincipal['name']);
        $rutaArchivo = $directorio . $nombreArchivo;

        if (move_uploaded_file($imagenPrincipal['tmp_name'], $rutaArchivo)) {
            $queryImagenUpdate = "UPDATE imagenes SET ruta = '$rutaArchivo' WHERE id_producto = '$idProducto' AND img_principal = 1";
            mysqli_query($conn, $queryImagenUpdate);
        }
    }

    // Manejo de las imágenes secundarias
    if ($imagenesSecundarias['error'][0] == 0) {
        // Eliminar todas las imágenes secundarias actuales
        $queryImagenesSecundarias = "SELECT id_imagen, ruta FROM imagenes WHERE id_producto = '$idProducto' AND img_principal = 0";
        $resImagenesSecundarias = mysqli_query($conn, $queryImagenesSecundarias);
        while ($imagenSecundaria = mysqli_fetch_assoc($resImagenesSecundarias)) {
            if (file_exists($imagenSecundaria['ruta'])) {
                unlink($imagenSecundaria['ruta']);
            }
            $queryDeleteSecundaria = "DELETE FROM imagenes WHERE id_imagen = '{$imagenSecundaria['id_imagen']}'";
            mysqli_query($conn, $queryDeleteSecundaria);
        }

        // Subir nuevas imágenes secundarias
        foreach ($imagenesSecundarias['tmp_name'] as $key => $tmp_name) {
            $nombreArchivoSecundario = time() . '_' . basename($imagenesSecundarias['name'][$key]);
            $rutaArchivoSecundario = $directorio . $nombreArchivoSecundario;

            if (move_uploaded_file($tmp_name, $rutaArchivoSecundario)) {
                $queryInsertSecundaria = "INSERT INTO imagenes (id_producto, ruta, img_principal) VALUES ('$idProducto', '$rutaArchivoSecundario', 0)";
                mysqli_query($conn, $queryInsertSecundaria);
            }
        }
    }

    echo '<meta http-equiv="refresh" content="0; url=panel.php?modulo=productos&mensaje=Producto actualizado exitosamente" />';
}
?>
<!-- Formulario de edición de producto -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Editar Producto</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="panel.php?modulo=editarProducto&id=<?php echo $idProducto; ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Marca</label>
                                    <input type="text" name="marca" class="form-control" value="<?php echo $producto['marca']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo $producto['precio']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Stock</label>
                                    <input type="number" name="stock" class="form-control" value="<?php echo $producto['stock']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="descripcion" class="form-control" required><?php echo $producto['descripcion']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Detalles</label>
                                    <textarea name="detalles" class="form-control"><?php echo $producto['detalles']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Material</label>
                                    <input type="text" name="material" class="form-control" value="<?php echo $producto['material']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Origen</label>
                                    <input type="text" name="origen" class="form-control" value="<?php echo $producto['origen']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Peso</label>
                                    <input type="text" name="peso" class="form-control" value="<?php echo $producto['peso']; ?>">
                                </div>

                                <!-- Imagen Principal -->
                                <div class="form-group">
                                    <label>Imagen Principal (Nueva)</label>
                                    <input type="file" name="imagen_principal" class="form-control">
                                    <label>Imagen Principal Actual:</label>
                                    <?php
                                    $queryImagenPrincipal = "SELECT id_imagen, ruta FROM imagenes WHERE id_producto = '$idProducto' AND img_principal = 1";
                                    $resImagenPrincipal = mysqli_query($conn, $queryImagenPrincipal);
                                    $imagenPrincipal = mysqli_fetch_assoc($resImagenPrincipal);

                                    if ($imagenPrincipal) {
                                        echo '<div>';
                                        echo '<img src="' . $imagenPrincipal['ruta'] . '" alt="Imagen Principal" style="width: 100px; height: 100px;">';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>

                                <!-- Imágenes Secundarias -->
                                <div class="form-group">
                                    <label>Imágenes Secundarias (Nuevas)</label>
                                    <input type="file" name="imagenes_secundarias[]" class="form-control" multiple>
                                    <label>Imágenes Secundarias Actuales:</label>
                                    <div class="d-flex flex-wrap">
                                        <?php
                                        $queryImagenesSecundarias = "SELECT id_imagen, ruta FROM imagenes WHERE id_producto = '$idProducto' AND img_principal = 0";
                                        $resImagenesSecundarias = mysqli_query($conn, $queryImagenesSecundarias);

                                        while ($imagenSecundaria = mysqli_fetch_assoc($resImagenesSecundarias)) {
                                            echo '<div style="margin: 5px; text-align: center;">';
                                            echo '<img src="' . $imagenSecundaria['ruta'] . '" alt="Imagen Secundaria" style="width: 100px; height: 100px;">';
                                            echo '<br>';
                                            echo '<a href="panel.php?modulo=editarProducto&id=' . $idProducto . '&eliminarImagen=' . $imagenSecundaria['id_imagen'] . '" class="text-danger">X</a>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary" name="guardar">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
