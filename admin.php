<?php 
session_start();

if (!isset($_SESSION['nombre'])) {
  header('Location: index.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>The Metal Galery - Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
  <div class="container-fluid d-flex justify-content-between align-items-center mt-2 mb-4">
    <h1>The Metal Galery</h1>
  </div>
  <div class="container-fluid d-flex justify-content-between align-items-center mt-2 mb-4">
    <h3>Bienvenido <?php echo $_SESSION['nombre']; ?></h3>
    <a href="class/logout.php" class="btn btn-danger">Salir</a>
  </div>
  <div class="container">
    <h4>Agregar una nueva obra</h4>
    <form id="formNuevaObra" action="class/GaleriaController.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate onsubmit="event.preventDefault()">
      <div class="row gap-3">
        <div class="col-sm-5">
          <input type="text" id="autor" class="form-control" name="autor" placeholder="Nombre del autor" required>
        </div>
        <div class="col-sm-5">
          <input type="text" id="titulo" class="form-control" name="titulo" placeholder="Titulo de la obra" required>
        </div>
      </div>
      <div class="row mt-3 mb-3">
        <div class="col-md-7">
          <label for="imagen" class="form-label">Imagen:</label>
          <input type="file" id="imagen" class="form-control" name="imagen" accept="image/*" required>
        </div>
      </div>
      <div class="row gap-3">
        <div class="col-md-7">
          <textarea id="descripcion" class="form-control" name="descripcion" rows="5" cols="40" placeholder="Descripción" required></textarea>
        </div>
        <div class="col-md-3 align-self-end">
          <button id="btnGuardrObra" type="button" class="btn btn-primary">Guardar obra</button>
        </div>
      </div>
    </form>
  </div>
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header gap-1">
        <i class="bi bi-hand-thumbs-up-fill" style="font-size: var(--bs-toast-font-size);"></i>
        <i class="bi bi-hand-thumbs-down-fill d-none" style="font-size: var(--bs-toast-font-size);"></i>
        <strong class="me-auto">The Metal Galery </strong>
        <small id="horaNotificacion"></small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body" id="toastBody"></div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const formObra = document.getElementById('formNuevaObra');
    document.getElementById('btnGuardrObra').addEventListener('click', () => {

      if (!formObra.checkValidity()) {
        formObra.classList.add('was-validated');
        return;
      };

      formObra.classList.remove('was-validated');

      const formData = new FormData();

      formData.append('accion', 'nuevaObra');
      formData.append('imagen', document.getElementById('imagen').files[0]);
      formData.append('autor', document.getElementById('autor').value);
      formData.append('titulo', document.getElementById('titulo').value);
      formData.append('descripcion', document.getElementById('descripcion').value);

      fetch('class/GaleriaController.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        mostrarToast(data.mensaje, !data.status);
      })
      .catch(err => {
        mostrarToast('Error al guardar la obra.', true);
      });
    });

    const toastLiveExample = document.getElementById('liveToast');
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
    const horaNotificacion = document.getElementById('horaNotificacion');
    const toastBody = document.getElementById('toastBody');
    const toastHeader = document.querySelector('.toast-header');

    function mostrarToast(mensaje, status) {
      horaNotificacion.innerHTML = tiempoActual();
      toastBody.innerHTML = mensaje;
      
      const styleToast = getToastStyles(status);
      toastHeader.classList.remove(styleToast.remove);
      toastHeader.classList.add(styleToast.add);

      const icon = toastHeader.querySelector('.bi');
      icon.classList.remove(styleToast.iconRemove);
      icon.classList.add(styleToast.iconAdd);

      toastBootstrap.show();
    }

    function getToastStyles(status) {
      return {
        remove: status ? 'text-success' : 'text-danger',
        add: status ? 'text-danger' : 'text-success',
        iconRemove: status ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-down-fill',
        iconAdd: status ? 'bi-hand-thumbs-down-fill' : 'bi-hand-thumbs-up-fill',
      }
    }

    function tiempoActual() {
      const fecha = new Date();
      
      const hora = parserTiempo(fecha.getHours());
      const minutos = parserTiempo(fecha.getMinutes());
      const dia = parserTiempo(fecha.getDate());
      const mes = parserTiempo((fecha.getMonth() + 1));

      return `${hora}:${minutos} ${dia}-${mes}`;
    }

    function parserTiempo(tiempo) {
      return tiempo.toString().padStart(2, '0');
    }
  </script>
</body>

</html>