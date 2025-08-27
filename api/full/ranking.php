<?php
// Endpoint para o Ranking de Usuários - Versão completa (MySQL)

include_once '../config.php';

$orderBy = $_GET['orderBy'] ?? 'pontos_totais';
$allowedOrderBy = ['pontos_totais', 'streak_atual'];

if (!in_array($orderBy, $allowedOrderBy)) {
    $orderBy = 'pontos_totais';
}

$stmt = $pdo->query("SELECT id, nome, pontos_totais, streak_atual FROM usuarios ORDER BY $orderBy DESC LIMIT 100");
$ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($ranking);
?>


