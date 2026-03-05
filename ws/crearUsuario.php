<?php
    require_once 'models/User.php';

    if (!empty($_POST)) {
        try {
            $nombre = $_POST['nombre'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $email = $_POST['email'] ?? '';
            $genero = $_POST['genero'] ?? '';

            $user = new User($nombre, $apellidos, $contrasena, $telefono, $email, $genero);

            $userJson = $user->toJson();

            $ficheroUsuarios = __DIR__ . '/usuario.txt';
            
            if (file_put_contents($ficheroUsuarios, $userJson . "\n", FILE_APPEND) === false) {
                throw new Exception('Error al escribir en el archivo de usuarios.');
            }

            echo $user->toJson();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'No se ha recibido información del formulario.';
    } 
?>