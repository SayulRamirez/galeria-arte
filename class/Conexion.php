<?php 
include_once('configuracion.php');
/**
 * Clase Conexion que extiende PDO para la gestión de base de datos MySQL.
 */
class Conexion extends PDO {

    /**
     * Constructor de la clase.
     * Establece la conexión a la base de datos usando PDO.
     * 
     * @throws Exception Si ocurre un error al conectar.
     */
    public function __construct() {
        $dsn = 'mysql:host=' . DB_HOST  . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $usuario = DB_USER;
        $password = DB_PASS;

        try {
            parent::__construct($dsn, $usuario, $password);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }

    /**
     * Ejecuta una consulta SELECT y retorna los resultados en un array asociativo.
     * 
     * @param string $query Consulta SQL con placeholders (?).
     * @param array $params Array de parámetros para la consulta.
     * @return array Resultados obtenidos o un array vacío si no hay coincidencias.
     * @throws Exception Si ocurre un error en la consulta.
     */
    public function select($query, $params = []) {
        try {
            $prepare = parent::prepare($query);
            $prepare->execute($params);
            return $prepare->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error en consulta: " . $e->getMessage());
        }
    }

    /**
     * Ejecuta una consulta dentro de una transacción (INSERT, UPDATE, DELETE).
     * 
     * @param string $query Consulta SQL con placeholders (?).
     * @param array $params Array de parámetros para la consulta.
     * @return bool True si la transacción se ejecuta correctamente, False si falla.
     * @throws Exception Si ocurre un error en la transacción.
     */
    public function transaction($query, $params = []) {
        try {
            parent::beginTransaction();
            $prepare = parent::prepare($query);

            if (!$prepare) {
                parent::rollBack();
                throw new Exception("Error al preparar la transacción");
            }

            if ($prepare->execute($params)) {
                parent::commit();
                return true;
            }

            parent::rollBack();
            return false;
        } catch (PDOException $e) {
            parent::rollBack();
            throw new Exception("Error en transacción: " . $e->getMessage());
        }
    }

    /**
     * Inserta un registro en la base de datos y retorna el ID generado.
     * 
     * @param string $query Consulta SQL con placeholders (?).
     * @param array $params Array de parámetros para la consulta.
     * @return int ID del último registro insertado, o 0 si falla.
     * @throws Exception Si ocurre un error en la inserción.
     */
    public function insert($query, $params = []) {
        try {
            parent::beginTransaction();
            $prepare = parent::prepare($query);

            if (!$prepare) {
                parent::rollBack();
                throw new Exception("Error al preparar la inserción");
            }

            if ($prepare->execute($params)) {
                $id = parent::lastInsertId();
                parent::commit();
                return (int) $id;
            }

            parent::rollBack();
            return 0;
        } catch (PDOException $e) {
            parent::rollBack();
            throw new Exception("Error en inserción: " . $e->getMessage());
        }
    }
}
?>
