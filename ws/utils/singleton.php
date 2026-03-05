<?php
    class Singleton {
        private static $instancia = null;
        private $conexionBd;

        private function __construct() {
            try {
                $this->conexionBd = new PDO('mysql:host=localhost;dbname=colegio', 'root', '');
                $this->conexionBd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                throw new Exception('Error al conectar con la base de datos: ' . $e->getMessage());
            }
        }

        public static function getInstancia() {
            if (self::$instancia === null) {
                self::$instancia = new self();
            }
            return self::$instancia;
        }

        public function getConexion() {
            return $this->conexionBd;
        }
    }
?>

