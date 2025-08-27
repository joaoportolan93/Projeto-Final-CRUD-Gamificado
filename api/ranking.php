<?php
// Endpoint para o Ranking de Usuários

include_once 'config.php';

$orderBy = $_GET['orderBy'] ?? 'pontos_totais'; // Padrão é ordenar por pontos
$allowedOrderBy = ['pontos_totais', 'streak_atual'];

if (!in_array($orderBy, $allowedOrderBy)) {
    $orderBy = 'pontos_totais'; // Garante que o campo de ordenação é válido
}

$stmt = $pdo->query("SELECT id, nome, pontos_totais, streak_atual FROM usuarios ORDER BY $orderBy DESC LIMIT 100");
$ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($ranking);
?>

