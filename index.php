<?php 
include_once('class/Conexion.php');

$conexion = new Conexion();

$query = "SELECT * FROM obras";

$resultados = $conexion->select($query, []);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver obras</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #F8F8F8;
            margin: 20px;
        }
    
        h1 {
            text-align: center;
        }
    
        .galeria {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
    
        .obra {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgb(0, 0, 0, 0.1);
            width: 30px;
            padding: 15px;
        }
    
        .obra img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
    
        .obra h3, .obra p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Obras registradas</h1>
    <div class="galeria">
        <?php if (count($resultados) > 0) {
            foreach($resultados as $result) {
                $autor = $result['autor'];
                $titulo = $result['titulo'];
                $imagen = $result['imagen_path'];
                $descripcion = $result['descripcion'];
        ?>
        <div class="obra">
            <img src="<?php echo $imagen; ?>" alt="Obra">
            <h3><?php echo htmlspecialchars($titulo); ?></h3>
            <p><strong>Autor:</strong><?php echo htmlspecialchars($autor); ?></p>
            <p><?php echo htmlspecialchars($descripcion); ?></p>
        </div>
        <?php
            }
        } else {
        ?>
        <p>No hay obras registradas</p>
        <?php
        }
        ?>
    </div>
</body>
</html>