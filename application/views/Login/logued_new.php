<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/adminlte.min.css" rel="stylesheet">
    <style>
        /* Aquí iría el CSS del componente React convertido */
        .modern-login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .codeigniter-example {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .codeigniter-example h2 {
            color: #2d3748;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }

        .code-section {
            margin-bottom: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .code-section h3 {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            margin: 0;
            padding: 16px 24px;
            font-size: 18px;
            font-weight: 600;
        }

        .code-section pre {
            margin: 0;
            padding: 24px;
            background: #f8f9fa;
            overflow-x: auto;
        }

        .code-section code {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 13px;
            line-height: 1.6;
            color: #2d3748;
        }
    </style>
</head>
<body>
    <div class="modern-login-page">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="logo-container">
                        <div class="logo-icon">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <h1 class="login-title">Sistema Contable</h1>
                    <p class="login-subtitle">Bienvenido de vuelta</p>
                </div>
                
                <div class="login-form-container">
                    <div id="error-alert" class="error-alert" style="display: none;">
                        <div class="error-icon">⚠️</div>
                        <span id="error-message"></span>
                    </div>
                    
                    <form id="loginForm" class="login-form">
                        <div class="form-group">
                            <label for="usuario" class="form-label">Usuario</label>
                            <div class="input-container">
                                <div class="input-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <input
                                    type="text"
                                    class="form-input"
                                    id="usuario"
                                    name="usuario"
                                    placeholder="Ingrese su usuario"
                                    required
                                />
                            </div>
                            <div class="field-error" id="usuario-error"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-container">
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <input
                                    type="password"
                                    class="form-input"
                                    id="password"
                                    name="password"
                                    placeholder="Ingrese su contraseña"
                                    required
                                />
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="field-error" id="password-error"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-container">
                                <input type="checkbox" id="recordar" name="recordar" value="1" />
                                <span class="checkmark"></span>
                                <span class="checkbox-label">Recordar mis datos</span>
                            </label>
                        </div>
                        
                        <button type="submit" class="submit-button" id="submitBtn">
                            <div class="button-content">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Iniciar Sesión</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').click(function() {
                const passwordField = $('#password');
                const icon = $(this).find('i');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Form submission
            $('#loginForm').submit(function(e) {
                e.preventDefault();
                
                const submitBtn = $('#submitBtn');
                const errorAlert = $('#error-alert');
                
                // Show loading state
                submitBtn.prop('disabled', true).html(
                    '<div class="loading-content">' +
                    '<div class="spinner"></div>' +
                    '<span>Iniciando sesión...</span>' +
                    '</div>'
                );
                
                $.ajax({
                    url: 'auth/login',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect;
                        } else {
                            errorAlert.find('#error-message').text(response.message);
                            errorAlert.show();
                        }
                    },
                    error: function() {
                        errorAlert.find('#error-message').text('Error de conexión. Intente nuevamente.');
                        errorAlert.show();
                    },
                    complete: function() {
                        // Reset button
                        submitBtn.prop('disabled', false).html(
                            '<div class="button-content">' +
                            '<i class="fas fa-sign-in-alt"></i>' +
                            '<span>Iniciar Sesión</span>' +
                            '</div>'
                        );
                    }
                });
            });
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

