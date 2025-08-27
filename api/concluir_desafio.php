<?php
// Endpoint para Concluir um Desafio

include_once 'config.php';
include_once 'logica_gamificacao.php';

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

// --- Início da Transação ---
$pdo->beginTransaction();

try {
    // Verificar se o desafio já foi concluído hoje pelo usuário
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

    // 1. Buscar os pontos do desafio
    $stmt = $pdo->prepare("SELECT pontos FROM desafios WHERE id = ?");
    $stmt->execute([$desafio_id]);
    $desafio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$desafio) {
        throw new Exception("Desafio não encontrado.");
    }
    $pontos_ganhos = $desafio['pontos'];

    // 2. Registrar que o desafio foi concluído
    $stmt = $pdo->prepare("INSERT INTO desafios_concluidos (usuario_id, desafio_id, data_conclusao) VALUES (?, ?, NOW())");
    $stmt->execute([$usuario_id, $desafio_id]);

    // 3. Calcular o novo streak
    $novo_streak = calcularStreak($pdo, $usuario_id);

    // 4. Atualizar os pontos, o streak e o último login do usuário
    $stmt = $pdo->prepare(
        "UPDATE usuarios SET 
            pontos_totais = pontos_totais + ?, 
            streak_atual = ?, 
            ultimo_login = CURDATE() 
        WHERE id = ?"
    );
    $stmt->execute([$pontos_ganhos, $novo_streak, $usuario_id]);

    // 5. Verificar e conceder badges (após a atualização dos dados do usuário)
    $novos_badges = verificarBadges($pdo, $usuario_id);

    // Se tudo deu certo, confirma a transação
    $pdo->commit();

    // Resposta de sucesso
    http_response_code(200);
    echo json_encode([
        'message' => 'Desafio concluído com sucesso!',
        'pontos_ganhos' => $pontos_ganhos,
        'novo_streak' => $novo_streak,
        'novos_badges' => $novos_badges
    ]);

} catch (Exception $e) {
    // Se algo deu errado, reverte a transação
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao processar o desafio: ' . $e->getMessage()]);
}
?>

