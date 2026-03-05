<?php
    header('Content-Type: application/json');
    
    require_once __DIR__ . '/utils/singleton.php';

    try {
        if (empty($_POST)) {
            echo json_encode([
                'success' => false,
                'message' => 'No se ha recibido información del formulario',
                'data' => null
            ]);
            exit;
        }

        $nombre = $_POST['nombre'] ?? '';
        $apellidos = $_POST['apellidos'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $email = $_POST['email'] ?? '';
        $genero = $_POST['genero'] ?? '';

        // Validar campos requeridos
        if (empty($nombre) || empty($email) || empty($contrasena)) {
            echo json_encode([
                'success' => false,
                'message' => 'Los campos nombre, email y contraseña son obligatorios',
                'data' => null
            ]);
            exit;
        }

        $singleton = Singleton::getInstancia();
        $conexionBd = $singleton->getConexion();

        // Insertar el usuario en la base de datos
        $sql = 'INSERT INTO alumno (nombre, apellidos, password, telefono, email, sexo) 
                VALUES (:nombre, :apellidos, :contrasena, :telefono, :email, :genero)';
        
        $query = $conexionBd->prepare($sql);
        $query->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $query->bindValue(':apellidos', $apellidos, PDO::PARAM_STR);
        $query->bindValue(':contrasena', $contrasena, PDO::PARAM_STR);
        $query->bindValue(':telefono', $telefono, PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->bindValue(':genero', $genero, PDO::PARAM_STR);
        $query->execute();
        
        $idInsertado = $conexionBd->lastInsertId();
        $query->closeCursor();

        // Obtener el usuario creado
        $sqlSelect = 'SELECT * FROM alumno WHERE id = :id';
        $querySelect = $conexionBd->prepare($sqlSelect);
        $querySelect->bindValue(':id', $idInsertado, PDO::PARAM_INT);
        $querySelect->execute();
        $usuarioCreado = $querySelect->fetch(PDO::FETCH_ASSOC);
        $querySelect->closeCursor();

        echo json_encode([
            'success' => true,
            'message' => 'Usuario creado correctamente',
            'data' => $usuarioCreado
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'data' => null
        ]);
    }
?>