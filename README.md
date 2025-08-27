# 🎯 Desafio de Aprendizado - Sistema CRUD Gamificado

Uma aplicação web que gamifica o processo de aprendizado com desafios, pontos, streaks e badges. Backend em PHP/MySQL e frontend em HTML/CSS/JS.

## ✨ Funcionalidades

### 🎮 Sistema de Gamificação
- **Pontos**: Ganhe pontos ao concluir desafios
- **Streaks**: Mantenha sequência diária de estudos
- **Badges**: Conquiste medalhas por diferentes critérios
- **Ranking**: Competição entre usuários

### 📚 Gerenciamento de Desafios
- **CRUD Completo**: Criar, visualizar, editar e deletar desafios
- **Categorização**: Organize desafios por área de conhecimento
- **Sistema de Pontos**: Cada desafio tem uma pontuação específica

### 🏆 Sistema de Conquistas
- **Badge Iniciante**: Primeiro desafio concluído
- **Badge Persistente**: Streak de 5 dias
- **Badge Matemático Júnior**: Desafio de matemática concluído

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 7.4+ com PDO
- **Banco de Dados**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (jQuery)
- **Ícones**: Font Awesome 5.15.4
- **Design**: CSS Grid, Flexbox, Gradientes

## 📋 Pré-requisitos

- **XAMPP** ou **Laragon** (Apache + MySQL + PHP)
- Navegador web moderno

## ⚡ Rodar em 2 minutos (resumo)

1. Crie o banco `gamificado_aprendizado` e execute `database.sql` no phpMyAdmin.
2. Ajuste credenciais em `api/config.php` se necessário:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'gamificado_aprendizado');
   ```
3. Acesse `http://localhost/gamificado-desafio-aprendizado/`.

## 🚀 Instalação (detalhado)

### 1. Preparar o Ambiente
1. Instale o XAMPP ou Laragon
2. Inicie os serviços Apache e MySQL
3. Acesse o phpMyAdmin em `http://localhost/phpmyadmin`

### 2. Configurar o Banco de Dados
1. No phpMyAdmin, crie o banco `gamificado_aprendizado`
2. Execute o conteúdo do arquivo `database.sql`

### 3. Instalar a Aplicação
1. Extraia o projeto na pasta `htdocs` (XAMPP) ou `www` (Laragon)
2. Caminho final sugerido: `C:/xampp/htdocs/gamificado-desafio-aprendizado/`

### 4. Testar a Aplicação
1. Acesse `http://localhost/gamificado-desafio-aprendizado/`
2. Você deve ver a interface com desafios e ranking
3. Teste concluir um desafio clicando em "Concluir"

## 🔌 Endpoints de API (importante)

O frontend (`js/app.js`) consome por padrão os endpoints "simples":

- `api/desafios_simple.php`
- `api/ranking_simple.php`
- `api/concluir_desafio_simple.php`

Também existem versões completas:

- `api/full/desafios.php`
- `api/full/ranking.php`
- `api/full/concluir_desafio.php`

Para a demonstração, mantenha as versões "simple". Se desejar usar as versões completas, ajuste as URLs em `js/app.js`.

## 🎯 Como Usar

### Concluir Desafios
1. Na seção "Desafios Disponíveis", clique em "✅ Concluir"
2. Um modal mostrará pontos ganhos e novo streak
3. Se conquistar um badge, ele será exibido no modal
4. O ranking será atualizado automaticamente

### Gerenciar Desafios
1. Role até a seção "⚙️ Gerenciar Desafios"
2. Preencha o formulário (Título, Área, Pontos, Descrição)
3. Clique em "💾 Salvar Desafio"

### Editar/Deletar Desafios
1. Na lista de desafios, use os ícones ✏️ e 🗑️

### Visualizar Ranking
1. Na seção "🏆 Ranking", escolha a ordenação por Pontos ou Streak

## 🔧 Personalização

### Adicionar Novos Badges
Execute no banco (tabela `badges`):
```sql
INSERT INTO badges (nome, descricao, icone_url, criterio_tipo, criterio_valor, criterio_extra) 
VALUES ('Novo Badge', 'Descrição', 'icons/novo.png', 'pontos', 100, NULL);
```

### Modificar Critérios de Badges
- **pontos**: Badge por pontos totais
- **streak**: Badge por dias consecutivos
- **desafios_area**: Badge por desafios em área específica

### Personalizar Visual
- Edite estilos em `css/style.css`

## 🐛 Solução de Problemas

### Erro de Conexão com Banco
- Verifique MySQL ativo
- Confirme credenciais em `api/config.php`
- Certifique-se que o banco `gamificado_aprendizado` existe

### Desafios Não Carregam
- Verifique Apache ativo
- Confirme a constante `API_URL` em `js/app.js`
- Verifique erros no console do navegador (F12)

### Modal de Feedback Não Aparece
- Verifique jQuery carregado
- Verifique erros no console
- Teste a API: `http://localhost/gamificado-desafio-aprendizado/api/desafios_simple.php`

## 📈 Melhorias Futuras

### Funcionalidades
- [ ] Sistema de login/registro
- [ ] Missões semanais/mensais
- [ ] Loja virtual com itens
- [ ] Dashboard com gráficos
- [ ] Notificações push

### Técnicas
- [ ] Cache com Redis
- [ ] API REST com autenticação JWT
- [ ] Frontend em React/Vue
- [ ] Deploy em Docker
- [ ] Testes automatizados

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch (`git checkout -b feature/nova-feature`)
3. Commit (`git commit -am 'feat: nova feature'`)
4. Push (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE`.

## 👥 Créditos

Desenvolvido pela equipe.

---

🎯 Bons estudos e que a gamificação torne seu aprendizado mais divertido!

