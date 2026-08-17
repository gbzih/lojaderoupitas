-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/08/2026 às 21:46
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
(1, 'Manga'),
(2, 'Quadrinho'),
(3, 'Light Novel'),
(4, 'Livro');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf_cnpj` varchar(20) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id`, `nome`, `cpf_cnpj`, `telefone`, `email`, `endereco`) VALUES
(1, 'Cliente Balcao', '000.000.000-00', '(21) 90000-0000', 'balcao@cliente.com', 'Nova Iguacu - RJ'),
(2, 'Cliente Balcao', '000.000.000-00', '(21) 90000-0000', 'balcao@cliente.com', 'Nova Iguacu - RJ'),
(3, 'Cliente Balcao', '000.000.000-00', '(21) 90000-0000', 'balcao@cliente.com', 'Nova Iguacu - RJ'),
(4, 'Cliente Balcao', '000.000.000-00', '(21) 90000-0000', 'balcao@cliente.com', 'Nova Iguacu - RJ'),
(5, 'Cliente Balcao', '000.000.000-00', '(21) 90000-0000', 'balcao@cliente.com', 'Nova Iguacu - RJ'),
(6, 'Cliente Balcao', '000.000.000-00', '(21) 90000-0000', 'balcao@cliente.com', 'Nova Iguacu - RJ'),
(7, 'Cliente Balcao', '000.000.000-00', '(21) 90000-0000', 'balcao@cliente.com', 'Nova Iguacu - RJ');

-- --------------------------------------------------------

--
-- Estrutura para tabela `entrada_item`
--

