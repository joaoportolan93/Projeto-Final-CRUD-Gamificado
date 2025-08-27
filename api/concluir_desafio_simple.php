<?php
// Endpoint para concluir desafios - Versão com arquivos JSON

include_once 'config_simple.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Método não permitido.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$usuarioId = $input['usuario_id'] ?? null;
$desafioId = $input['desafio_id'] ?? null;

if (!$usuarioId || !$desafioId) {
    http_response_code(400);
    echo json_encode(['message' => 'usuario_id e desafio_id são obrigatórios.']);
    exit;
}

$dataDir = __DIR__ . '/../data/';
$usuariosFile = $dataDir . 'usuarios.json';
$desafiosFile = $dataDir . 'desafios.json';
$concluidosFile = $dataDir . 'desafios_concluidos.json';
$badgesFile = $dataDir . 'badges.json';

// Ler dados
$usuarios = readData($usuariosFile);
$desafios = readData($desafiosFile);
$concluidos = readData($concluidosFile);
$badges = readData($badgesFile);

// Encontrar usuário e desafio
$usuario = null;
$desafio = null;

foreach ($usuarios as &$u) {
    if ($u['id'] == $usuarioId) {
        $usuario = &$u;
        break;
    }
}

foreach ($desafios as $d) {
    if ($d['id'] == $desafioId) {
        $desafio = $d;
        break;
    }
}

if (!$usuario || !$desafio) {
    http_response_code(404);
    echo json_encode(['message' => 'Usuário ou desafio não encontrado.']);
    exit;
}

// Verificar se já foi concluído
$jaConcluido = false;
foreach ($concluidos as $c) {
    if ($c['usuario_id'] == $usuarioId && $c['desafio_id'] == $desafioId) {
        $jaConcluido = true;
        break;
    }
}

if ($jaConcluido) {
    http_response_code(400);
    echo json_encode(['message' => 'Desafio já foi concluído.']);
    exit;
}

// Registrar conclusão
$concluidos[] = [
    'id' => generateId($concluidos),
    'usuario_id' => $usuarioId,
    'desafio_id' => $desafioId,
    'data_conclusao' => date('Y-m-d H:i:s')
];

// Atualizar pontos do usuário
$pontosGanhos = $desafio['pontos'];
$usuario['pontos_totais'] += $pontosGanhos;

// Atualizar streak (simplificado - incrementa sempre)
$usuario['streak_atual'] += 1;

// Salvar dados
writeData($concluidosFile, $concluidos);
writeData($usuariosFile, $usuarios);

// Verificar badges (simplificado)
$novosBadges = [];
$badgesUsuario = []; // Em uma versão completa, isso viria de uma tabela usuarios_badges

// Badge de primeiro desafio
if ($usuario['pontos_totais'] >= 1 && !in_array(1, $badgesUsuario)) {
    $novosBadges[] = $badges[0]; // Badge "Iniciante"
}

// Badge de streak
if ($usuario['streak_atual'] >= 5 && !in_array(2, $badgesUsuario)) {
    $novosBadges[] = $badges[1]; // Badge "Persistente"
}

// Preparar resposta
$feedback = [
    'pontos_ganhos' => $pontosGanhos,
    'novo_streak' => $usuario['streak_atual'],
    'novos_badges' => $novosBadges
];

echo json_encode($feedback);
?>
