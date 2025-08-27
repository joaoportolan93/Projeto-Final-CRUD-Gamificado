<?php
// Endpoint para gerenciar Desafios (CRUD) - Versão com arquivos JSON

include_once 'config_simple.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
$dataDir = __DIR__ . '/../data/';
$desafiosFile = $dataDir . 'desafios.json';

switch ($method) {
    case 'GET':
        // Se um ID for passado na URL (ex: /desafios.php?id=1), busca um desafio específico
        if (isset($_GET['id'])) {
            $desafios = readData($desafiosFile);
            $id = (int)$_GET['id'];
            $desafio = null;
            
            foreach ($desafios as $d) {
                if ($d['id'] == $id) {
                    $desafio = $d;
                    break;
                }
            }
            
            if ($desafio) {
                echo json_encode($desafio);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Desafio não encontrado.']);
            }
        } else {
            // Senão, busca todos os desafios
            $desafios = readData($desafiosFile);
            // Ordenar por área de conhecimento e pontos
            usort($desafios, function($a, $b) {
                if ($a['area_conhecimento'] !== $b['area_conhecimento']) {
                    return strcmp($a['area_conhecimento'], $b['area_conhecimento']);
                }
                return $a['pontos'] - $b['pontos'];
            });
            echo json_encode($desafios);
        }
        break;

    case 'POST':
        // Cria um novo desafio
        $desafios = readData($desafiosFile);
        $novoDesafio = [
            'id' => generateId($desafios),
            'titulo' => $input['titulo'],
            'descricao' => $input['descricao'],
            'area_conhecimento' => $input['area_conhecimento'],
            'pontos' => (int)$input['pontos']
        ];
        
        $desafios[] = $novoDesafio;
        
        if (writeData($desafiosFile, $desafios)) {
            http_response_code(201); // Created
            echo json_encode(['message' => 'Desafio criado com sucesso.', 'id' => $novoDesafio['id']]);
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
        
        $desafios = readData($desafiosFile);
        $encontrado = false;
        
        foreach ($desafios as &$desafio) {
            if ($desafio['id'] == $id) {
                $desafio['titulo'] = $input['titulo'];
                $desafio['descricao'] = $input['descricao'];
                $desafio['area_conhecimento'] = $input['area_conhecimento'];
                $desafio['pontos'] = (int)$input['pontos'];
                $encontrado = true;
                break;
            }
        }
        
        if ($encontrado && writeData($desafiosFile, $desafios)) {
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
        
        $desafios = readData($desafiosFile);
        $novosDesafios = [];
        $encontrado = false;
        
        foreach ($desafios as $desafio) {
            if ($desafio['id'] != $id) {
                $novosDesafios[] = $desafio;
            } else {
                $encontrado = true;
            }
        }
        
        if ($encontrado && writeData($desafiosFile, $novosDesafios)) {
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
