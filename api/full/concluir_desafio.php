<?php
// Endpoint para Concluir um Desafio - Versão completa (MySQL)

include_once '../config.php';
include_once '../logica_gamificacao.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Método não permitido.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$usuario_id = $input['usuario_id'] ?? null;
$desafio_id = $input['desafio_id'] ?? null;

if (!$usuario_id || !$desafio_id) {
    http_response_code(400);
    echo json_encode(['message' => 'ID do usuário e do desafio são obrigatórios.']);
    exit;
}

$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total 
        FROM desafios_concluidos 
        WHERE usuario_id = ? AND desafio_id = ? AND DATE(data_conclusao) = CURDATE()
    ");
    $stmt->execute([$usuario_id, $desafio_id]);
    $jaConcluidoHoje = $stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0;

    if ($jaConcluidoHoje) {
        throw new Exception("Você já concluiu este desafio hoje!");
    }

    $stmt = $pdo->prepare("SELECT pontos FROM desafios WHERE id = ?");
    $stmt->execute([$desafio_id]);
    $desafio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$desafio) {
        throw new Exception("Desafio não encontrado.");
    }
    $pontos_ganhos = $desafio['pontos'];

    $stmt = $pdo->prepare("INSERT INTO desafios_concluidos (usuario_id, desafio_id, data_conclusao) VALUES (?, ?, NOW())");
    $stmt->execute([$usuario_id, $desafio_id]);

    $novo_streak = calcularStreak($pdo, $usuario_id);

    $stmt = $pdo->prepare(
        "UPDATE usuarios SET 
            pontos_totais = pontos_totais + ?, 
            streak_atual = ?, 
            ultimo_login = CURDATE() 
        WHERE id = ?"
    );
    $stmt->execute([$pontos_ganhos, $novo_streak, $usuario_id]);

    $novos_badges = verificarBadges($pdo, $usuario_id);

    $pdo->commit();

    http_response_code(200);
    echo json_encode([
        'message' => 'Desafio concluído com sucesso!',
        'pontos_ganhos' => $pontos_ganhos,
        'novo_streak' => $novo_streak,
        'novos_badges' => $novos_badges
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao processar o desafio: ' . $e->getMessage()]);
}
?>