CREATE TABLE `entrada_item` (
  `id` int(11) NOT NULL,
  `entrada_id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `custo_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `entrada_item`
--

INSERT INTO `entrada_item` (`id`, `entrada_id`, `variacao_id`, `quantidade`, `custo_unitario`) VALUES
(1, 1, 2, 5, 2.00),
(2, 2, 1, 99, 1.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `entrada_mercadoria`
--

CREATE TABLE `entrada_mercadoria` (
  `id` int(11) NOT NULL,
  `fornecedor_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `status` enum('rascunho','confirmada') NOT NULL DEFAULT 'rascunho',
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `entrada_mercadoria`
--

INSERT INTO `entrada_mercadoria` (`id`, `fornecedor_id`, `data`, `status`, `valor_total`) VALUES
(1, 8, '2026-08-07', 'confirmada', 10.00),
(2, 9, '2026-08-07', 'confirmada', 99.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `minimo` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`id`, `variacao_id`, `quantidade`, `minimo`) VALUES
(1, 2, 5, 0),
(3, 1, 98, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cnpj` varchar(20) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedor`
--

INSERT INTO `fornecedor` (`id`, `nome`, `cnpj`, `telefone`, `email`, `endereco`, `ativo`) VALUES
(1, 'Cosplay Imports LTDA', '12.345.678/0001-90', '(21) 99999-1111', 'contato@cosplayimports.com', 'Rio de Janeiro - RJ', 1),
(2, 'Cosplay Imports LTDA', '12.345.678/0001-90', '(21) 99999-1111', 'contato@cosplayimports.com', 'Rio de\r\nJaneiro - RJ', 1),
(3, 'Cosplay Imports LTDA', '12.345.678/0001-90', '(21) 99999-1111', 'contato@cosplayimports.com', 'Rio de\r\nJaneiro - RJ', 1),
(4, 'Cosplay Imports LTDA', '12.345.678/0001-90', '(21) 99999-1111', 'contato@cosplayimports.com', 'Rio de\r\nJaneiro - RJ', 1),
(5, 'Cosplay Imports LTDA', '12.345.678/0001-90', '(21) 99999-1111', 'contato@cosplayimports.com', 'Rio de\r\nJaneiro - RJ', 1),
(6, 'Cosplay Imports LTDA', '12.345.678/0001-90', '(21) 99999-1111', 'contato@cosplayimports.com', 'Rio de\r\nJaneiro - RJ', 1),
(7, 'Cosplay Imports LTDA', '12.345.678/0001-90', '(21) 99999-1111', 'contato@cosplayimports.com', 'Rio de\r\nJaneiro - RJ', 1),
(8, 'Bruno', '123456778', '11222222', 'brunovend@email.com', 'Rua Leocádio Melo, Jardim Nova Era - Nova Iguaçu/RJ', 0),
(9, 'Natan', '123456789', '1234567', 'natanael@email.com', 'Rua Francisco Camelo, Vila São Domingos - Nova Iguaçu/RJ', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimento_estoque`
--

CREATE TABLE `movimento_estoque` (
  `id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `quantidade` int(11) NOT NULL,
  `origem` enum('entrada','venda') NOT NULL,
  `origem_id` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `movimento_estoque`
--

INSERT INTO `movimento_estoque` (`id`, `variacao_id`, `tipo`, `quantidade`, `origem`, `origem_id`, `data`) VALUES
(1, 2, 'entrada', 5, '', 1, '2026-08-07 13:36:59'),
(2, 1, 'entrada', 99, '', 2, '2026-08-07 13:38:29'),
(3, 1, 'saida', 1, 'venda', 3, '2026-08-07 13:39:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `nota_fiscal_entrada`
--

CREATE TABLE `nota_fiscal_entrada` (
  `id` int(11) NOT NULL,
  `entrada_id` int(11) NOT NULL,
  `modelo` varchar(5) NOT NULL,
  `serie` varchar(5) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `chave_acesso` varchar(44) NOT NULL,
  `data_emissao` date NOT NULL,
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `nota_fiscal_venda`
--

CREATE TABLE `nota_fiscal_venda` (
  `id` int(11) NOT NULL,
  `venda_id` int(11) NOT NULL,
  `modelo` varchar(5) NOT NULL,
  `serie` varchar(5) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `data_emissao` date NOT NULL,
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `nota_fiscal_venda`
--

INSERT INTO `nota_fiscal_venda` (`id`, `venda_id`, `modelo`, `serie`, `numero`, `data_emissao`, `valor_total`) VALUES
(1, 3, 'NFCe', '1', '1', '2026-08-07', 189.90);

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
(10, 'Jojo\'s Bizzare Adventure Part 7: Vol.1', 'Mangas incriveis com historias emocionadoras e epicas', 70.00, 112, 1, 1);

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

-- --------------------------------------------------------

--
-- Estrutura para tabela `variacao`
--

CREATE TABLE `variacao` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `tamanho` varchar(10) NOT NULL,
  `cor` varchar(30) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `variacao`
--

INSERT INTO `variacao` (`id`, `produto_id`, `tamanho`, `cor`, `sku`, `preco`) VALUES
(1, 1, 'P', 'Preto', 'COS-NINJA-P-PRETO', 189.90),
(2, 1, 'M', 'Preto', 'COS-NINJA-M-PRETO', 189.90),
(3, 1, 'G', 'Preto', 'COS-NINJA-G-PRETO', 189.90),
(4, 2, 'U', 'Azul', 'COS-PERUCA-U-AZUL', 99.90),
(5, 3, 'U', 'Vermelho', 'COS-MANTO-U-VERM', 149.90),
(6, 4, 'U', 'Marrom', 'COS-STEAM-U-MARR', 79.90);

-- --------------------------------------------------------

--
-- Estrutura para tabela `venda`
--

CREATE TABLE `venda` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `data` date NOT NULL,
  `status` enum('aberta','finalizada') NOT NULL DEFAULT 'aberta',
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `venda`
--

INSERT INTO `venda` (`id`, `usuario_id`, `cliente_id`, `data`, `status`, `valor_total`) VALUES
(3, 8, 1, '2026-08-07', 'finalizada', 189.90);

-- --------------------------------------------------------

--
-- Estrutura para tabela `venda_item`
--

CREATE TABLE `venda_item` (
  `id` int(11) NOT NULL,
  `venda_id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `venda_item`
--

INSERT INTO `venda_item` (`id`, `venda_id`, `variacao_id`, `quantidade`, `preco_unitario`) VALUES
(1, 3, 1, 1, 189.90);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `entrada_item`
--
ALTER TABLE `entrada_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_entrada_item_entrada` (`entrada_id`),
  ADD KEY `idx_entrada_item_variacao` (`variacao_id`);

--
-- Índices de tabela `entrada_mercadoria`
--
ALTER TABLE `entrada_mercadoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_entrada_fornecedor` (`fornecedor_id`),
  ADD KEY `idx_entrada_data` (`data`),
  ADD KEY `idx_entrada_status` (`status`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `variacao_id` (`variacao_id`);

--
-- Índices de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `movimento_estoque`
--
ALTER TABLE `movimento_estoque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mov_variacao` (`variacao_id`),
  ADD KEY `idx_mov_data` (`data`),
  ADD KEY `idx_mov_origem` (`origem`,`origem_id`),
  ADD KEY `idx_mov_tipo` (`tipo`);

--
-- Índices de tabela `nota_fiscal_entrada`
--
ALTER TABLE `nota_fiscal_entrada`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entrada_id` (`entrada_id`),
  ADD UNIQUE KEY `chave_acesso` (`chave_acesso`),
  ADD KEY `idx_nf_entrada_data` (`data_emissao`);

--
-- Índices de tabela `nota_fiscal_venda`
--
ALTER TABLE `nota_fiscal_venda`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `venda_id` (`venda_id`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `idx_nf_venda_data` (`data_emissao`);

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
-- Índices de tabela `variacao`
--
ALTER TABLE `variacao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_variacao_produto` (`produto_id`);

--
-- Índices de tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_venda_usuario` (`usuario_id`),
  ADD KEY `idx_venda_cliente` (`cliente_id`),
  ADD KEY `idx_venda_data` (`data`),
  ADD KEY `idx_venda_status` (`status`);

--
-- Índices de tabela `venda_item`
--
ALTER TABLE `venda_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_venda_item_venda` (`venda_id`),
  ADD KEY `idx_venda_item_variacao` (`variacao_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `entrada_item`
--
ALTER TABLE `entrada_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
