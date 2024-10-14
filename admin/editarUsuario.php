<?php
    include_once "db_ecommerce.php";
          if (isset($_REQUEST['guardar'])) {


          $email = mysqli_real_escape_string($conn, $_REQUEST['email'] ?? '');
          $pass = md5(mysqli_real_escape_string($conn, $_REQUEST['pass'] ?? ''));
          $nombre = mysqli_real_escape_string($conn, $_REQUEST['nombre'] ?? '');
          $apellido = mysqli_real_escape_string($conn, $_REQUEST['apellido'] ?? '');
          $id = mysqli_real_escape_string($conn, $_REQUEST['id'] ?? '');

          $query = "UPDATE usuarios SET 
          email='" . $email . "', 
          pass='" . $pass . "', 
          nombres='" . $nombre . "', 
          apellidos='" . $apellido . "' 
          WHERE id_usuario='" . $id . "'";


          $res = mysqli_query($conn, $query);

    if ($res) {
    echo '<meta http-equiv="refresh" content="0; url=panel.php?modulo=usuarios&mensaje=Usuario ' . $nombre . ' editado exitosamente" />';    
      } else {
    ?>
        <div class="alert alert-danger" role="alert">
            Error al crear usuario <?php echo mysqli_error($conn); ?>
        </div>
<?php
    }
}
    $id= mysqli_real_escape_string($conn,$_REQUEST['id']??'');
    $query="SELECT id_usuario,email,pass,nombres,apellidos from usuarios where id_usuario='".$id."'; ";
    $res=mysqli_query($conn,$query);
    $row=mysqli_fetch_assoc($res);
?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Crear Usuario</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
              <form action="panel.php?modulo=editarUsuario" method="post">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $row['email'] ?>" required="required" >
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="pass" class="form-control" required="required" >
                            </div>
                            <div class="form-group">
                                <label>Nombres</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo $row['nombres'] ?>"  required="required" >
                            </div>
                            <div class="form-group">
                                <label>Apellidos</label>
                                <input type="text" name="apellido" class="form-control" value="<?php echo $row['apellidos'] ?>"  required="required" >
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo $row['id_usuario'] ?>" >
                                <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                            </div>
                        </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>