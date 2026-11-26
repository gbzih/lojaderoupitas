<?php

require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/AuthController.php';

class ProdutoController
{
    // Exibe a página administrativa de produtos
    public function index(): void
    {
        $this->onlyAdmin();

        $produtoModel = new Produto();
        $categoriaModel = new Categoria();

        $produtos = $produtoModel->listarComCategoria(false);
        $categorias = $categoriaModel->listarTodas();

        $editar = null;

        if (isset($_GET['id'])) {

            $id = (int) $_GET['id'];

            if ($id > 0) {
                $editar = $produtoModel->buscarPorId($id);
            }
        }

        // Recupera mensagens da sessão
        $erro = $_SESSION['produto_erro'] ?? null;
        $sucesso = $_SESSION['produto_sucesso'] ?? null;

        unset(
            $_SESSION['produto_erro'],
            $_SESSION['produto_sucesso']
        );

        require_once __DIR__ . '/../views/produtos.php';
    }

    // Cadastra ou atualiza um produto
    public function salvar(): void
    {
        $this->onlyAdmin();
        $this->exigirPost();

        $id = (int) ($_POST['id_produto'] ?? 0);

        $categoriaId = (int) ($_POST['id_categoria'] ?? 0);

        $nome = trim($_POST['nome'] ?? '');

        $descricao = trim($_POST['descricao'] ?? '');

        $descricao = $descricao === ''
            ? null
            : $descricao;

        $precoTexto = str_replace(
            ',',
            '.',
            trim($_POST['preco'] ?? '')
        );

        // Validação dos dados principais
        if (
            $id < 0 ||
            $categoriaId <= 0 ||
            $nome === '' ||
            mb_strlen($nome) > 150
        ) {
            $this->erro(
                'Preencha corretamente os dados do produto.',
                $id
            );
        }

        // Validação do preço
        if (
            !preg_match('/^\d{1,8}(\.\d{1,2})?$/', $precoTexto)
        ) {
            $this->erro('Informe um preço válido.', $id);
        }

        $preco = (float) $precoTexto;

        $produtoModel = new Produto();

        // Verifica se a categoria existe
        if (!$produtoModel->categoriaExiste($categoriaId)) {
            $this->erro('Categoria não encontrada.', $id);
        }

        // Valida a imagem antes de salvar
        try {

            $imagem = $this->validarImagem();

        } catch (RuntimeException $e) {

            $this->erro($e->getMessage(), $id);
        }

        try {

            // Atualização de produto existente
            if ($id > 0) {

                if (!$produtoModel->buscarPorId($id)) {
                    $this->erro('Produto não encontrado.');
                }

                $produtoModel->atualizar(
                    $id,
                    $categoriaId,
                    $nome,
                    $descricao,
                    $preco
                );

                $produtoId = $id;

            } else {

                // Validação do estoque inicial
                $estoque = filter_var(
                    $_POST['estoque'] ?? null,
                    FILTER_VALIDATE_INT,
                    [
                        'options' => [
                            'min_range' => 0,
                            'max_range' => 2147483647
                        ]
                    ]
                );

                // Validação do estoque mínimo
                $minimo = filter_var(
                    $_POST['minimo'] ?? 5,
                    FILTER_VALIDATE_INT,
                    [
                        'options' => [
                            'min_range' => 0,
                            'max_range' => 2147483647
                        ]
                    ]
                );

                if ($estoque === false || $minimo === false) {
                    $this->erro(
                        'Informe valores válidos para o estoque.'
                    );
                }

                // Cadastra produto e estoque na mesma transação
                $produtoId = $produtoModel->inserir(
                    $categoriaId,
                    $nome,
                    $descricao,
                    $preco,
                    $estoque,
                    $minimo
                );
            }

        } catch (PDOException $e) {

            error_log(
                'Erro ao salvar produto: ' . $e->getMessage()
            );

            $this->erro(
                'Não foi possível salvar o produto.',
                $id
            );
        }

        // Salva a imagem, caso tenha sido enviada
        if ($imagem !== null) {

            try {

                $this->salvarImagemDoProduto(
                    $produtoId,
                    $imagem
                );

            } catch (RuntimeException $e) {

                $this->erro(
                    'Produto salvo, mas a imagem não pôde ser gravada.',
                    $produtoId
                );
            }
        }

        $_SESSION['produto_sucesso'] = $id > 0
            ? 'Produto atualizado com sucesso!'
            : 'Produto cadastrado com sucesso!';

        $this->redirecionar();
    }

    // Exclui permanentemente um produto
    public function deletar(): void
    {
        $this->onlyAdmin();
        $this->exigirPost();

        $id = (int) ($_POST['id_produto'] ?? 0);

        if ($id <= 0) {
            $this->erro('ID do produto inválido.');
        }

        $produtoModel = new Produto();

        if (!$produtoModel->buscarPorId($id)) {
            $this->erro('Produto não encontrado.');
        }

        try {

            $excluido = $produtoModel->deletar($id);

            if (!$excluido) {
                $this->erro('Produto não encontrado.');
            }

        } catch (PDOException $e) {

            error_log(
                'Erro ao excluir produto: ' . $e->getMessage()
            );

            if ((string) $e->getCode() === '23000') {

                $this->erro(
                    'Este produto possui registros relacionados. Utilize a opção Inativar.'
                );
            }

            $this->erro('Não foi possível excluir o produto.');
        }

        // Remove a imagem somente após excluir o produto
        if (!$this->deletarImagemDoProduto($id)) {

            $this->erro(
                'Produto excluído, mas não foi possível remover sua imagem.'
            );
        }

        $_SESSION['produto_sucesso'] =
            'Produto excluído com sucesso!';

        $this->redirecionar();
    }

