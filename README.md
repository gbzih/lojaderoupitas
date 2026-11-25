# Portal Geekies/Loja Geekies

Sistema web desenvolvido para gerenciamento de e-commerce e controle de inventário voltado para o universo geek (quadrinhos, mangás, HQS, etc).

-----------------------------------------------------------------------------------------------------------------------------------------------

## Funcionalidades

### - Autenticação & Perfis
- **Administrador:** Controle total do sistema. Gerencia o catálogo (produtos e categorias), monitora o estoque, registra entradas de mercadorias e administra os usuários cadastrados.
- **Cliente:** Navega pelo catálogo, busca por itens, gerencia o carrinho de compras, realiza pedidos e efetua o pagamento de forma integrada.

### - Gestão de Catálogo & Estoque
- Operações de CRUD completas para produtos e categorias.
- Controle dinâmico de status (**Ativo/Inativo**).
- Gestão de estoque com monitoramento de quantidade e baixa automática após a confirmação das vendas.

### - Fluxo de E-commerce
- Adição e remoção de itens no carrinho virtual.
- Conversão de itens do carrinho em pedido oficial de compra.
- Registro de pagamentos e consulta de histórico de pedidos.

-----------------------------------------------------------------------------------------------------------------------------------------------

## - Tecnologias Utilizadas

- **Backend:** PHP
- **Banco de Dados:** MySQL (utilizando HeidiSQL para gerenciamento)
- **Frontend:** HTML5, CSS
- **Modelagem:** DER, Diagrama Lógico e Diagramas de Classes (UML)

-----------------------------------------------------------------------------------------------------------------------------------------------

## - Como Executar

1. Clone ou baixe os arquivos do projeto para o seu diretório de servidor local (como o `htdocs` do XAMPP).
2. Abra o **HeidiSQL** (ou phpMyAdmin), crie um banco de dados chamado `loja_geekies` e importe o script para dentro.
3. Ajuste as credenciais de conexão com o banco de dados no arquivo de configuração do PHP (caso necessário).
4. Inicie o Apache/MySQL no seu servidor local e acesse o projeto pelo navegador.
