<?php
// Configuração alternativa usando arquivos JSON (sem banco de dados)

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Trata a requisição OPTIONS (pre-flight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Função para ler dados do arquivo JSON
function readData($filename) {
    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        return json_decode($data, true) ?: [];
    }
    return [];
}

// Função para escrever dados no arquivo JSON
function writeData($filename, $data) {
    return file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Inicializar arquivos de dados se não existirem
$dataDir = __DIR__ . '/../data/';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}

$files = [
    'desafios.json' => [
        [
            'id' => 1,
            'titulo' => 'Resolver 10 equações de 1º grau',
            'descricao' => 'Pratique suas habilidades em álgebra.',
            'area_conhecimento' => 'Matemática',
            'pontos' => 20
        ],
        [
            'id' => 2,
            'titulo' => 'Escrever uma redação de 300 palavras',
            'descricao' => 'Tema: O impacto da tecnologia na sociedade.',
            'area_conhecimento' => 'Linguagens',
            'pontos' => 30
        ],
        [
            'id' => 3,
            'titulo' => 'Criar uma função em Python',
            'descricao' => 'A função deve calcular o fatorial de um número.',
            'area_conhecimento' => 'Programação',
            'pontos' => 50
        ]
    ],
    'usuarios.json' => [
        [
            'id' => 1,
            'nome' => 'Usuário Teste',
            'email' => 'teste@exemplo.com',
            'pontos_totais' => 150,
            'streak_atual' => 5,
            'ultimo_login' => '2025-01-25'
        ]
    ],
    'desafios_concluidos.json' => [],
    'badges.json' => [
        [
            'id' => 1,
            'nome' => 'Iniciante',
            'descricao' => 'Concluiu seu primeiro desafio.',
            'icone_url' => 'icons/iniciante.png',
            'criterio_tipo' => 'pontos',
            'criterio_valor' => 1
        ],
        [
            'id' => 2,
            'nome' => 'Persistente',
            'descricao' => 'Manteve um streak de 5 dias de estudo.',
            'icone_url' => 'icons/persistente.png',
            'criterio_tipo' => 'streak',
            'criterio_valor' => 5
        ],
        [
            'id' => 3,
            'nome' => 'Matemático Júnior',
            'descricao' => 'Concluiu 1 desafio na área de Matemática.',
            'icone_url' => 'icons/matematico.png',
            'criterio_tipo' => 'desafios_area',
            'criterio_valor' => 1,
            'criterio_extra' => 'Matemática'
        ]
    ]
];

foreach ($files as $filename => $defaultData) {
    $filepath = $dataDir . $filename;
    if (!file_exists($filepath)) {
        writeData($filepath, $defaultData);
    }
}

// Função para gerar ID único
function generateId($data) {
    if (empty($data)) return 1;
    $maxId = max(array_column($data, 'id'));
    return $maxId + 1;
}
?>
