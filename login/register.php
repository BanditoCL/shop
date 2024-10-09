<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card">
                <article class="card-body">
                    <form action="login/process.php" method="post">
                        <div class="form-group">
                            <input class="form-control" placeholder="Nombres" type="text" name="nombres">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Apellidos" type="text" name="apellidos">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Email" type="email" name="email">
                        </div>
                        <div class="form-group position-relative">
                            <input class="form-control" id="pass" placeholder="Contraseña" type="password" name="pass">
                            <span toggle="#pass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        <div class="form-group position-relative">
                            <input class="form-control" id="r_pass" placeholder="Repetir Contraseña" type="password" name="r_pass">
                            <span toggle="#r_pass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <div class="form-group d-flex justify-content-center">
                                    <button type="submit" class="primary-btn" style="border: none; border-radius: 10px;">Registrarse</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </article>
            </div>
        </div>
    </div>
</div>

<!-- Script para mostrar/ocultar contraseñas -->
<script>
    document.querySelectorAll('.toggle-password').forEach(item => {
        item.addEventListener('click', function() {
            const input = document.querySelector(this.getAttribute('toggle'));
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
</script>

<style>
    .field-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>