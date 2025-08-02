<?php
session_start();

if (isset($_SESSION['nombre'])) {
  header('Location: admin.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Metal Galery - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container-fluid d-flex justify-content-center mt-2 mb-4">
        <h1>The Metal Galery</h1>
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form id="formLogin" class="needs-validation border py-3 px-5 shadow-sm rounded-2" novalidate>
                    <h3 class="mb-4 text-center">Iniciar sesión</h3>
                    <div class="mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Correo electrónico" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required minlength="6">
                    </div>
                    <button id="btnLogin" type="button" class="btn btn-primary d-block mx-auto px-4 mb-3">Entrar</button>
                    <div style="min-height: 35px;">
                        <div id="alert" class="alert alert-danger d-flex align-items-center p-1 d-none" role="alert">
                            <i class="bi bi-patch-exclamation-fill me-2"></i>
                            <div id="alertMensaje"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const formLogin = document.getElementById('formLogin');
        const alertMensaje = document.getElementById('alertMensaje');
        const alert = document.getElementById('alert');

        document.getElementById('btnLogin').addEventListener('click', () => {

            alert.classList.add('d-none');

            if (!formLogin.checkValidity()) {
                formLogin.classList.add('was-validated');
                return;
            };

            formLogin.classList.remove('was-validated');

            const formData = new FormData();

            formData.append('accion', 'login');
            formData.append('email', document.getElementById('email').value);
            formData.append('password', document.getElementById('password').value);

            fetch('class/GaleriaController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    console.log(data.status);
                    if(data.status) {
                        window.location.href = 'admin.php';
                        return;
                    }

                    alert.classList.remove('d-none');
                    alertMensaje.innerHTML = data.mensaje;
                })
                .catch(err => {
                    alertMensaje.innerHTML = 'Ocurrió un error inesperado.';
                });
        });
    </script>
</body>

</html>