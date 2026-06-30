🛒 Loja Nerdis - Sistema de Gerenciamento de Produtos

Um sistema web completo, responsivo e seguro para o gerenciamento de inventário e controle de acesso de uma loja, desenvolvido em PHP.
---

## 🚀 Funcionalidades

### 🔐 Autenticação & Níveis de Acesso
- **Administrador (admin):** Possui controle total do sistema. Pode cadastrar, editar, ativar/inativar e **excluir** produtos, além de gerenciar categorias e mídias.
- **Vendedor (vendedor):** Tem limite limitado. Pode visualizar a lista de itens, cadastrar novos produtos e editar informações (correção de estoque/preços), mas possui restrição de segurança para **não excluir** dados do sistema.

### 📦 Gestão de Produtos
- Operações completas de CRUD (Criar, Ler, Atualizar e Deletar).
- Controle dinâmico de Status (**Ativo/Inativo**).
- Vinculação inteligente com Categorias ativas do banco de dados.
- Tratamento de valores monetários (Preço) e quantidades (Estoque).
- Upload seguro de fotos de produtos em formatos otimizados (`.jpg`, `.png`, `.webp`).
  
---

## 🛠️ Tecnologias Utilizadas

* **Backend:** PHP
* **Banco de Dados:** MySQL, HeidiSQL
* **Frontend:** HTML, CSS
