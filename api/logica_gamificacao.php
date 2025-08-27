<?php
// Funções de Lógica de Gamificação

/**
 * Calcula o novo streak do usuário.
 * @param PDO $pdo Conexão com o banco.
 * @param int $usuario_id ID do usuário.
 * @return int O novo valor do streak.
 */
function calcularStreak(PDO $pdo, int $usuario_id): int {
    $stmt = $pdo->prepare("SELECT streak_atual, ultimo_login FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario || !$usuario['ultimo_login']) {
        return 1; // Primeiro login ou primeiro desafio
    }

    $hoje = new DateTime();
    $ultimoLogin = new DateTime($usuario['ultimo_login']);
    $diferenca = $hoje->diff($ultimoLogin)->days;

    if ($diferenca === 1) {
        // Sequência contínua
        return $usuario['streak_atual'] + 1;
    } elseif ($diferenca === 0) {
        // Já concluiu um desafio hoje, mantém o streak
        return $usuario['streak_atual'];
    } else {
        // Quebrou a sequência
        return 1;
    }
}

/**
 * Verifica e concede novos badges ao usuário.
 * @param PDO $pdo Conexão com o banco.
 * @param int $usuario_id ID do usuário.
 * @return array Lista de novos badges conquistados.
 */
function verificarBadges(PDO $pdo, int $usuario_id): array {
    // Pega os dados atuais do usuário e seus desafios concluídos
    $stmt = $pdo->prepare("SELECT pontos_totais, streak_atual FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT d.area_conhecimento, COUNT(dc.id) as total
        FROM desafios_concluidos dc
        JOIN desafios d ON dc.desafio_id = d.id
        WHERE dc.usuario_id = ?
        GROUP BY d.area_conhecimento
    ");
    $stmt->execute([$usuario_id]);
    $desafiosPorArea = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Pega todos os badges que o usuário ainda não tem
    $stmt = $pdo->prepare("
        SELECT * FROM badges 
        WHERE id NOT IN (SELECT badge_id FROM usuarios_badges WHERE usuario_id = ?)
    ");
    $stmt->execute([$usuario_id]);
    $badgesParaVerificar = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $novosBadgesConquistados = [];

    foreach ($badgesParaVerificar as $badge) {
        $conquistou = false;
        switch ($badge['criterio_tipo']) {
            case 'pontos':
                if ($usuario['pontos_totais'] >= $badge['criterio_valor']) {
                    $conquistou = true;
                }
                break;
            case 'streak':
                if ($usuario['streak_atual'] >= $badge['criterio_valor']) {
                    $conquistou = true;
                }
                break;
            case 'desafios_area':
                $area = $badge['criterio_extra'];
                $totalNaArea = $desafiosPorArea[$area] ?? 0;
                if ($totalNaArea >= $badge['criterio_valor']) {
                    $conquistou = true;
                }
                break;
        }

        if ($conquistou) {
            // Concede o badge ao usuário
            $sql = "INSERT INTO usuarios_badges (usuario_id, badge_id, data_conquista) VALUES (?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario_id, $badge['id']]);
            $novosBadgesConquistados[] = $badge;
        }
    }

    return $novosBadgesConquistados;
}
?>

