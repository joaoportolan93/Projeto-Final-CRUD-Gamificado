-- Base de dados: `gamificado_aprendizado`
CREATE DATABASE IF NOT EXISTS `gamificado_aprendizado` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gamificado_aprendizado`;

-- Estrutura da tabela `usuarios`
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `pontos_totais` int(11) DEFAULT 0,
  `streak_atual` int(11) DEFAULT 0,
  `ultimo_login` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserindo um usuário de exemplo
INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `pontos_totais`, `streak_atual`, `ultimo_login`) VALUES
(1, 'Usuário Teste', 'teste@exemplo.com', 'senha_criptografada', 150, 5, '2025-08-25');

-- Estrutura da tabela `desafios`
CREATE TABLE `desafios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `area_conhecimento` varchar(50) NOT NULL,
  `pontos` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserindo desafios de exemplo
INSERT INTO `desafios` (`id`, `titulo`, `descricao`, `area_conhecimento`, `pontos`) VALUES
(1, 'Resolver 10 equações de 1º grau', 'Pratique suas habilidades em álgebra.', 'Matemática', 20),
(2, 'Escrever uma redação de 300 palavras', 'Tema: O impacto da tecnologia na sociedade.', 'Linguagens', 30),
(3, 'Criar uma função em Python', 'A função deve calcular o fatorial de um número.', 'Programação', 50);

-- Estrutura da tabela `desafios_concluidos`
CREATE TABLE `desafios_concluidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `desafio_id` int(11) NOT NULL,
  `data_conclusao` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `desafio_id` (`desafio_id`),
  CONSTRAINT `desafios_concluidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `desafios_concluidos_ibfk_2` FOREIGN KEY (`desafio_id`) REFERENCES `desafios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Estrutura da tabela `badges`
CREATE TABLE `badges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `icone_url` varchar(255) NOT NULL,
  `criterio_tipo` enum('pontos','streak','desafios_area') NOT NULL,
  `criterio_valor` int(11) NOT NULL,
  `criterio_extra` varchar(50) DEFAULT NULL, -- Para 'desafios_area', armazena a área
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserindo badges de exemplo
INSERT INTO `badges` (`id`, `nome`, `descricao`, `icone_url`, `criterio_tipo`, `criterio_valor`, `criterio_extra`) VALUES
(1, 'Iniciante', 'Concluiu seu primeiro desafio.', 'icons/iniciante.png', 'pontos', 1, NULL),
(2, 'Persistente', 'Manteve um streak de 5 dias de estudo.', 'icons/persistente.png', 'streak', 5, NULL),
(3, 'Matemático Júnior', 'Concluiu 1 desafio na área de Matemática.', 'icons/matematico.png', 'desafios_area', 1, 'Matemática');

-- Estrutura da tabela `usuarios_badges`
CREATE TABLE `usuarios_badges` (
  `usuario_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `data_conquista` datetime NOT NULL,
  PRIMARY KEY (`usuario_id`,`badge_id`),
  KEY `badge_id` (`badge_id`),
  CONSTRAINT `usuarios_badges_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `usuarios_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índices para otimização de performance
CREATE INDEX idx_pontos ON usuarios(pontos_totais DESC);
CREATE INDEX idx_streak ON usuarios(streak_atual DESC);

