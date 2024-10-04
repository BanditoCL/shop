<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Iniciar Session</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card">
                <article class="card-body">
                    <div class="container">
                        <form action="login_proceso.php" method="post">
                            <div class="form-group">
                                <input name="" class="form-control" placeholder="Email" type="email" name="email">
                            </div> <!-- form-group// -->
                            <div class="form-group">
                                <input class="form-control" placeholder="******" type="password" name="pass">
                            </div> <!-- form-group// -->
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="submit" class="primary-btn" style="border: none; border-radius: 10px;"> Login </button>
                                    </div> <!-- form-group// -->
                                </div>
                                <div class="col-md-6 text-right">
                                    <a class="small" href="#">Forgot password?</a>
                                </div>
                            </div> <!-- .row// -->

                        </form>
                    </div>
                </article>
            </div> <!-- card.// -->
        </div>
    </div>
</div>