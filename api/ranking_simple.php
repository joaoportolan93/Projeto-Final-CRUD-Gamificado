<?php
// Endpoint para Ranking - Versão com arquivos JSON

include_once 'config_simple.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET') {
    http_response_code(405);
    echo json_encode(['message' => 'Método não permitido.']);
    exit;
}

$dataDir = __DIR__ . '/../data/';
$usuariosFile = $dataDir . 'usuarios.json';

$usuarios = readData($usuariosFile);

// Ordenar por pontos ou streak
$orderBy = $_GET['orderBy'] ?? 'pontos_totais';
$validOrderBy = ['pontos_totais', 'streak_atual'];

if (!in_array($orderBy, $validOrderBy)) {
    $orderBy = 'pontos_totais';
}

// Ordenar usuários
usort($usuarios, function($a, $b) use ($orderBy) {
    return $b[$orderBy] - $a[$orderBy]; // Ordem decrescente
});

// Retornar apenas os campos necessários para o ranking
$ranking = array_map(function($usuario) {
    return [
        'id' => $usuario['id'],
        'nome' => $usuario['nome'],
        'pontos_totais' => $usuario['pontos_totais'],
        'streak_atual' => $usuario['streak_atual']
    ];
}, $usuarios);

echo json_encode($ranking);
?>
