<?php
    header('Content-Type: application/json');
    
    require_once __DIR__ . '/utils/singleton.php';

    try {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No se ha proporcionado un id válido',
                'data' => null
            ]);
            exit;
        }

        $id = $_GET['id'];
        $singleton = Singleton::getInstancia();
        $conexionBd = $singleton->getConexion();

        // Primero obtener los datos del usuario antes de eliminarlo
        $sqlSelect = 'SELECT * FROM alumno WHERE id = :id';
        $querySelect = $conexionBd->prepare($sqlSelect);
        $querySelect->bindValue(':id', $id, PDO::PARAM_INT);
        $querySelect->execute();
        $usuarioEliminado = $querySelect->fetch(PDO::FETCH_ASSOC);
        $querySelect->closeCursor();

        if (!$usuarioEliminado) {
            echo json_encode([
                'success' => false,
                'message' => 'No se encontró ningún usuario con el id especificado',
                'data' => null
            ]);
            exit;
        }

        // Eliminar el usuario
        $sqlDelete = 'DELETE FROM alumno WHERE id = :id';
        $queryDelete = $conexionBd->prepare($sqlDelete);
        $queryDelete->bindValue(':id', $id, PDO::PARAM_INT);
        $queryDelete->execute();
        $queryDelete->closeCursor();

        echo json_encode([
            'success' => true,
            'message' => 'Usuario eliminado correctamente',
            'data' => $usuarioEliminado
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'data' => null
        ]);
    }
?>
