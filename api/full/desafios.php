<?php
// Endpoint para gerenciar Desafios (CRUD) - Versão completa (MySQL)

include_once '../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM desafios WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM desafios ORDER BY area_conhecimento, pontos");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case 'POST':
        $sql = "INSERT INTO desafios (titulo, descricao, area_conhecimento, pontos) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$input['titulo'], $input['descricao'], $input['area_conhecimento'], $input['pontos']])) {
            http_response_code(201);
            echo json_encode(['message' => 'Desafio criado com sucesso.', 'id' => $pdo->lastInsertId()]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Erro ao criar desafio.']);
        }
        break;

    case 'PUT':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'ID do desafio não fornecido.']);
            exit;
        }
        $sql = "UPDATE desafios SET titulo = ?, descricao = ?, area_conhecimento = ?, pontos = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$input['titulo'], $input['descricao'], $input['area_conhecimento'], $input['pontos'], $id])) {
            http_response_code(200);
            echo json_encode(['message' => 'Desafio atualizado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Erro ao atualizar desafio.']);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'ID do desafio não fornecido.']);
            exit;
        }
        $sql = "DELETE FROM desafios WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$id])) {
            http_response_code(200);
            echo json_encode(['message' => 'Desafio deletado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Erro ao deletar desafio.']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método não permitido.']);
        break;
}
?>


