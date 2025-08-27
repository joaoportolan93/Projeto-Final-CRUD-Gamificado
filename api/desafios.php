<?php
// Endpoint para gerenciar Desafios (CRUD)

include_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // Se um ID for passado na URL (ex: /desafios.php?id=1), busca um desafio específico
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM desafios WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            // Senão, busca todos os desafios
            $stmt = $pdo->query("SELECT * FROM desafios ORDER BY area_conhecimento, pontos");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case 'POST':
        // Cria um novo desafio
        $sql = "INSERT INTO desafios (titulo, descricao, area_conhecimento, pontos) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$input['titulo'], $input['descricao'], $input['area_conhecimento'], $input['pontos']])) {
            http_response_code(201); // Created
            echo json_encode(['message' => 'Desafio criado com sucesso.', 'id' => $pdo->lastInsertId()]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Erro ao criar desafio.']);
        }
        break;

    case 'PUT':
        // Atualiza um desafio existente
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'ID do desafio não fornecido.']);
            exit;
        }
        $sql = "UPDATE desafios SET titulo = ?, descricao = ?, area_conhecimento = ?, pontos = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$input['titulo'], $input['descricao'], $input['area_conhecimento'], $input['pontos'], $id])) {
            http_response_code(200); // OK
            echo json_encode(['message' => 'Desafio atualizado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Erro ao atualizar desafio.']);
        }
        break;

    case 'DELETE':
        // Deleta um desafio
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
        http_response_code(405); // Method Not Allowed
        echo json_encode(['message' => 'Método não permitido.']);
        break;
}
?>

