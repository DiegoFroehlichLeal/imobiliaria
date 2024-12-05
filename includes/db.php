<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Carrega as variáveis de ambiente do arquivo .env
$dotenv = Dotenv::createImmutable(__DIR__ );
$dotenv->load();

// Acessa as variáveis de ambiente corretamente
$host = $_ENV['DB_host'];
$user = $_ENV['DB_user'];
$pass = $_ENV['DB_pass'];
$db   = $_ENV['DB_db'];

// Inicia a conexão com o banco de dados
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
