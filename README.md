<<<<<<< HEAD
# Projeto-Final-CRUD-Gamificado
=======
# 🎯 Desafio de Aprendizado - Sistema CRUD Gamificado

Uma aplicação web completa que gamifica o processo de aprendizado através de desafios, pontos, streaks e badges. Desenvolvida com PHP, MySQL, HTML, CSS e JavaScript.

## ✨ Funcionalidades

### 🎮 Sistema de Gamificação
- **Pontos**: Ganhe pontos ao concluir desafios
- **Streaks**: Mantenha uma sequência diária de estudos
- **Badges**: Conquiste medalhas por diferentes critérios
- **Ranking**: Compete com outros usuários

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
- Editor de texto (opcional, para modificações)

## 🚀 Instalação

### 1. Preparar o Ambiente
1. Instale o XAMPP ou Laragon
2. Inicie os serviços Apache e MySQL
3. Acesse o phpMyAdmin em `http://localhost/phpmyadmin`

### 2. Configurar o Banco de Dados
1. No phpMyAdmin, clique em "Novo" para criar um banco
2. Nome do banco: `gamificado_aprendizado`
3. Clique na aba "SQL" e execute o conteúdo do arquivo `database.sql`

### 3. Instalar a Aplicação
1. Extraia o projeto na pasta `htdocs` (XAMPP) ou `www` (Laragon)
2. Caminho final: `C:/xampp/htdocs/gamificado-desafio-aprendizado/`
3. Verifique se a estrutura de pastas está correta:
   ```
   gamificado-desafio-aprendizado/
   ├── api/
   │   ├── config.php
   │   ├── desafios.php
   │   ├── ranking.php
   │   ├── concluir_desafio.php
   │   └── logica_gamificacao.php
   ├── icons/
   │   ├── iniciante.png
   │   ├── persistente.png
   │   └── matematico.png
   ├── index.html
   ├── database.sql
   └── README.md
   ```

### 4. Configurar a Conexão
1. Abra o arquivo `api/config.php`
2. Verifique as configurações do banco:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'gamificado_aprendizado');
   ```
3. Ajuste se necessário para seu ambiente

### 5. Testar a Aplicação
1. Acesse `http://localhost/gamificado-desafio-aprendizado/`
2. Você deve ver a interface com desafios e ranking
3. Teste concluir um desafio clicando em "Concluir"

## 🎯 Como Usar

### Concluir Desafios
1. Na seção "Desafios Disponíveis", clique em "✅ Concluir"
2. Um modal aparecerá mostrando os pontos ganhos e novo streak
3. Se conquistar um badge, ele será exibido no modal
4. O ranking será atualizado automaticamente

### Gerenciar Desafios
1. Role até a seção "⚙️ Gerenciar Desafios"
2. Preencha o formulário com:
   - **Título**: Nome do desafio
   - **Área de Conhecimento**: Categoria (ex: Matemática, Programação)
   - **Pontos**: Valor em pontos (1-100)
   - **Descrição**: Detalhes do desafio
3. Clique em "💾 Salvar Desafio"

### Editar/Deletar Desafios
1. Na lista de desafios, use os ícones:
   - **✏️ Editar**: Carrega os dados no formulário
   - **🗑️ Deletar**: Remove o desafio (com confirmação)

### Visualizar Ranking
1. Na seção "🏆 Ranking", escolha a ordenação:
   - **Pontos**: Ordena por pontos totais
   - **Streak**: Ordena por sequência de dias
2. Os primeiros colocados recebem medalhas (🥇🥈🥉)

## 🔧 Personalização

### Adicionar Novos Badges
1. Insira no banco de dados (tabela `badges`):
   ```sql
   INSERT INTO badges (nome, descricao, icone_url, criterio_tipo, criterio_valor, criterio_extra) 
   VALUES ('Novo Badge', 'Descrição', 'icons/novo.png', 'pontos', 100, NULL);
   ```

### Modificar Critérios de Badges
- **pontos**: Badge por pontos totais
- **streak**: Badge por dias consecutivos
- **desafios_area**: Badge por desafios em área específica

### Personalizar Visual
1. Edite o CSS no arquivo `index.html`
2. Modifique cores nos gradientes:
   ```css
   background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
   ```

## 🐛 Solução de Problemas

### Erro de Conexão com Banco
- Verifique se MySQL está rodando
- Confirme as credenciais em `api/config.php`
- Certifique-se que o banco `gamificado_aprendizado` existe

### Desafios Não Carregam
- Verifique se o Apache está rodando
- Confirme o caminho da API no JavaScript (linha 41 do `index.html`)
- Verifique erros no console do navegador (F12)

### Modal de Feedback Não Aparece
- Verifique se jQuery está carregando
- Confirme se não há erros JavaScript no console
- Teste a API diretamente: `http://localhost/seu-projeto/api/desafios.php`

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
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 👥 Créditos

Desenvolvido pela equipe de especialistas:
- **Engenheiro de Software**: Arquitetura e backend
- **Especialista em UX/Gamificação**: Frontend e experiência
- **Especialista em Inovação**: Conceitos e melhorias

---

**🎯 Bons estudos e que a gamificação torne seu aprendizado mais divertido!**

>>>>>>> 9e9cdc7 (🎮 Projeto Final CRUD Gamificado - Sistema completo de desafios de aprendizado com gamificação, ranking, badges e interface responsiva)