    // Ativa ou inativa um produto
    public function toggle(): void
    {
        $this->onlyAdmin();
        $this->exigirPost();

        $id = (int) ($_POST['id_produto'] ?? 0);

        $ativo = $_POST['ativo'] ?? '';

        if (
            $id <= 0 ||
            !in_array($ativo, ['0', '1'], true)
        ) {
            $this->erro('Dados inválidos.');
        }

        $produtoModel = new Produto();

        if (!$produtoModel->buscarPorId($id)) {
            $this->erro('Produto não encontrado.');
        }

        try {

            $produtoModel->setAtivo(
                $id,
                $ativo === '1'
            );

        } catch (PDOException $e) {

            error_log($e->getMessage());

            $this->erro(
                'Não foi possível alterar o status do produto.'
            );
        }

        $_SESSION['produto_sucesso'] = $ativo === '1'
            ? 'Produto ativado com sucesso!'
            : 'Produto inativado com sucesso!';

        $this->redirecionar();
    }

    // Remove a imagem de um produto
    public function removerImagem(): void
    {
        $this->onlyAdmin();
        $this->exigirPost();

        $id = (int) ($_POST['id_produto'] ?? 0);

        if ($id <= 0) {
            $this->erro('ID inválido.');
        }

        $produtoModel = new Produto();

        if (!$produtoModel->buscarPorId($id)) {
            $this->erro('Produto não encontrado.');
        }

        if (!$this->deletarImagemDoProduto($id)) {

            $this->erro(
                'Não foi possível remover a imagem.',
                $id
            );
        }

        $_SESSION['produto_sucesso'] =
            'Imagem removida com sucesso!';

        $this->redirecionar($id);
    }

    // Valida a imagem enviada
    private function validarImagem(): ?array
    {
        if (!isset($_FILES['imagem'])) {
            return null;
        }

        $arquivo = $_FILES['imagem'];

        if ($arquivo['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($arquivo['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException(
                'Erro ao enviar a imagem.'
            );
        }

        // Limite de 2 MB
        if (
            $arquivo['size'] <= 0 ||
            $arquivo['size'] > 2 * 1024 * 1024
        ) {
            throw new RuntimeException(
                'A imagem deve possuir no máximo 2 MB.'
            );
        }

        $tmp = $arquivo['tmp_name'];

        if (!is_uploaded_file($tmp)) {
            throw new RuntimeException(
                'Arquivo enviado inválido.'
            );
        }

        // Verifica o tipo real do arquivo
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        $mime = $finfo->file($tmp);

        $formatos = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        if (!isset($formatos[$mime])) {
            throw new RuntimeException(
                'Utilize imagens JPG, PNG ou WEBP.'
            );
        }

        if (@getimagesize($tmp) === false) {
            throw new RuntimeException(
                'O arquivo enviado não é uma imagem válida.'
            );
        }

        return [
            'tmp' => $tmp,
            'ext' => $formatos[$mime]
        ];
    }

    // Salva a imagem utilizando o ID do produto
    private function salvarImagemDoProduto(
        int $produtoId,
        array $imagem
    ): void {

        $destDir =
            __DIR__ . '/../public/uploads/produtos/';

        if (
            !is_dir($destDir) &&
            !mkdir($destDir, 0755, true) &&
            !is_dir($destDir)
        ) {
            throw new RuntimeException(
                'Não foi possível criar a pasta de imagens.'
            );
        }

        $destino = $destDir
            . $produtoId
            . '.'
            . $imagem['ext'];

        // Salva a nova imagem
        if (!move_uploaded_file($imagem['tmp'], $destino)) {

            throw new RuntimeException(
                'Não foi possível salvar a imagem.'
            );
        }

        // Remove imagens antigas de outros formatos
        foreach (['jpg', 'png', 'webp'] as $ext) {

            if ($ext === $imagem['ext']) {
                continue;
            }

            $antiga = $destDir
                . $produtoId
                . '.'
                . $ext;

            if (is_file($antiga)) {

                if (!unlink($antiga)) {
                    throw new RuntimeException(
                        'Não foi possível remover a imagem anterior.'
                    );
                }
            }
        }
    }

    // Exclui os arquivos de imagem do produto
    private function deletarImagemDoProduto(
        int $produtoId
    ): bool {

        $destDir =
            __DIR__ . '/../public/uploads/produtos/';

        $sucesso = true;

        foreach (['jpg', 'png', 'webp'] as $ext) {

            $arquivo = $destDir
                . $produtoId
                . '.'
                . $ext;

            if (is_file($arquivo)) {

                if (!unlink($arquivo)) {
                    $sucesso = false;
                }
            }
        }

        return $sucesso;
    }

    // Permite somente requisições POST
    private function exigirPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            http_response_code(405);

            exit('Método não permitido.');
        }
    }

    // Permite acesso somente ao administrador
    private function onlyAdmin(): void
    {
        $auth = new AuthController();

        $auth->onlyAdmin();
    }

    // Armazena a mensagem de erro
    private function erro(
        string $mensagem,
        int $id = 0
    ): void {

        $_SESSION['produto_erro'] = $mensagem;

        $this->redirecionar($id);
    }

    // Redireciona para a página de produtos
    private function redirecionar(int $id = 0): void
    {
        $url = 'index.php?controller=produto&action=index';

        if ($id > 0) {
            $url .= '&id=' . $id;
        }

        header('Location: ' . $url);

        exit;
    }
}