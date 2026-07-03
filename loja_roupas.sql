-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/07/2026 às 16:37
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
(1, 'Camisetas'),
(2, 'Moletons'),
(3, 'Meia'),
(4, 'Oversized');

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
(1, 'Oversized Sanji', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 99.99, 2300, 4, 1),
(2, 'Oversized Itachi', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 100.00, 2300, 4, 1),
(3, 'Oversized Tomioka', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 95.00, 2300, 4, 1),
(4, 'Oversized Pantera Negra', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 90.00, 2300, 4, 1),
(5, 'Meia Akatsuki', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 49.00, 2300, 3, 1),
(6, 'Meia Minions', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 47.00, 2300, 3, 1),
(7, 'Meia Tanjiro', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 55.00, 2300, 3, 1),
(8, 'Meia Naruto', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 47.00, 2300, 3, 1),
(9, 'Meia Goku', 'Meia confeccionada em tecido confortável e respirável, ideal para o uso diário. Possui toque macio, ótima elasticidade e acabamento resistente, garantindo conforto durante todo o dia.\r\nDisponível em diversas cores e estampas.\r\nTamanho único.', 45.00, 2300, 3, 1),
(10, 'Moletom Zoro', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 135.00, 2300, 2, 1),
(11, 'Moletom Megumi', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 140.00, 2300, 2, 1),
(12, 'Moletom Gojo', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 145.00, 2300, 2, 1),
(13, 'Moletom Sanemi', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 130.00, 2300, 2, 1),
(14, 'Moletom Akatsuki', 'Moletom confeccionado em tecido macio e confortável, ideal para os dias mais frios. Possui acabamento de alta qualidade, estampa resistente e caimento moderno para o uso no dia a dia.\r\nDisponível nas cores preta, branca e cinza.\r\nTamanhos: P, M, G e GG.', 135.00, 2300, 2, 1),
(15, 'Camiseta do Luffy', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M\r\nunissex', 75.00, 2300, 1, 1),
(16, 'Camiseta do Goku', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M \r\nunissex', 77.00, 2300, 1, 1),
(17, 'Camiseta do Sukuna', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M \r\nunissex', 75.00, 2300, 1, 1),
(18, 'Camiseta do Gojo', 'Camiseta confeccionada em tecido confortável e resistente, ideal para o uso no dia a dia. Possui estampa de alta qualidade. \r\nCor preta Tamanho G e M \r\nunissex', 79.99, 2300, 1, 1),
(19, 'Camiseta do Gilgamesh', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 75.00, 100000, 1, 1),
(20, 'Oversized Pikachu', 'Camiseta oversized confeccionada em tecido premium, confortável e resistente, com modelagem ampla e caimento moderno. Ideal para compor looks estilosos e casuais no dia a dia. Possui estampa de alta qualidade e acabamento reforçado.\r\nDisponível nas cores preta, branca e bege.\r\nTamanhos: P, M, G e GG.', 80.00, 100000, 4, 1),
(32342, 'wada', 'dawda', 100.00, 13434, 3, 1);

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
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32343;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
