<?php
    header('Content-Type: application/json');
    
    require_once __DIR__ . '/utils/singleton.php';

    try {
        $singleton = Singleton::getInstancia();
        $conexionBd = $singleton->getConexion();

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            // Obtener un usuario por ID
            $id = $_GET['id'];
            
            $sql = 'SELECT id, nombre, apellidos, telefono, email, sexo  FROM alumno WHERE id = :id';
            $query = $conexionBd->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            $query->closeCursor();

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario obtenido correctamente',
                    'data' => $resultado
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No se encontró ningún usuario con el id especificado',
                    'data' => null
                ]);
            }
        } else {
            // Obtener todos los usuarios
            $sql = 'SELECT id, nombre, apellidos, telefono, email, sexo FROM alumno';
            $query = $conexionBd->prepare($sql);
            $query->execute();
            $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
            $query->closeCursor();

            echo json_encode([
                'success' => true,
                'message' => 'Usuarios obtenidos correctamente',
                'data' => $resultado
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'data' => null
        ]);
    }
?>
