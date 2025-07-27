<?php 
include_once('Conexion.php');

class GaleriaServicio extends Conexion {

    function __construct() {}

    function nuevaObra($obra) {
        try {
            $query = "INSERT INTO obras(autor, titulo, imagen_path, descripcion) VALUES (?, ?, ?, ?)";
    
            $params = [$obra['autor'], $obra['titulo'], $obra['path'], $obra['descripcion']];
            return $this->transaction($query, $params);            
        } catch (Exception $e) {
            return false;
        }
    }

    function getTodasLasObras() {
        $query = "SELECT * FROM obras";

        try {
            return $this->select($query, []);
        } catch (Exception $e) {
            return [];
        }
    }

    function registrar($usuario) {
        $query = "INSERT INTO usuarios (nombres, email, password) VALUES (?, ?, ?)";

        $params = [$usuario['nombres'], 
                $usuario['email'], 
                password_hash($usuario['password'], PASSWORD_DEFAULT),
        ];
        
        try {
            return $this->beginTransaction($query, $params);
        } catch (Exception $e) {
            return false;
        }
    }

    public function login($correo, $contrasena) {

        $respuesta = ['exitosa' => false, 
                    'contenido' => ''];

        $query = "SELECT id, password, nombres FROM usuarios WHERE email = ?";

        try {
            $resultado = $this->select($query, [$correo]);

            if(count($resultado) == 0 || !password_verify($contrasena, $respuesta[0]['password'])) {
                $respuesta['contenido'] = "Credenciales incorrectas";
                return $respuesta;
            }

            $usuario = $resultado[0];
            
            $respuesta['exitosa'] = true;
            $respuesta['contenido'] = [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombres'],
            ];

            return $respuesta;
        } catch (Exception $e) {
            $respuesta['contenido'] = 'Ocurrió un error, vuelve a intentar';
            return $respuesta;
        }    
    }
}
?>