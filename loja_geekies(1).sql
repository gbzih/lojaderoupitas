-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/11/2026 às 22:51
-- Versão do servidor: 10.11.16-MariaDB
-- Versão do PHP: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `loja_geekies`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administrador`
--

CREATE TABLE `administrador` (
  `id_administrador` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nivelAcesso` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `administrador`
--

INSERT INTO `administrador` (`id_administrador`, `id_usuario`, `nivelAcesso`) VALUES
(1, 1, 'admin');

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id_carrinho` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `data_criacao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nome`) VALUES
(1, 'Livros'),
(2, 'Hqs'),
(3, 'Manga'),
(4, 'Light Novels');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `entrada`
--

CREATE TABLE `entrada` (
  `id_entrada` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `data_entrada` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id_estoque` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `minimo` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`id_estoque`, `id_produto`, `quantidade`, `minimo`) VALUES
(1, 5, 15, 5),
(3, 18, 15, 5),
(4, 16, 4, 5),
(5, 15, 12, 5),
(6, 2, 66, 5),
(7, 8, 555, 5),
(8, 3, 42, 5),
(9, 6, 88, 5),
(10, 19, 92, 5),
(11, 17, 11, 5),
(12, 14, 3, 5),
(13, 4, 1, 5),
(14, 1, 4, 5),
(15, 12, 61, 5),
(16, 13, 41, 5),
(17, 9, 87, 5),
(18, 10, 1002, 5),
(19, 20, 218, 5),
(20, 11, 112, 5),
(21, 7, 428, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_carrinho`
--

CREATE TABLE `item_carrinho` (
  `id_item_carrinho` int(11) NOT NULL,
  `id_carrinho` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_pedido`
--

CREATE TABLE `item_pedido` (
  `id_item_pedido` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
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
  `status_pagamento` varchar(50) NOT NULL,
  `data_pagamento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `data_pedido` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `id_categoria`, `nome`, `descricao`, `preco`, `ativo`) VALUES
(1, 1, 'O Nome do Vento', 'Edição em capa dura ou brochura com acabamento de alta qualidade, impressa em papel de excelente gramagem para uma leitura confortável e sem fadiga visual. Uma obra indispensável para os amantes da literatura e colecionadores, ideal para expandir a tua biblioteca com uma narrativa envolvente do início ao fim.\r\nPáginas: Variável conforme o título.', 99.99, 1),
(2, 1, 'Duna', 'Edição em capa dura ou brochura com acabamento de alta qualidade, impressa em papel de excelente gramagem para uma leitura confortável e sem fadiga visual. Uma obra indispensável para os amantes da literatura e colecionadores, ideal para expandir a tua biblioteca com uma narrativa envolvente do início ao fim.\r\nPáginas: Variável conforme o título.', 89.00, 1),
(3, 1, 'Harry Potter', 'Edição em capa dura ou brochura com acabamento de alta qualidade, impressa em papel de excelente gramagem para uma leitura confortável e sem fadiga visual. Uma obra indispensável para os amantes da literatura e colecionadores, ideal para expandir a tua biblioteca com uma narrativa envolvente do início ao fim.\r\nPáginas: Variável conforme o título.', 27.00, 1),
(4, 1, 'O Hobbit', 'Edição em capa dura ou brochura com acabamento de alta qualidade, impressa em papel de excelente gramagem para uma leitura confortável e sem fadiga visual. Uma obra indispensável para os amantes da literatura e colecionadores, ideal para expandir a tua biblioteca com uma narrativa envolvente do início ao fim.\r\nPáginas: Variável conforme o título.', 23.00, 1),
(5, 2, 'Batman: O Cavaleiro das Trevas', 'Edição impressa com acabamento de alta qualidade, produzida em papel de excelente gramagem para valorizar cada detalhe das ilustrações e proporcionar uma experiência de leitura confortável. Com histórias envolventes, arte visual expressiva e narrativas dinâmicas, é uma peça indispensável para os apreciadores da nona arte e colecionadores. Ideal para enriquecer a tua coleção e explorar diferentes universos e personagens', 49.00, 1),
(6, 2, 'Homem-Aranha: A Última Caçada de Kraven', 'Edição impressa com acabamento de alta qualidade, produzida em papel de excelente gramagem para valorizar cada detalhe das ilustrações e proporcionar uma experiência de leitura confortável. Com histórias envolventes, arte visual expressiva e narrativas dinâmicas, é uma peça indispensável para os apreciadores da nona arte e colecionadores. Ideal para enriquecer a tua coleção e explorar diferentes universos e personagens', 47.00, 1),
(7, 2, 'Watchmen', 'Edição impressa com acabamento de alta qualidade, produzida em papel de excelente gramagem para valorizar cada detalhe das ilustrações e proporcionar uma experiência de leitura confortável. Com histórias envolventes, arte visual expressiva e narrativas dinâmicas, é uma peça indispensável para os apreciadores da nona arte e colecionadores. Ideal para enriquecer a tua coleção e explorar diferentes universos e personagens', 55.00, 1),
(8, 2, 'Guerra Civil', 'Edição impressa com acabamento de alta qualidade, produzida em papel de excelente gramagem para valorizar cada detalhe das ilustrações e proporcionar uma experiência de leitura confortável. Com histórias envolventes, arte visual expressiva e narrativas dinâmicas, é uma peça indispensável para os apreciadores da nona arte e colecionadores. Ideal para enriquecer a tua coleção e explorar diferentes universos e personagens', 47.00, 1),
(9, 2, 'Reino do Amanhã', 'Edição impressa com acabamento de alta qualidade, produzida em papel de excelente gramagem para valorizar cada detalhe das ilustrações e proporcionar uma experiência de leitura confortável. Com histórias envolventes, arte visual expressiva e narrativas dinâmicas, é uma peça indispensável para os apreciadores da nona arte e colecionadores. Ideal para enriquecer a tua coleção e explorar diferentes universos e personagens', 75.00, 1),
(10, 4, 'Solo Leveling', 'Obra literária ilustrada, apresentada em edição de capa dura ou brochura, com acabamento de alta qualidade e impressão em papel de excelente gramagem para proporcionar uma leitura confortável e agradável. Combina uma narrativa envolvente com ilustrações que complementam a experiência literária, sendo uma excelente escolha para fãs de literatura japonesa e colecionadores. Ideal para expandir a tua biblioteca com histórias cativantes e universos únicos.', 25.00, 1),
(11, 4, 'Sword Art Online', 'Obra literária ilustrada, apresentada em edição de capa dura ou brochura, com acabamento de alta qualidade e impressão em papel de excelente gramagem para proporcionar uma leitura confortável e agradável. Combina uma narrativa envolvente com ilustrações que complementam a experiência literária, sendo uma excelente escolha para fãs de literatura japonesa e colecionadores. Ideal para expandir a tua biblioteca com histórias cativantes e universos únicos.', 35.00, 1),
(12, 4, 'Overlord', 'Obra literária ilustrada, apresentada em edição de capa dura ou brochura, com acabamento de alta qualidade e impressão em papel de excelente gramagem para proporcionar uma leitura confortável e agradável. Combina uma narrativa envolvente com ilustrações que complementam a experiência literária, sendo uma excelente escolha para fãs de literatura japonesa e colecionadores. Ideal para expandir a tua biblioteca com histórias cativantes e universos únicos.', 75.00, 1),
(13, 4, 'Re:Zero', 'Obra literária ilustrada, apresentada em edição de capa dura ou brochura, com acabamento de alta qualidade e impressão em papel de excelente gramagem para proporcionar uma leitura confortável e agradável. Combina uma narrativa envolvente com ilustrações que complementam a experiência literária, sendo uma excelente escolha para fãs de literatura japonesa e colecionadores. Ideal para expandir a tua biblioteca com histórias cativantes e universos únicos.', 90.00, 1),
(14, 4, 'No Game No Life', 'Obra literária ilustrada, apresentada em edição de capa dura ou brochura, com acabamento de alta qualidade e impressão em papel de excelente gramagem para proporcionar uma leitura confortável e agradável. Combina uma narrativa envolvente com ilustrações que complementam a experiência literária, sendo uma excelente escolha para fãs de literatura japonesa e colecionadores. Ideal para expandir a tua biblioteca com histórias cativantes e universos únicos.', 53.00, 1),
(15, 3, 'Chainsaw Man', 'Edição impressa com acabamento de qualidade, cuidadosamente produzida para proporcionar uma experiência de leitura agradável e valorizar a arte e a narrativa de cada obra. Reúne histórias ilustradas, personagens marcantes e universos distintos, sendo uma excelente opção para leitores de todas as idades e entusiastas da banda desenhada. Ideal para ampliar a tua coleção e desfrutar de grandes histórias em formato ilustrado.', 75.00, 1),
(16, 3, 'Dragon Ball Super', 'Edição impressa com acabamento de qualidade, cuidadosamente produzida para proporcionar uma experiência de leitura agradável e valorizar a arte e a narrativa de cada obra. Reúne histórias ilustradas, personagens marcantes e universos distintos, sendo uma excelente opção para leitores de todas as idades e entusiastas da banda desenhada. Ideal para ampliar a tua coleção e desfrutar de grandes histórias em formato ilustrado.', 77.00, 1),
(17, 3, 'Naruto', 'Edição impressa com acabamento de qualidade, cuidadosamente produzida para proporcionar uma experiência de leitura agradável e valorizar a arte e a narrativa de cada obra. Reúne histórias ilustradas, personagens marcantes e universos distintos, sendo uma excelente opção para leitores de todas as idades e entusiastas da banda desenhada. Ideal para ampliar a tua coleção e desfrutar de grandes histórias em formato ilustrado.', 75.00, 1),
(18, 3, 'Bleach', 'Edição impressa com acabamento de qualidade, cuidadosamente produzida para proporcionar uma experiência de leitura agradável e valorizar a arte e a narrativa de cada obra. Reúne histórias ilustradas, personagens marcantes e universos distintos, sendo uma excelente opção para leitores de todas as idades e entusiastas da banda desenhada. Ideal para ampliar a tua coleção e desfrutar de grandes histórias em formato ilustrado.', 79.99, 1),
(19, 3, 'My Hero Academia', 'Edição impressa com acabamento de qualidade, cuidadosamente produzida para proporcionar uma experiência de leitura agradável e valorizar a arte e a narrativa de cada obra. Reúne histórias ilustradas, personagens marcantes e universos distintos, sendo uma excelente opção para leitores de todas as idades e entusiastas da banda desenhada. Ideal para ampliar a tua coleção e desfrutar de grandes histórias em formato ilustrado.', 45.00, 1),
(20, 3, 'Spy x Family', 'Edição impressa com acabamento de qualidade, cuidadosamente produzida para proporcionar uma experiência de leitura agradável e valorizar a arte e a narrativa de cada obra. Reúne histórias ilustradas, personagens marcantes e universos distintos, sendo uma excelente opção para leitores de todas as idades e entusiastas da banda desenhada. Ideal para ampliar a tua coleção e desfrutar de grandes histórias em formato ilustrado.', 37.00, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`) VALUES
(1, 'ADM', 'admin@gmail.com', '$2a$12$jb5WrA0HyT8iDbLGroHtxeo2yWjwIslQ1yvkvEbofLugNsSYXoAMi');

-- --------------------------------------------------------

--
-- Estrutura para tabela `venda`
--

CREATE TABLE `venda` (
  `id_venda` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `data_venda` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_administrador`),
  ADD UNIQUE KEY `id_usuario_2` (`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id_carrinho`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `id_usuario_2` (`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `entrada`
--
ALTER TABLE `entrada`
  ADD PRIMARY KEY (`id_entrada`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id_estoque`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `item_carrinho`
--
ALTER TABLE `item_carrinho`
  ADD PRIMARY KEY (`id_item_carrinho`),
  ADD KEY `id_carrinho` (`id_carrinho`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD PRIMARY KEY (`id_item_pedido`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `pagamento`
--
ALTER TABLE `pagamento`
  ADD PRIMARY KEY (`id_pagamento`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Índices de tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`id_venda`),
  ADD KEY `id_produto` (`id_produto`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_administrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id_carrinho` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `entrada`
--
ALTER TABLE `entrada`
  MODIFY `id_entrada` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id_estoque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `item_carrinho`
--
ALTER TABLE `item_carrinho`
  MODIFY `id_item_carrinho` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  MODIFY `id_item_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagamento`
--
ALTER TABLE `pagamento`
  MODIFY `id_pagamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `venda`
--
ALTER TABLE `venda`
  MODIFY `id_venda` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Restrições para tabelas `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `entrada`
--
ALTER TABLE `entrada`
  ADD CONSTRAINT `entrada_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);

--
-- Restrições para tabelas `estoque`
--
ALTER TABLE `estoque`
  ADD CONSTRAINT `estoque_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);

--
-- Restrições para tabelas `item_carrinho`
--
ALTER TABLE `item_carrinho`
  ADD CONSTRAINT `item_carrinho_ibfk_1` FOREIGN KEY (`id_carrinho`) REFERENCES `carrinho` (`id_carrinho`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_carrinho_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);

--
-- Restrições para tabelas `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD CONSTRAINT `item_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_pedido_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);

--
-- Restrições para tabelas `pagamento`
--
ALTER TABLE `pagamento`
  ADD CONSTRAINT `pagamento_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Restrições para tabelas `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
