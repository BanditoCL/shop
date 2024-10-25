<?php
include_once "db_ecommerce.php";

// Verificar si se está editando un producto existente
$idProducto = mysqli_real_escape_string($conn, $_REQUEST['id'] ?? '');

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
    
// Verificar si se ha solicitado eliminar el PDF
if (isset($_REQUEST['eliminarPdf'])) {
    $queryPdfActual = "SELECT documentacion FROM productos WHERE id_producto = '$idProducto'";
    $resPdfActual = mysqli_query($conn, $queryPdfActual);
    $pdfActual = mysqli_fetch_assoc($resPdfActual);

    if ($pdfActual && file_exists($pdfActual['documentacion'])) {
        unlink($pdfActual['documentacion']);
    }

    // Actualizar la base de datos para eliminar la ruta del PDF
    $queryEliminarPdf = "UPDATE productos SET documentacion = NULL WHERE id_producto = '$idProducto'";
    mysqli_query($conn, $queryEliminarPdf);
}

// Manejo de la carga de un nuevo PDF
if (isset($_FILES['pdf_documentacion']) && $_FILES['pdf_documentacion']['error'] == 0) {
    // Verificar y eliminar el PDF actual
    $queryPdfActual = "SELECT documentacion FROM productos WHERE id_producto = '$idProducto'";
    $resPdfActual = mysqli_query($conn, $queryPdfActual);
    $pdfActual = mysqli_fetch_assoc($resPdfActual);

    if ($pdfActual && file_exists($pdfActual['documentacion'])) {
        unlink($pdfActual['documentacion']);
    }

    // Crear el directorio específico para almacenar el PDF
    $directorioDocumentacion = "documentaciones/$idProducto/";
    if (!is_dir($directorioDocumentacion)) {
        mkdir($directorioDocumentacion, 0777, true);
    }

    // Subir el nuevo PDF
    $nombrePdf = time() . '_' . basename($_FILES['pdf_documentacion']['name']);
    $rutaPdf = $directorioDocumentacion . $nombrePdf;

    if (move_uploaded_file($_FILES['pdf_documentacion']['tmp_name'], $rutaPdf)) {
        // Actualizar la ruta del PDF en la base de datos
        $queryPdfUpdate = "UPDATE productos SET documentacion = '$rutaPdf' WHERE id_producto = '$idProducto'";
        mysqli_query($conn, $queryPdfUpdate);
    }
}

// Obtener el producto para editar
$query = "SELECT * FROM productos WHERE id_producto = '$idProducto'";
$res = mysqli_query($conn, $query);

// Verificar si el producto fue encontrado
if ($res && mysqli_num_rows($res) > 0) {
    $producto = mysqli_fetch_assoc($res);
} else {
    // Manejar el caso cuando el producto no existe
    echo "Error: Producto no encontrado.";
    exit; // Salir para evitar errores adicionales
}

// Crear el directorio específico para las imágenes del producto si no existe
$categoria = mysqli_real_escape_string($conn, $_REQUEST['categoria'] ?? $producto['categoria']);
$subcategoria = mysqli_real_escape_string($conn, $_REQUEST['subcategoria'] ?? $producto['subcategoria']);
$directorio = "imagenes_productos/$categoria/$subcategoria/$idProducto/";

if (!is_dir($directorio)) {
    mkdir($directorio, 0777, true);
}

// Obtener el producto para editar
$query = "SELECT * FROM productos WHERE id_producto = '$idProducto'";
$res = mysqli_query($conn, $query);
$producto = mysqli_fetch_assoc($res);

// Aquí recuperamos las categorías y subcategorías del producto
$categoriaActual = $producto['categoria'];
$subcategoriaActual = $producto['subcategoria'];

