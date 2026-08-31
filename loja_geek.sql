-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 31/08/2026 às 19:29
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `loja_geek`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nome`) VALUES
(1, 'Manga'),
(2, 'Quadrinho'),
(3, 'Light Novel'),
(4, 'Livro');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `minimo` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`id`, `quantidade`, `minimo`) VALUES
(1, 5, 0),
(3, 98, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedidos`
--

CREATE TABLE `itens_pedidos` (
  `a` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamento`
--

CREATE TABLE `pagamento` (
  `id_pagamento` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `forma_pagamento` varchar(50) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `status_pagamento` varchar(50) DEFAULT 'Pendente',
  `data_pagamento` datetime DEFAULT current_timestamp(),
  `transacao_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `status_pedido` varchar(50) DEFAULT 'Pendente',
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `endereco_entrega` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `nome`, `descricao`, `preco`, `estoque`, `categoria_id`, `ativo`) VALUES
(1, 'Marvel: As Aventuras de Ciclopes e Fenix', 'Quadrinhos com historias emocionantes com seus personagens favoritos.', 78.00, 104, 2, 1),
(2, 'Demon Slayer Vol.1', 'Mangas incriveis com historias emocionadoras e epicas', 29.00, 203, 1, 1),
(3, 'Demon Slayer Vol.2', 'Mangas incriveis com historias emocionadoras e epicas', 29.00, 102, 2, 1),
(4, 'One Piece Vol.1', 'Mangas incriveis com historias emocionadoras e epicas', 34.90, 103, 2, 1),
(5, 'One Piece Vol.2', 'Mangas incriveis com historias emocionadoras e epicas', 40.00, 20, 2, 1),
(6, 'Marvel Ultimate: Devorador de Mundos', 'Quadrinhos com historias emocionantes com seus personagens favoritos.', 95.90, 186, 2, 1),
(7, 'Batman: Colheita Maldita', 'Quadrinhos com historias emocionantes com seus personagens favoritos.', 30.30, 234, 2, 1),
(8, 'All-Star Superman', 'Quadrinhos com historias emocionantes com seus personagens favoritos.', 94.00, 231, 2, 1),
(9, 'Mulher-Maravilha: Tempestade Turbulenta', 'Quadrinhos com historias emocionantes com seus personagens favoritos.', 63.00, 233, 2, 1),
(10, 'Jojo\'s Bizzare Adventure Part 7: Vol.1', 'Mangas incriveis com historias emocionadoras e epicas', 70.00, 112, 1, 1),
(11, 'Turma da Monica Jovem Vol.53', 'Livros dos seus autores mais famosos', 30.00, 121, 4, 1),
(12, 'Harry Potter: Pedra Filosofal', 'Livros dos seus autores mais famosos', 114.00, 221, 4, 1),
(13, 'Como eu era antes de voce', 'Livros dos seus autores mais famosos', 108.00, 311, 4, 1),
(14, 'A culpa e das estrelas', 'Livros dos seus autores mais famosos', 89.00, 422, 4, 1),
(15, 'Twilight', 'Livros dos seus autores mais famosos', 125.00, 126, 4, 1),
(16, 'The Apothecary Diaries Vol.16', 'Histórias leves e envolventes, cheias de fantasia, aventura, romance e personagens marcantes.', 200.00, 174, 3, 1),
(17, 'Mushoku Tensei Vol.26', 'Histórias leves e envolventes, cheias de fantasia, aventura, romance e personagens marcantes.', 178.00, 198, 3, 1),
(18, 'That Time I Got Reincarnated as a Slime Vol.23', 'Histórias leves e envolventes, cheias de fantasia, aventura, romance e personagens marcantes.', 111.00, 129, 3, 1),
(19, 'Re:Zero Vol 44', 'Histórias leves e envolventes, cheias de fantasia, aventura, romance e personagens marcantes.', 134.00, 146, 3, 1),
(20, 'Classroom of the Elite Vol.30', 'Histórias leves e envolventes, cheias de fantasia, aventura, romance e personagens marcantes.', 120.00, 121, 3, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `perfil` varchar(50) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`, `perfil`, `ativo`) VALUES
(1, 'Guilherme', 'gb@gmail.com', '$2a$12$6OL6Rojau9GkhFsHLb26Yu18zeptGW.iQalqaq2fe5XVxGSEFdf4S', 'admin', 1),
(3, 'Juan', 'juan@gmail.com', '$2y$10$b.awcknVaLmbxsctwy9uAOJfxtaG/6T.y6PZrVlpBibwUQ1VAEQc6', 'Vendedor', 1),
(4, 'Jess', 'jessie@gmail.com', '$2y$10$klNyfd7qgCvX2wS9rxVukeniF8av5F3RkPB.DjLcKEcJTRJST0Ifm', 'Vendedor', 1),
(6, 'Juana', 'juana@gmail.com', '$2y$10$QInDU9pA4ZFRCUFjxYrg2O0DCpHV2LMBZpJEdywEDwsgOLQu.OWp6', 'vendedor', 1),
(7, 'Wenderson', 'wenderson@gmail.com', '$2y$10$INtCp0S1T8BXJ/qbH9UNuebKW/VDyUotMU9.otrtciInP6VC6xLVC', 'vendedor', 1),
(8, 'Maria', 'g@gmail.com', '$2y$10$tzz.OYWPtg96ovdLv9sS4.wrnV5CK36SuhD09OkYCk6CIkofvPF4S', 'vendedor', 1),
(11, 'Carlos', 'carlos@gmail.com', '$2y$10$Sim3ugfxzyvc1jq8CJ5AO.oMzrwsACqFHsZNhILpPdAYWIUddXdmK', 'vendedor', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pagamento`
--
ALTER TABLE `pagamento`
  ADD PRIMARY KEY (`id_pagamento`),
  ADD KEY `fk_pagamento_pedido` (`id_pedido`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `fk_pedidos_usuario` (`id_usuario`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `pagamento`
--
ALTER TABLE `pagamento`
  MODIFY `id_pagamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32346;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pagamento`
--
ALTER TABLE `pagamento`
  ADD CONSTRAINT `fk_pagamento_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
