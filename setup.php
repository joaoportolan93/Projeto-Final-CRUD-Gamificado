<?php
// Script de configuração e verificação do ambiente

echo "<h1>🔧 Configuração do Sistema de Desafios</h1>";

// Verificar se o PHP está funcionando
echo "<h2>✅ Verificação do PHP</h2>";
echo "Versão do PHP: " . phpversion() . "<br>";
echo "Extensões carregadas: " . implode(", ", get_loaded_extensions()) . "<br><br>";

// Verificar extensões necessárias
$extensoes_necessarias = ['pdo', 'pdo_mysql', 'json'];
foreach ($extensoes_necessarias as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext: OK<br>";
    } else {
        echo "❌ $ext: NÃO ENCONTRADA<br>";
    }
}
echo "<br>";

// Testar conexão com banco de dados
echo "<h2>🗄️ Teste de Conexão com Banco de Dados</h2>";

// Configurações do banco
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'gamificado_aprendizado';

try {
    // Tentar conectar sem especificar o banco
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexão com MySQL: OK<br>";
    
    // Verificar se o banco existe
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Banco de dados '$dbname': EXISTE<br>";
        
        // Conectar ao banco específico
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Verificar tabelas
        $stmt = $pdo->query("SHOW TABLES");
        $tabelas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "✅ Tabelas encontradas: " . implode(", ", $tabelas) . "<br>";
        
    } else {
        echo "❌ Banco de dados '$dbname': NÃO EXISTE<br>";
        echo "<h3>📋 Criando banco de dados...</h3>";
        
        // Ler e executar o arquivo SQL
        $sql = file_get_contents('database.sql');
        $pdo->exec($sql);
        echo "✅ Banco de dados criado com sucesso!<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Erro de conexão: " . $e->getMessage() . "<br>";
    echo "<h3>🔧 Instruções para configurar:</h3>";
    echo "<ol>";
    echo "<li>Instale o XAMPP ou Laragon (inclui MySQL)</li>";
    echo "<li>Inicie o serviço MySQL</li>";
    echo "<li>Execute este script novamente</li>";
    echo "</ol>";
}

// Testar APIs
echo "<h2>🌐 Teste das APIs</h2>";

$apis = [
    'desafios.php' => 'GET',
    'ranking.php' => 'GET',
    'concluir_desafio.php' => 'POST'
];

foreach ($apis as $api => $method) {
    $url = "http://localhost:8000/api/$api";
    $context = stream_context_create([
        'http' => [
            'method' => $method,
            'header' => 'Content-Type: application/json',
            'timeout' => 5
        ]
    ]);
    
    $result = @file_get_contents($url, false, $context);
    if ($result !== false) {
        echo "✅ $api: OK<br>";
    } else {
        echo "❌ $api: ERRO (servidor não está rodando?)<br>";
    }
}

echo "<br><h2>🚀 Próximos Passos:</h2>";
echo "<ol>";
echo "<li>Certifique-se de que o MySQL está rodando</li>";
echo "<li>Execute: <code>php -S localhost:8000</code> na pasta do projeto</li>";
echo "<li>Acesse: <a href='http://localhost:8000'>http://localhost:8000</a></li>";
echo "</ol>";
?>