// Actualizar el producto si se ha enviado el formulario
if (isset($_REQUEST['guardar'])) {
    $marca = mysqli_real_escape_string($conn, $_REQUEST['marca'] ?? '');
    $modelo = mysqli_real_escape_string($conn, $_REQUEST['modelo'] ?? '');
    $precio = mysqli_real_escape_string($conn, $_REQUEST['precio'] ?? '');
    $stock = mysqli_real_escape_string($conn, $_REQUEST['stock'] ?? '');
    $descripcion = mysqli_real_escape_string($conn, $_REQUEST['descripcion'] ?? '');
    $detalles = mysqli_real_escape_string($conn, $_REQUEST['detalles'] ?? '');
    $material = mysqli_real_escape_string($conn, $_REQUEST['material'] ?? '');
    $origen = mysqli_real_escape_string($conn, $_REQUEST['origen'] ?? '');
    $peso = mysqli_real_escape_string($conn, $_REQUEST['peso'] ?? '');
    $nuevaCategoria = mysqli_real_escape_string($conn, $_REQUEST['categoria']);
    $nuevaSubcategoria = mysqli_real_escape_string($conn, $_REQUEST['subcategoria']);
    $imagenPrincipal = $_FILES['imagen_principal'];
    $imagenesSecundarias = $_FILES['imagenes_secundarias'];

    // Obtener la categoría y subcategoría actuales del producto
    $queryActual = "SELECT categoria, subcategoria FROM productos WHERE id_producto = '$idProducto'";
    $resActual = mysqli_query($conn, $queryActual);
    $productoActual = mysqli_fetch_assoc($resActual);
    $categoriaActual = $productoActual['categoria'];
    $subcategoriaActual = $productoActual['subcategoria'];

    // Actualizar los datos del producto
    $queryUpdate = "UPDATE productos SET marca = '$marca', modelo = '$modelo', precio = '$precio', stock = '$stock', descripcion = '$descripcion', detalles = '$detalles', material = '$material', origen = '$origen', peso = '$peso', categoria = '$nuevaCategoria', subcategoria = '$nuevaSubcategoria' WHERE id_producto = '$idProducto'";
    mysqli_query($conn, $queryUpdate);

    // Crear el directorio específico para las nuevas imágenes del producto si no existe
    $directorio = "imagenes_productos/$nuevaCategoria/$nuevaSubcategoria/$idProducto/";
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    // Verificar si la categoría o subcategoría ha cambiado y mover las imágenes existentes
    if ($categoriaActual !== $nuevaCategoria || $subcategoriaActual !== $nuevaSubcategoria) {
        // Obtener las rutas de todas las imágenes actuales del producto
        $queryImagenes = "SELECT id_imagen, ruta FROM imagenes WHERE id_producto = '$idProducto'";
        $resImagenes = mysqli_query($conn, $queryImagenes);

        while ($imagen = mysqli_fetch_assoc($resImagenes)) {
            $rutaActual = $imagen['ruta'];
            $nombreArchivo = basename($rutaActual); // Obtener el nombre del archivo

            $nuevaRuta = $directorio . $nombreArchivo; // Definir la nueva ruta

            // Mover el archivo a la nueva ruta
            if (file_exists($rutaActual)) {
                if (rename($rutaActual, $nuevaRuta)) {
                    // Actualizar la ruta en la base de datos si el movimiento fue exitoso
                    $queryUpdateRuta = "UPDATE imagenes SET ruta = '$nuevaRuta' WHERE id_imagen = '{$imagen['id_imagen']}'";
                    mysqli_query($conn, $queryUpdateRuta);
                } else {
                    echo "Error al mover la imagen: $rutaActual a $nuevaRuta";
                }
            }
        }

        // Verificar si se necesita eliminar el directorio antiguo
        $directorioAntiguo = "imagenes_productos/$categoriaActual/$subcategoriaActual/$idProducto/";
        $archivosAntiguos = glob("$directorioAntiguo/*");
        $carpetaVacia = (count($archivosAntiguos) === 0);

        if ($carpetaVacia) {
            // Eliminar la carpeta del producto
            rmdir($directorioAntiguo);

            // Verificar si hay otras carpetas en la subcategoría
            $subcarpetas = glob("imagenes_productos/$categoriaActual/$subcategoriaActual/*", GLOB_ONLYDIR);
            if (count($subcarpetas) === 0) {
                // Eliminar la subcategoría si está vacía
                rmdir("imagenes_productos/$categoriaActual/$subcategoriaActual");
                
                // Verificar si hay otras subcategorías en la categoría
                $subcategorias = glob("imagenes_productos/$categoriaActual/*", GLOB_ONLYDIR);
                if (count($subcategorias) === 0) {
                    // Eliminar la categoría si está vacía
                    rmdir("imagenes_productos/$categoriaActual");
                }
            }
        }
    }

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
    if (isset($imagenesSecundarias['error']) && $imagenesSecundarias['error'][0] == 0) {
        // Eliminar todas las imágenes secundarias actuales
        // $queryImagenesSecundarias = "SELECT id_imagen, ruta FROM imagenes WHERE id_producto = '$idProducto' AND img_principal = 0";
        // $resImagenesSecundarias = mysqli_query($conn, $queryImagenesSecundarias);
        // while ($imagenSecundaria = mysqli_fetch_assoc($resImagenesSecundarias)) {
        //     if (file_exists($imagenSecundaria['ruta'])) {
        //         unlink($imagenSecundaria['ruta']);
        //     }
        //     $queryDeleteSecundaria = "DELETE FROM imagenes WHERE id_imagen = '{$imagenSecundaria['id_imagen']}'";
        //     mysqli_query($conn, $queryDeleteSecundaria);
        // }

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

    // Redirigir después de guardar
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
                                    <label>Modelo</label> 
                                    <input type="text" name="modelo" class="form-control" value="<?php echo $producto['modelo']; ?>" required>
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
                                    <label>Categoría</label>
                                    <select name="categoria" class="form-control" required>
                                        <option value="Otros" <?php if ($categoriaActual == 'Otros') echo 'selected'; ?>>Otros</option>
                                        <option value="Herramientas-electricas" <?php if ($categoriaActual == 'Herramientas-electricas') echo 'selected'; ?>>Herramientas-electricas</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Subcategoría</label>
                                    <select name="subcategoria" class="form-control" required>
                                        <option value="Otros" <?php if ($subcategoriaActual == 'Otros') echo 'selected'; ?>>Otros</option>
                                        <option value="Maquinarias" <?php if ($subcategoriaActual == 'Maquinarias') echo 'selected'; ?>>Maquinarias</option>
                                    </select>
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
                                    <label>Imagen Principal Actual:</label><br>
                                    <?php
                                    $queryImagenPrincipal = "SELECT id_imagen, ruta FROM imagenes WHERE id_producto = '$idProducto' AND img_principal = 1";
                                    $resImagenPrincipal = mysqli_query($conn, $queryImagenPrincipal);
                                    $imagenPrincipal = mysqli_fetch_assoc($resImagenPrincipal);
                                    if ($imagenPrincipal) {
                                        echo '<img src="' . $imagenPrincipal['ruta'] . '" width="100" height="100">';
                                    }
                                    ?>
                                </div>

                                <!-- Imágenes Secundarias -->
                                <div class="form-group">
                                    <label>Imágenes Secundarias (Nuevas)</label>
                                    <input type="file" name="imagenes_secundarias[]" class="form-control" multiple>
                                    <label>Imágenes Secundarias Actuales:</label>
                                    <div class="row">
                                        <?php
                                        $queryImagenesSecundarias = "SELECT id_imagen, ruta FROM imagenes WHERE id_producto = '$idProducto' AND img_principal = 0";
                                        $resImagenesSecundarias = mysqli_query($conn, $queryImagenesSecundarias);
                                        while ($imagenSecundaria = mysqli_fetch_assoc($resImagenesSecundarias)) {
                                            echo '<div class="col-3 text-center">';
                                            echo '<img src="' . $imagenSecundaria['ruta'] . '" width="100" height="100">';
                                            echo '<br><a href="panel.php?modulo=editarProducto&id=' . $idProducto . '&eliminarImagen=' . $imagenSecundaria['id_imagen'] . '" class="btn btn-danger btn-sm mt-1">Eliminar</a>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>

                                <!-- Mostrar enlace al documento PDF -->
                                <div class="form-group">
                                    <label>PDF Documentación (Nueva)</label>
                                    <input type="file" name="pdf_documentacion" class="form-control" accept=".pdf">
                                    <label>PDF Actual:</label><br>
                                    <?php
                                    $queryPdfActual = "SELECT documentacion FROM productos WHERE id_producto = '$idProducto'";
                                    $resPdfActual = mysqli_query($conn, $queryPdfActual);
                                    $pdfActual = mysqli_fetch_assoc($resPdfActual);

                                    if ($pdfActual && file_exists($pdfActual['documentacion'])) {
                                        echo '<a href="' . $pdfActual['documentacion'] . '" target="_blank">Ver PDF Actual</a>';
                                        echo '<br><a href="panel.php?modulo=editarProducto&id=' . $idProducto . '&eliminarPdf=1" class="btn btn-danger btn-sm mt-1">Eliminar PDF</a>';
                                    } else {
                                        echo 'No hay PDF cargado.';
                                    }
                                    ?>
                                </div>

                                

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                                    <a href="panel.php?modulo=productos" class="btn btn-secondary">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
