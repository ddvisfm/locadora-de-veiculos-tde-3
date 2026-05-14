<?php

class Conexao {
    private static $instance;

    public static function getConn() {
        if (!isset(self::$instance)) {
            // Padrão XAMPP:
            $host = 'localhost';
            $db   = 'locadora';
            $user = 'root';
            $pass = '';

            try {
                self::$instance = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);

                // Checagem rápida: se a tabela principal não existir, avisar como resolver.
                // Assim você não fica com "Fatal error: Table doesn't exist" sem orientação.
                self::$instance->query("SELECT 1 FROM clientes LIMIT 1");
            } catch (PDOException $e) {
                // Mensagem amigável para setup do banco
                $msg = $e->getMessage();
                if (strpos($msg, "Base table or view not found") !== false || strpos($msg, "doesn't exist") !== false) {
                    throw new Exception(
                        "Banco não inicializado. Rode o instalador: http://localhost/locadora-crud-php/install.php " .
                        "ou importe database/schema.sql no phpMyAdmin."
                    );
                }
                throw $e;
            }
        }
        return self::$instance;
    }
}
