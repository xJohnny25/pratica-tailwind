<?php
    require_once __DIR__ . '/../interfaces/IToJson.php';


    class User implements IToJson{
        private $nombre;
        private $apellidos;
        private $contrasena;
        private $telefono;
        private $email;
        private $genero;

        public function __construct($nombre, $apellidos, $contrasena, $telefono, $email, $genero) {
            $this->nombre = $nombre;
            $this->apellidos = $apellidos;
            $this->contrasena = $contrasena;
            $this->telefono = $telefono;
            $this->email = $email;
            $this->genero = $genero;
        }

        public function getNombre() {
            return $this->nombre;
        }

        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }

        public function getApellidos() {
            return $this->apellidos;
        }

        public function setApellidos($apellidos) {
            $this->apellidos = $apellidos;
        }
        
        public function getContrasena() {
            return $this->contrasena;
        }
        
        public function setContrasena($contrasena) {
            $this->contrasena = $contrasena;
        }

        public function getTelefono() {
            return $this->telefono;
        }

        public function setTelefono($telefono) {
            $this->telefono = $telefono;
        }
        
        public function getEmail() {
            return $this->email;
        }
        
        public function setEmail($email) {
            $this->email = $email;
        }

        public function getGenero() {
            return $this->genero;
        }

        public function setGenero($genero) {
            $this->genero = $genero;
        }

        public function toJson() {
            return json_encode([
                "nombre" => $this->nombre,
                "apellidos" => $this->apellidos,
                "contrasena" => $this->contrasena,
                "telefono" => $this->telefono,
                "email" => $this->email,
                "genero" => $this->genero,
            ]);
        }
    }
?>