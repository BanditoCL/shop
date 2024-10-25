<?php
if (isset($_REQUEST['guardar'])) {
    include_once "db_ecommerce.php";

    $marca = mysqli_real_escape_string($conn, $_REQUEST['marca'] ?? '');
    $modelo = mysqli_real_escape_string($conn, $_REQUEST['modelo'] ?? '');
    $precio = mysqli_real_escape_string($conn, $_REQUEST['precio'] ?? '');
    $stock = mysqli_real_escape_string($conn, $_REQUEST['stock'] ?? '');
    $categoria = mysqli_real_escape_string($conn, $_REQUEST['categoria'] ?? '');
    $subcategoria = mysqli_real_escape_string($conn, $_REQUEST['subcategoria'] ?? '');
    $fecha_ingreso = mysqli_real_escape_string($conn, $_REQUEST['fecha_ingreso'] ?? '');
    $descripcion = mysqli_real_escape_string($conn, $_REQUEST['descripcion'] ?? '');
    $detalles = mysqli_real_escape_string($conn, $_REQUEST['detalles'] ?? '');
    $material = mysqli_real_escape_string($conn, $_REQUEST['material'] ?? '');
    $origen = mysqli_real_escape_string($conn, $_REQUEST['origen'] ?? '');
    $peso = mysqli_real_escape_string($conn, $_REQUEST['peso'] ?? '');
    $imagenPrincipal = $_FILES['imagen_principal'];
    $imagenesSecundarias = $_FILES['imagenes_secundarias'];
    $documentoPdf = $_FILES['documento_pdf'];

    $query = "INSERT INTO productos (marca, modelo, precio, stock, categoria, subcategoria, fecha_ingreso, descripcion, detalles, material, origen, peso) 
              VALUES ('$marca', '$modelo', '$precio', '$stock', '$categoria', '$subcategoria', '$fecha_ingreso', '$descripcion', '$detalles', '$material', '$origen', '$peso')";
    $res = mysqli_query($conn, $query);

    if ($res) {
        $idProducto = mysqli_insert_id($conn);

        // Crear el directorio para imágenes del producto
        $directorio = "imagenes_productos/$categoria/$subcategoria/$idProducto/";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        // Función para cargar una imagen y devolver su ruta
        function cargarImagen($archivo, $idProducto, $principal = 0) {
            global $conn, $directorio;
            $nombreArchivo = time() . '_' . basename($archivo['name']);
            $rutaArchivo = $directorio . $nombreArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
                // Guardar la ruta de la imagen en la base de datos
                $queryImagen = "INSERT INTO imagenes (id_producto, ruta, img_principal) VALUES ('$idProducto', '$rutaArchivo', '$principal')";
                mysqli_query($conn, $queryImagen);
            }
        }

        // Cargar la imagen principal
        if ($imagenPrincipal && $imagenPrincipal['error'] == 0) {
            cargarImagen($imagenPrincipal, $idProducto, 1);
        }

        // Cargar imágenes secundarias
        foreach ($imagenesSecundarias['tmp_name'] as $key => $tmp_name) {
            $archivoSecundario = [
                'name' => $imagenesSecundarias['name'][$key],
                'tmp_name' => $tmp_name,
                'error' => $imagenesSecundarias['error'][$key]
            ];

            if ($archivoSecundario['error'] == 0) {
                cargarImagen($archivoSecundario, $idProducto, 0);
            }
        }

        // Crear el directorio para el documento PDF
        $directorioDocs = "documentaciones/$idProducto/";
        if (!is_dir($directorioDocs)) {
            mkdir($directorioDocs, 0777, true);
        }

        // Verificar y subir el documento PDF si se ha subido uno nuevo
        if ($documentoPdf && $documentoPdf['error'] == 0) {
            $nombreDocumento = time() . '_' . basename($documentoPdf['name']);
            $rutaDocumento = $directorioDocs . $nombreDocumento;

            if (move_uploaded_file($documentoPdf['tmp_name'], $rutaDocumento)) {
                // Actualizar la base de datos con la ruta del documento PDF
                $queryDocumento = "UPDATE productos SET documentacion = '$rutaDocumento' WHERE id_producto = '$idProducto'";
                mysqli_query($conn, $queryDocumento);
            }
        }

        echo '<meta http-equiv="refresh" content="0; url=panel.php?modulo=productos&mensaje=Producto creado exitosamente" />';
    } else {
        ?>
        <div class="alert alert-danger" role="alert">
            Error al crear producto: <?php echo mysqli_error($conn); ?>
        </div>
        <?php
    }
}
?>

<!-- Formulario de creación de productos -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Crear Producto</h1>
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
                            <form action="panel.php?modulo=crearProducto" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Marca</label>
                                    <input type="text" name="marca" class="form-control" required="required">
                                </div>
                                <div class="form-group">
                                    <label>Modelo</label> <!-- Nuevo campo para modelo -->
                                    <input type="text" name="modelo" class="form-control" required="required">
                                </div>
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input type="number" step="0.01" name="precio" class="form-control" required="required">
                                </div>
                                <div class="form-group">
                                    <label>Stock</label>
                                    <input type="number" name="stock" class="form-control" required="required">
                                </div>
                                <div class="form-group">
                                    <label>Categoría</label>
                                    <select name="categoria" id="categoria" class="form-control" required>
                                        <option value="Otros">Otros</option>
                                        <option value="Herramientas-electricas">Herramientas Eléctricas</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Subcategoría</label>
                                    <select name="subcategoria" id="subcategoria" class="form-control" required>
                                        <option value="Otros">Otros</option>
                                        <option value="Maquinarias">Maquinarias</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Fecha de Ingreso</label>
                                    <input type="date" name="fecha_ingreso" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Detalles</label>
                                    <textarea name="detalles" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Material</label>
                                    <input type="text" name="material" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Origen</label>
                                    <input type="text" name="origen" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Peso</label>
                                    <input type="text" name="peso" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Imagen Principal</label>
                                    <input type="file" name="imagen_principal" class="form-control" required="required">
                                </div>
                                <div class="form-group">
                                    <label>Imágenes Secundarias</label>
                                    <input type="file" name="imagenes_secundarias[]" class="form-control" multiple>
                                </div>
                                <div class="form-group">
                                    <label>Documento PDF</label>
                                    <input type="file" name="documento_pdf" class="form-control" accept=".pdf">
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
