<?php
include_once(dirname(__FILE__).'/../class/csrf.class.php');
include_once(dirname(__FILE__).'/../class/login.class.php');
$objLog=new login();
$csrf = new csrf();

// Genera un identificador y lo valida
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
 
// Genera nombres aleatorios para el formulario
$form_names = $csrf->form_names(array('user', 'telefono', 'email', 'empresa'), false);

 
if(isset($_POST[$form_names['user']], $_POST[$form_names['telefono']], $_POST[$form_names['email']], $_POST[$form_names['empresa']])) {
        // Revisa si el identificador y su valor son válidos.
        if($csrf->check_valid('post')) {
                // Get the Form Variables.
                $user = filter_input(INPUT_POST, $form_names['user'], FILTER_SANITIZE_SPECIAL_CHARS);
                $telefono = filter_input(INPUT_POST, $form_names['telefono'], FILTER_SANITIZE_SPECIAL_CHARS);
                $email = filter_input(INPUT_POST, $form_names['email'], FILTER_SANITIZE_SPECIAL_CHARS);
                $empresa = filter_input(INPUT_POST, $form_names['empresa'], FILTER_SANITIZE_SPECIAL_CHARS);
 
                // completamos la variable error si es necesario
                if (empty($user) or empty($telefono) or empty($email))   $error['vacio'] = 'Complete los datos requeridos';
                
                // si no hay errores registramos al usuario
                if (empty($error)) {
                    // verificamos que los datos ingresados correspondan a un usuario
                    if (!$objLog->loginUser($user,$telefono,$email,$empresa)) {
                        $error['noExiste'] = 'Los datos son incorrectos';
                    } 
                }
        }
        // Regenera un valor aleatorio nuevo para el formulario.
        $form_names = $csrf->form_names(array('user', 'telefono', 'email', 'empresa'), true);
}

if (!$logueado=$objLog->login_check()) {
    unset($_SESSION['mayoristas']);
}

?>


    <!-- Modal -->
    <div class="modal right fade" id="modLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Acceso Mayoristas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col wrap-form-signin">
                                <br>
                                <section id="signin">
                                <div class="alert alert-danger" role="alert">
                                La <strong>compra mínima</strong> mayorista es de <strong>$180.000</strong>.
                                </div>
                                    <form class="form-signin" method="post">
                                        <input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
                                        <div class="form-group">
                                            <input type="hidden" id="inputNombre" name="<?php echo $form_names['user']; ?>" value="mayorista" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" id="inputTel" name="<?php echo $form_names['telefono']; ?>" value="11111111" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" id="inputEmail" name="<?php echo $form_names['email']; ?>" value="generico@email.com" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" id="inputEmpresa" name="<?php echo $form_names['empresa']; ?>" value="sin empresa">
                                        </div>
                                        <button class="btn btn-lg btn-primary w-100 text-white" type="submit">INGRESAR COMO MAYORISTA</button>
                                        <hr>
                                        <?php if (!empty($error)) { ?>
                                                    <?php foreach ($error as $mensaje) { ?>
                                                        <div class="alert alert-danger" role="alert"><small><i class="fas fa-exclamation-circle"></i> 
                                                        <?php echo $mensaje ?>
                                                        </small></div>
                                                    <?php } ?>
                                        <?php } ?>
                                    </form>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- modal-content -->
        </div><!-- modal-dialog -->
    </div><!-- modal -->
