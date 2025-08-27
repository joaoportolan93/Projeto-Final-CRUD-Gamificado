<?php
// Arquivo de Configuração do Banco de Dados

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *"); // Permite acesso de qualquer origem (para desenvolvimento)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Trata a requisição OPTIONS (pre-flight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Usuário padrão do XAMPP/Laragon
define('DB_PASS', '');     // Senha padrão do XAMPP/Laragon
define('DB_NAME', 'gamificado_aprendizado');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERRO: Não foi possível conectar. " . $e->getMessage());
}
?>

