-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/06/2026 às 09:15
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
  `imagem` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `destaque` tinyint(1) DEFAULT 0,
  `promocao` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `nome`, `descricao`, `preco`, `estoque`, `imagem`, `categoria_id`, `destaque`, `promocao`) VALUES
(1, 'camiseta do gojo', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M \r\nunissex', 79.99, 2300, 'gojo.png', 1, 0, 0),
(2, 'camiseta do toji', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M \r\nunissex', 80.00, 2300, 'toji.png', 1, 0, 0),
(3, 'camiseta do sukuna', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M \r\nunissex', 75.00, 2300, 'sukuna.png', 1, 0, 0),
(4, 'camiseta do goku', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M \r\nunissex', 77.00, 2300, 'goku.png', 1, 0, 0),
(5, 'camiseta do luffy', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 'luffy.png', 1, 0, 1),
(6, 'camiseta do naruto', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 85.00, 2300, 'naruto.png', 1, 0, 0),
(7, 'camiseta do ichigo', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 74.00, 2300, 'ichigo.png', 1, 0, 0),
(8, 'camiseta do tanjiro', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 'tanjiro.png', 1, 0, 0),
(9, 'camiseta do inosuke', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 'inosuke.png', 1, 0, 0),
(10, 'camiseta do killua', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 70.00, 2300, 'killua.png', 1, 0, 1),
(11, 'camiseta harry potter', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 88.00, 2300, 'harrypotter.png', 1, 0, 0),
(12, 'camiseta star wars', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 89.90, 2300, 'starwars.png', 1, 0, 0),
(13, 'camiseta marvel', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 89.99, 2300, 'marvel.png', 1, 0, 0),
(14, 'camiseta homem-aranha', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 80.00, 2300, 'homenaranha.png', 1, 0, 0),
(15, 'camiseta carros', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 'carros.png', 1, 0, 0),
(16, 'camiseta trio de ouro harry potter', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 'triodeouro.png', 1, 0, 0),
(17, 'camiseta senhor dos aneis', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 66.00, 2300, 'senhordosaneis.png', 1, 0, 1),
(18, 'camiseta batman', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 78.00, 2300, 'batman.png', 1, 0, 0),
(19, 'camiseta crepusculo', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 50.00, 2300, 'crepusculo.png', 1, 0, 0),
(20, 'camiseta percy jackson', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 80.00, 2300, 'percyjackson.png', 1, 0, 0),
(21, 'camiseta creeper minecraft', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 45.99, 2300, 'creeper.png', 1, 0, 1),
(22, 'camiseta red dead redemptiom 2', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 79.99, 2300, 'reddead2.png', 1, 0, 0),
(23, 'camiseta the last of us', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 73.00, 2300, 'thelastofus.png', 1, 0, 0),
(24, 'camiseta gta5', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 71.00, 2300, 'gta5.png', 1, 0, 0),
(25, 'camiseta cyberpunk77', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 'cyberpunk77.png', 1, 0, 0),
(26, 'camiseta acdc', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 80.00, 2300, 'acdc.png', 1, 0, 0),
(27, 'camiseta guns and roses', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 80.00, 2300, 'gunsandrose.png', 1, 0, 0),
(28, 'camiseta queen', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 88.00, 2300, 'queen.png', 1, 0, 0),
(29, 'camiseta evanescence', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 82.00, 2300, 'evanescence.png', 1, 0, 0),
(30, 'camiseta metallica', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 79.99, 2300, 'metallica.png', 1, 0, 0),
(31, 'moletom akatsuki', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 135.00, 2300, 'akatsuki.png', 2, 0, 1),
(32, 'moletom sanemi', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 130.00, 2300, 'sanemi.png', 2, 0, 0),
(33, 'moletom gojo', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 145.00, 2300, 'gojoi.png', 2, 0, 0),
(34, 'moletom megumi', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 140.00, 2300, 'megumi.png', 2, 0, 0),
(35, 'moletom zoro', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 135.00, 2300, 'zoro.png', 2, 0, 0),
(36, 'moletom attack of titans', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 143.00, 2300, 'aot.png', 2, 0, 0),
(37, 'moletom harry potter', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 150.00, 2300, 'potter.png', 2, 0, 0),
(38, 'moletom coraline', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 120.00, 2300, 'coraline.png', 2, 0, 0),
(39, 'moletom stranger things', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 155.00, 2300, 'strangerthings.png', 2, 0, 0),
(40, 'moletom hellfire club stranger things', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 144.00, 2300, 'strangerthingshellfire.png', 2, 0, 0),
(41, 'moletom homem aranha', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 139.99, 2300, 'homemaranha.png', 2, 0, 0),
(42, 'moletom genshin impact', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 122.00, 2300, 'genshinimpact.png', 2, 0, 0),
(43, 'meia goku', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 45.00, 2300, 'gokumeia.png', 3, 0, 0),
(44, 'meia naruto', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 47.00, 2300, 'narutomeia.png', 3, 0, 0),
(45, 'meia tanjiro', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 55.00, 2300, 'tanjiromeia.png', 3, 0, 0),
(46, 'meia bob esponja', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 48.00, 2300, 'bobmeia.png', 3, 0, 1),
(47, 'meia minions', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 47.00, 2300, 'minionsmeia.png', 3, 0, 0),
(48, 'meia akatsuki', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\nDisponível em diversas cores e estampas.\nTamanho único.', 49.00, 2300, 'minionsmeia.png', 3, 0, 0),
(49, 'meia et', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 50.00, 2300, 'etmeia.png', 3, 0, 0),
(50, 'oversized pantera negra', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 90.00, 2300, 'panteranegra.png', 4, 0, 0),
(51, 'oversized tomioka', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 95.00, 2300, 'tomioka.png', 4, 0, 0),
(52, 'oversized itachi', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 100.00, 2300, 'itachi.png', 4, 0, 0),
(53, 'oversized sanji', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 97.99, 2300, 'sanji.png', 4, 0, 0),
(54, 'oversized jotaro', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 99.99, 2300, 'jotaro.png', 4, 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`, `endereco`, `cidade`, `cep`) VALUES
(1, 'Maria', 'maria@gmail.com', '1234', 'usuario', 'rio de janeiro', 'nova iguacui', '122345666112');

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
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
