<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Subir Obra</title>
</head>
<body>
  <h2>Formulario de Obra</h2>
  <form action="class/GaleriaController.php" method="POST" enctype="multipart/form-data">
    <input type="text" id="accion" name="accion" value="nuevaObra" hidden>
    <label for="autor">Autor:</label><br>
    <input type="text" id="autor" name="autor" required><br><br>

    <label for="titulo">Título de la obra:</label><br>
    <input type="text" id="titulo" name="titulo" required><br><br>

    <label for="imagen">Imagen:</label><br>
    <input type="file" id="imagen" name="imagen" accept="image/*" required><br><br>

    <label for="descripcion">Descripción:</label><br>
    <textarea id="descripcion" name="descripcion" rows="5" cols="40" required></textarea><br><br>

    <input type="submit" value="Guardar Obra">
  </form>
</body>
</html>
