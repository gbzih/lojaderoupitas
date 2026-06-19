-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/06/2026 às 18:17
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
-- Banco de dados: `loja_roupas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id_carrinho` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'camisetas'),
(2, 'moletons'),
(3, 'meia'),
(4, 'oversized');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `pedido_id` int(11) DEFAULT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `data_pedido` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `metodo_pagamento` varchar(30) DEFAULT NULL
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
(1, 'camiseta do gojo', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M \r\nunissex', 79.99, 2300, 1, 1),
(2, 'camiseta do toji', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M \r\nunissex', 80.00, 2300, 1, 1),
(3, 'camiseta do sukuna', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M \r\nunissex', 75.00, 2300, 1, 1),
(4, 'camiseta do goku', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M \r\nunissex', 77.00, 2300, 1, 1),
(5, 'camiseta do luffy', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 1, 1),
(6, 'camiseta do naruto', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 85.00, 2300, 1, 1),
(7, 'camiseta do ichigo', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 74.00, 2300, 1, 1),
(8, 'camiseta do tanjiro', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 1, 1),
(9, 'camiseta do inosuke', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 1, 1),
(10, 'camiseta do killua', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 70.00, 2300, 1, 1),
(11, 'camiseta harry potter', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 88.00, 2300, 1, 1),
(12, 'camiseta star wars', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 89.90, 2300, 1, 1),
(13, 'camiseta marvel', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 89.99, 2300, 1, 1),
(14, 'camiseta homem-aranha', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 80.00, 2300, 1, 1),
(15, 'camiseta carros', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 1, 1),
(16, 'camiseta trio de ouro harry potter', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 1, 1),
(17, 'camiseta senhor dos aneis', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 66.00, 2300, 1, 1),
(18, 'camiseta batman', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 78.00, 2300, 1, 1),
(19, 'camiseta crepusculo', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 50.00, 2300, 1, 1),
(20, 'camiseta percy jackson', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 80.00, 2300, 1, 1),
(21, 'camiseta creeper minecraft', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 45.99, 2300, 1, 1),
(22, 'camiseta red dead redemptiom 2', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 79.99, 2300, 1, 1),
(23, 'camiseta the last of us', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 73.00, 2300, 1, 1),
(24, 'camiseta gta5', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 71.00, 2300, 1, 1),
(25, 'camiseta cyberpunk77', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 1, 1),
(26, 'camiseta acdc', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 80.00, 2300, 1, 1),
(27, 'camiseta guns and roses', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 80.00, 2300, 1, 1),
(28, 'camiseta queen', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 88.00, 2300, 1, 1),
(29, 'camiseta evanescence', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 82.00, 2300, 1, 1),
(30, 'camiseta metallica', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 79.99, 2300, 1, 1),
(31, 'moletom akatsuki', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 135.00, 2300, 2, 1),
(32, 'moletom sanemi', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 130.00, 2300, 2, 1),
(33, 'moletom gojo', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 145.00, 2300, 2, 1),
(34, 'moletom megumi', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 140.00, 2300, 2, 1),
(35, 'moletom zoro', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 135.00, 2300, 2, 1),
(36, 'moletom attack of titans', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 143.00, 2300, 2, 1),
(37, 'moletom harry potter', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 150.00, 2300, 2, 1),
(38, 'moletom coraline', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 120.00, 2300, 2, 1),
(39, 'moletom stranger things', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 155.00, 2300, 2, 1),
(40, 'moletom hellfire club stranger things', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 144.00, 2300, 2, 1),
(41, 'moletom homem aranha', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 139.99, 2300, 2, 1),
(42, 'moletom genshin impact', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 122.00, 2300, 2, 1),
(43, 'meia goku', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 45.00, 2300, 3, 1),
(44, 'meia naruto', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 47.00, 2300, 3, 1),
(45, 'meia tanjiro', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 55.00, 2300, 3, 1),
(47, 'meia minions', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 47.00, 2300, 3, 1),
(48, 'meia akatsuki', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\nDisponível em diversas cores e estampas.\nTamanho único.', 49.00, 2300, 3, 1),
(49, 'meia et', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 50.00, 2300, 3, 1),
(50, 'oversized pantera negra', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 90.00, 2300, 4, 1),
(51, 'oversized tomioka', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 95.00, 2300, 4, 1),
(52, 'oversized itachi', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 100.00, 2300, 4, 1),
(53, 'oversized sanji', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 97.99, 2300, 4, 1),
(54, 'overside kakyoin', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 99.99, 2300, 4, 1);

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
(1, 'admin', 'admin@gmail.com', '$2a$12$6OL6Rojau9GkhFsHLb26Yu18zeptGW.iQalqaq2fe5XVxGSEFdf4S', 'admin', 1),
(3, 'juan', 'juan@gmail.com', '$2y$10$b.awcknVaLmbxsctwy9uAOJfxtaG/6T.y6PZrVlpBibwUQ1VAEQc6', 'vendedor', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id_carrinho`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `usuario_id` (`usuario_id`);

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
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id_carrinho` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id_produto`);

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id_produto`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
