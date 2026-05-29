<?php
-- ===================================
-- TABELA: usuario  ← tabela incluída para suportar a FK em venda
-- ===================================
CREATE TABLE usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  perfil ENUM('admin','vendedor') NOT NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- ===================================
-- TABELA: fornecedor
-- ===================================

CREATE TABLE fornecedor (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  cnpj VARCHAR(20) NOT NULL,
  telefone VARCHAR(20),
  email VARCHAR(100),
  endereco VARCHAR(255)
) ENGINE=InnoDB;
 
-- ===================================
-- TABELA: cliente
-- ===================================

CREATE TABLE cliente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  cpf_cnpj VARCHAR(20) NOT NULL,
  telefone VARCHAR(20),
  email VARCHAR(100),
  endereco VARCHAR(255)
) ENGINE=InnoDB;
 

-- ===================================
-- TABELA: categoria 
-- ===================================

CREATE TABLE categoria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;
 
-- ===================================
-- TABELA: produto
-- ===================================

CREATE TABLE produto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT NOT NULL,
  nome VARCHAR(100) NOT NULL,
  descricao TEXT,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  CONSTRAINT fk_produto_categoria
    FOREIGN KEY (categoria_id)
    REFERENCES categoria(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;
-- 

-- ===================================
-- TABELA: variacao
-- ===================================

CREATE TABLE variacao (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produto_id INT NOT NULL,
  tamanho VARCHAR(10) NOT NULL,
  cor VARCHAR(30) NOT NULL,
  sku VARCHAR(50) NOT NULL UNIQUE,
  preco DECIMAL(10,2) NOT NULL,
  CONSTRAINT fk_variacao_produto
    FOREIGN KEY (produto_id)
    REFERENCES produto(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;
-- ===================================
-- TABELA: estoque
-- ===================================
CREATE TABLE estoque (
  id INT AUTO_INCREMENT PRIMARY KEY,
  variacao_id INT NOT NULL UNIQUE,
  quantidade INT NOT NULL DEFAULT 0,
  minimo INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_estoque_variacao
    FOREIGN KEY (variacao_id)
    REFERENCES variacao(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;
 
-- ===================================
-- ENTRADA DE MERCADORIA
-- ===================================

CREATE TABLE entrada_mercadoria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fornecedor_id INT NOT NULL,
  data DATE NOT NULL,
  status ENUM('rascunho','confirmada') NOT NULL DEFAULT 'rascunho',
  valor_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
 
  CONSTRAINT fk_entrada_fornecedor
	FOREIGN KEY (fornecedor_id)
    REFERENCES fornecedor(id)
    ON UPDATE CASCADE
	ON DELETE RESTRICT,
 
  INDEX idx_entrada_fornecedor (fornecedor_id),
  INDEX idx_entrada_data (data),
  INDEX idx_entrada_status (status)
) ENGINE=InnoDB;
 

-- ===================================
-- ITENS DA ENTRADA
-- ===================================

CREATE TABLE entrada_item (
  id INT AUTO_INCREMENT PRIMARY KEY,
  entrada_id INT NOT NULL,
  variacao_id INT NOT NULL,
  quantidade INT NOT NULL,
  custo_unitario DECIMAL(10,2) NOT NULL,
 
  CONSTRAINT fk_entrada_item_entrada
	FOREIGN KEY (entrada_id)
	REFERENCES entrada_mercadoria(id)
	ON UPDATE CASCADE
    ON DELETE CASCADE,
 
  CONSTRAINT fk_entrada_item_variacao
    FOREIGN KEY (variacao_id)
    REFERENCES variacao(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
 
  INDEX idx_entrada_item_entrada (entrada_id),
  INDEX idx_entrada_item_variacao (variacao_id)
) ENGINE=InnoDB;
 


-- ===================================
-- NOTA FISCAL DE ENTRADA 
-- ===================================

CREATE TABLE nota_fiscal_entrada (
  id INT AUTO_INCREMENT PRIMARY KEY,
  entrada_id INT NOT NULL UNIQUE,
  modelo VARCHAR(5) NOT NULL,
  serie VARCHAR(5) NOT NULL,
  numero VARCHAR(10) NOT NULL,
  chave_acesso VARCHAR(44) NOT NULL UNIQUE,
  data_emissao DATE NOT NULL,
  valor_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
 
  CONSTRAINT fk_nf_entrada_entrada
	FOREIGN KEY (entrada_id)
	REFERENCES entrada_mercadoria(id)
	ON UPDATE CASCADE
    ON DELETE CASCADE,
 
  INDEX idx_nf_entrada_data (data_emissao)
) ENGINE=InnoDB;

 
-- ===================================
-- VENDA 
-- ===================================

CREATE TABLE venda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  cliente_id INT NULL,
  data DATE NOT NULL,
  status ENUM('aberta','finalizada') NOT NULL DEFAULT 'aberta',
  valor_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
 
  CONSTRAINT fk_venda_usuario
    FOREIGN KEY (usuario_id)
    REFERENCES usuario(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
 
  CONSTRAINT fk_venda_cliente
    FOREIGN KEY (cliente_id)
    REFERENCES cliente(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
 
  INDEX idx_venda_usuario (usuario_id),
  INDEX idx_venda_cliente (cliente_id),
  INDEX idx_venda_data (data),
  INDEX idx_venda_status (status)
) ENGINE=InnoDB;
 
-- ===================================
-- ITENS DA VENDA -- 
-- ===================================
CREATE TABLE venda_item (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venda_id INT NOT NULL,
  variacao_id INT NOT NULL,
  quantidade INT NOT NULL,
  preco_unitario DECIMAL(10,2) NOT NULL,
 
  CONSTRAINT fk_venda_item_venda
	FOREIGN KEY (venda_id)
    REFERENCES venda(id)
    ON UPDATE CASCADE
	ON DELETE CASCADE,
 
  CONSTRAINT fk_venda_item_variacao
	FOREIGN KEY (variacao_id)
    REFERENCES variacao(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
 
  INDEX idx_venda_item_venda (venda_id),
  INDEX idx_venda_item_variacao (variacao_id)
) ENGINE=InnoDB;

-- ===================================
-- NOTA FISCAL DE VENDA
-- ===================================

CREATE TABLE nota_fiscal_venda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venda_id INT NOT NULL UNIQUE,
  modelo VARCHAR(5) NOT NULL,
  serie VARCHAR(5) NOT NULL,
  numero VARCHAR(10) NOT NULL UNIQUE,
  data_emissao DATE NOT NULL,
  valor_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
 
  CONSTRAINT fk_nf_venda_venda
    FOREIGN KEY (venda_id)
    REFERENCES venda(id)
    ON UPDATE CASCADE
	ON DELETE CASCADE,
 
  INDEX idx_nf_venda_data (data_emissao)
) ENGINE=InnoDB;

-- ===================================
-- MOVIMENTO DE ESTOQUE 
-- ===================================

CREATE TABLE movimento_estoque (
  id INT AUTO_INCREMENT PRIMARY KEY,
  variacao_id INT NOT NULL,
  tipo ENUM('entrada','saida') NOT NULL,
  quantidade INT NOT NULL,
  origem ENUM('entrada','venda') NOT NULL,
  origem_id INT NOT NULL,
  data DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 
  CONSTRAINT fk_movimento_variacao
    FOREIGN KEY (variacao_id)
    REFERENCES variacao(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
 
  INDEX idx_mov_variacao (variacao_id),
  INDEX idx_mov_data (data),
  INDEX idx_mov_origem (origem, origem_id),
  INDEX idx_mov_tipo (tipo)
) ENGINE=InnoDB; 


INSERT INTO usuario (nome, email, senha, perfil, ativo) VALUES
('Admin Cosplay', 'admin@cosplay.com', '$2y$10$COLE_AQUI_O_HASH_DO_123456', 'admin', 1),
('Vendedor Cosplay', 'vendedor@cosplay.com', '$2y$10$COLE_AQUI_O_HASH_DO_123456', 'vendedor', 1);


INSERT INTO fornecedor (nome, cnpj, telefone, email, endereco) VALUES
('Cosplay Imports LTDA', '12.345.678/0001-90', '(21) 99999-1111', 'contato@cosplayimports.com', 'Rio de Janeiro - RJ');

INSERT INTO cliente (nome, cpf_cnpj, telefone, email, endereco) VALUES
('Cliente Balcao', '000.000.000-00', '(21) 90000-0000', 'balcao@cliente.com', 'Nova Iguacu - RJ');


INSERT INTO categoria (nome, ativo) VALUES
('Fantasias Completas', 1),
('Uniformes de Anime e Manga', 1),
('Capas e Mantos', 1),
('Acessorios', 1),
('Perucas', 1),
('Calcados Cosplay', 1),
('Props Cenograficos', 1),
('Armaduras', 1),
('Materiais para Cosplay', 1),
('Kits Cosplay', 1),
('Promocoes', 1);

INSERT INTO produto (categoria_id, nome, descricao, ativo) VALUES
(1, 'Fantasia Ninja Sombria', 'Fantasia completa estilo anime, tecido leve.', 1),
(5, 'Peruca Azul Longa', 'Peruca resistente ao calor, ideal para eventos.', 1),
(3, 'Manto Vermelho Akai', 'Manto com simbolos bordados, tamanho unico.', 1),
(4, 'Kit Acessorios Steampunk', 'Oculos + luvas + cinto tematico.', 1);


INSERT INTO variacao (produto_id, tamanho, cor, sku, preco) VALUES
(1, 'P',  'Preto', 'COS-NINJA-P-PRETO', 189.90),
(1, 'M',  'Preto', 'COS-NINJA-M-PRETO', 189.90),
(1, 'G',  'Preto', 'COS-NINJA-G-PRETO', 189.90),

(2, 'U',  'Azul',  'COS-PERUCA-U-AZUL',  99.90),

(3, 'U',  'Vermelho', 'COS-MANTO-U-VERM', 149.90),

(4, 'U',  'Marrom', 'COS-STEAM-U-MARR', 79.90);
?>
