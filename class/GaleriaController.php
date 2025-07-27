<?php
include_once('GaleriaServicio.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET) {

    $galeriaServicio = new GaleriaServicio();
    $resultado = $galeriaServicio->getTodasLasObras();

    header('Content-Type: application/json');
    return json_encode($resultado);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {

    $galeriaServicio = new GaleriaServicio();

    switch ($_POST['accion']) {
        case 'nuevaObra':
            if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] != UPLOAD_ERR_OK) {
                echo 'Ocurrió un error al subir el archivo';
                exit();
            }

            $imagen = $_FILES['imagen'];
            $nombreArchivo = $imagen['name'];
            $tipoArchivo = $imagen['type'];
            $rutaTemporal = $imagen['tmp_name'];

            $relativa = "../uploads/";

            $rutaDestino = $relativa . basename($nombreArchivo);

            if (!move_uploaded_file($rutaTemporal, $rutaDestino)) {
                echo 'Ocurrió un error al subir el archivo';
                exit();
            }

            $obra = ['autor' => $_POST['autor'],
                    'titulo' => $_POST['titulo'],
                    'path' => 'uploads/'.basename($nombreArchivo),
                    'descripcion' => $_POST['descripcion']
            ];

            $resultado = $galeriaServicio->nuevaObra($obra);

            echo $resultado ? 'Se agrego una nueva obra.' : 'Ocurrió un error al agregar la obra.';
            break;
        case 'registrar':
            $resultado = $galeriaServicio->registrar($_POST['usuario']);
            echo $resultado ? 'Se agrego un nuevo usuario' : 'Ocurrió un error al agregar al usuario';
            break;
        case 'login':
            $respuesta = $galeriaServicio->login($_POST['email'], $_POST['password']);

            if($respuesta['exitosa']) {
                session_start();
                $_SESSION['nombre'] = $respuesta['contenido']['nombre'];
                header('Location: ../admin.php');
                exit();
            }

            echo $respuesta['contenido'];
            break;
    }
}
