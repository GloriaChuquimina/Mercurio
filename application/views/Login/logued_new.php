<!DOCTYPE html>
<html lang="es">
<head>
    <!-- LOGIN ANTIGUO -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <title><?php echo $empresa; ?></title>   
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url();?>resources/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?php echo base_url();?>resources/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url();?>resources/dist/css/adminlte.min.css">
     <!-- Theme style login new-->
    <link rel="stylesheet" href="<?php echo base_url();?>resources/css/loginnew.css">
</head>
<body>
    <div class="modern-login-page">
        <div class="login-container">
            <div class="login-box-1">
                <div class="text-center mb-3">
                    <i class="fas fa-layer-group fa-3x" style="color: #ffffff;"></i>
                </div>
                <h1 class="login-title">MERCURIO</h1>
                <p class="login-subtitle">Sistema Contable</p>
                <div class="login-box-2">
<<<<<<< HEAD
                    <?= form_open('Login/loguedNuevo') ?>
=======
                    <?= form_open('Login/logued') ?>
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf


                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Ingrese su usuario" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>


                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="pass" placeholder="Ingrese su contraseña" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>


                    <?php if (!empty($error)) : ?>
                        <div id="alertError" class="custom-alert" style="display: flex;">
                            <span class="message"><?= $error ?></span>
                            <button class="close-btn" onclick="document.getElementById('alertError').style.display='none'">&times;</button>
                        </div>
                    <?php endif; ?>


                    <button type="submit" class="submit-button" id="submitBtn">
                        <i class="fas fa-sign-in-alt"></i> <span>Iniciar Sesión</span>
                    </button>

                    

                <?= form_close() ?>

                    

                </div>
            </div>            
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const alert = document.getElementById('alertError');
            if (alert) {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 5000); // 5 segundos
            }
        });
    </script>

<!-- jQuery -->
<script src="<?php echo base_url();?>resources/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="<?php echo base_url();?>resources/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url();?>resources/dist/js/adminlte.min.js"></script>
</body>
</html>

