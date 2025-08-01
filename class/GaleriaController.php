<?php
include_once('GaleriaServicio.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $galeriaServicio = new GaleriaServicio();
    $resultado = $galeriaServicio->getTodasLasObras();

    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit();
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

            $respuesta = ['status' => $resultado, 
                        'mensaje' => $resultado ? 'Se agrego una nueva obra.' : 'Ocurrió un error al agregar la obra.'
            ];

            echo json_encode($respuesta);
            break;
        case 'registrar':
            $resultado = $galeriaServicio->registrar($_POST['usuario']);
            echo $resultado ? 'Se agrego un nuevo usuario' : 'Ocurrió un error al agregar al usuario';
            break;
        case 'login':
            $respuesta = $galeriaServicio->login($_POST['email'], $_POST['password']);
            
            header('Content-Type: application/json');
            if($respuesta['exitosa']) {
                session_start();
                $_SESSION['nombre'] = $respuesta['contenido']['nombre'];
                
                echo json_encode(['status' => true]);
                exit();
            }
            
            echo json_encode(['status' => false, 
                            'mensaje' => $respuesta['contenido']]);
            break;
    }
}
