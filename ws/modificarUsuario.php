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

        // Verificar si el usuario existe
        $sqlCheck = 'SELECT * FROM alumno WHERE id = :id';
        $queryCheck = $conexionBd->prepare($sqlCheck);
        $queryCheck->bindValue(':id', $id, PDO::PARAM_INT);
        $queryCheck->execute();
        $usuarioExistente = $queryCheck->fetch(PDO::FETCH_ASSOC);
        $queryCheck->closeCursor();

        if (!$usuarioExistente) {
            echo json_encode([
                'success' => false,
                'message' => 'No se encontró ningún usuario con el id especificado',
                'data' => null
            ]);
            exit;
        }

        // Construir la consulta UPDATE dinámicamente solo con los campos enviados
        $campos = [];
        $valores = [];

        if (isset($_POST['nombre']) && !empty($_POST['nombre'])) {
            $campos[] = 'nombre = :nombre';
            $valores[':nombre'] = $_POST['nombre'];
        }

        if (isset($_POST['apellidos']) && !empty($_POST['apellidos'])) {
            $campos[] = 'apellidos = :apellidos';
            $valores[':apellidos'] = $_POST['apellidos'];
        }

        if (isset($_POST['contrasena']) && !empty($_POST['contrasena'])) {
            $campos[] = 'password = :contrasena';
            $valores[':contrasena'] = $_POST['contrasena'];
        }

        if (isset($_POST['telefono']) && !empty($_POST['telefono'])) {
            $campos[] = 'telefono = :telefono';
            $valores[':telefono'] = $_POST['telefono'];
        }

        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $campos[] = 'email = :email';
            $valores[':email'] = $_POST['email'];
        }

        if (isset($_POST['genero']) && !empty($_POST['genero'])) {
            $campos[] = 'sexo = :genero';
            $valores[':genero'] = $_POST['genero'];
        }

        if (empty($campos)) {
            echo json_encode([
                'success' => false,
                'message' => 'No se han proporcionado datos para modificar',
                'data' => null
            ]);
            exit;
        }

        // UPDATE solo con los campos enviados
        $sql = 'UPDATE alumno SET ' . implode(', ', $campos) . ' WHERE id = :id';
        $query = $conexionBd->prepare($sql);
        foreach ($valores as $param => $valor) {
            $query->bindValue($param, $valor, PDO::PARAM_STR);
        }
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $query->closeCursor();

        // Obtener el usuario modificado
        $sqlSelect = 'SELECT * FROM alumno WHERE id = :id';
        $querySelect = $conexionBd->prepare($sqlSelect);
        $querySelect->bindValue(':id', $id, PDO::PARAM_INT);
        $querySelect->execute();
        $usuarioModificado = $querySelect->fetch(PDO::FETCH_ASSOC);
        $querySelect->closeCursor();

        echo json_encode([
            'success' => true,
            'message' => 'Usuario modificado correctamente',
            'data' => $usuarioModificado
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'data' => null
        ]);
    }
?>