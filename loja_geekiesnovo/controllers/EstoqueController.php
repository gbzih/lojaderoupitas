<?php

require_once __DIR__ . '/../models/Estoque.php';
require_once __DIR__ . '/AuthController.php';

class EstoqueController
{
    // Exibe a página administrativa de estoque
    public function index(): void
    {
        $this->onlyAdmin();

        $estoqueModel = new Estoque();

        $estoques = $estoqueModel->listarComProdutos();

        $editar = null;

        if (isset($_GET['id'])) {

            $id = (int) $_GET['id'];

            if ($id > 0) {
                $editar = $estoqueModel->buscarPorId($id);
            }
        }

        // Recupera as mensagens da sessão
        $erro = $_SESSION['estoque_erro'] ?? null;

        $sucesso = $_SESSION['estoque_sucesso'] ?? null;

        unset(
            $_SESSION['estoque_erro'],
            $_SESSION['estoque_sucesso']
        );

        require_once __DIR__ . '/../views/estoques.php';
    }

    // Atualiza a quantidade e o estoque mínimo
    public function salvar(): void
    {
        $this->onlyAdmin();
        $this->exigirPost();

        $idProduto = (int) ($_POST['id_produto'] ?? 0);

        $quantidade = $this->validarInteiro(
            $_POST['quantidade'] ?? null
        );

        $minimo = $this->validarInteiro(
            $_POST['minimo'] ?? null
        );

        // Validação dos dados
        if (
            $idProduto <= 0 ||
            $quantidade === false ||
            $minimo === false
        ) {
            $this->erro(
                'Informe valores válidos para a quantidade e o estoque mínimo.',
                $idProduto
            );
        }

        $estoqueModel = new Estoque();

        try {

            if (!$estoqueModel->buscarPorId($idProduto)) {

                $this->erro('Produto não encontrado.');
            }

            $estoqueModel->atualizar(
                $idProduto,
                $quantidade,
                $minimo
            );

        } catch (PDOException $e) {

            error_log(
                'Erro ao atualizar estoque: ' .
                $e->getMessage()
            );

            $this->erro(
                'Não foi possível atualizar o estoque.',
                $idProduto
            );

        } catch (RuntimeException $e) {

            $this->erro(
                $e->getMessage(),
                $idProduto
            );
        }

        $_SESSION['estoque_sucesso'] =
            'Estoque atualizado com sucesso!';

        $this->redirecionar();
    }

    // Realiza uma entrada ou saída manual
    public function movimentar(): void
    {
        $this->onlyAdmin();
        $this->exigirPost();

        $idProduto = (int) ($_POST['id_produto'] ?? 0);

        $tipo = $_POST['tipo'] ?? '';

        $quantidade = $this->validarInteiro(
            $_POST['quantidade'] ?? null,
            1
        );

        // Valida os dados da movimentação
        if (
            $idProduto <= 0 ||
            $quantidade === false ||
            !in_array($tipo, ['entrada', 'saida'], true)
        ) {
            $this->erro(
                'Dados inválidos para a movimentação.',
                $idProduto
            );
        }

        $estoqueModel = new Estoque();

        try {

            $estoqueModel->movimentar(
                $idProduto,
                $tipo,
                $quantidade
            );

        } catch (PDOException $e) {

            error_log(
                'Erro na movimentação de estoque: ' .
                $e->getMessage()
            );

            $this->erro(
                'Não foi possível realizar a movimentação.',
                $idProduto
            );

        } catch (RuntimeException $e) {

            $this->erro(
                $e->getMessage(),
                $idProduto
            );
        }

        $_SESSION['estoque_sucesso'] = $tipo === 'entrada'
            ? 'Entrada registrada com sucesso!'
            : 'Saída realizada com sucesso!';

        $this->redirecionar();
    }

    // Valida números inteiros
    private function validarInteiro(
        $valor,
        int $minimo = 0
    ) {
        return filter_var(
            $valor,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => $minimo,
                    'max_range' => 2147483647
                ]
            ]
        );
    }

    // Restringe o acesso aos administradores
    private function onlyAdmin(): void
    {
        $auth = new AuthController();

        $auth->onlyAdmin();
    }

    // Permite somente requisições POST
    private function exigirPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            http_response_code(405);

            exit('Método não permitido.');
        }
    }

    // Armazena mensagens de erro
    private function erro(
        string $mensagem,
        int $idProduto = 0
    ): void {

        $_SESSION['estoque_erro'] = $mensagem;

        $this->redirecionar($idProduto);
    }

    // Redireciona para a página de estoque
    private function redirecionar(
        int $idProduto = 0
    ): void {

        $url = 'index.php?controller=estoque&action=index';

        if ($idProduto > 0) {

            $url .= '&id=' . $idProduto;
        }

        header('Location: ' . $url);

        exit;
    }
}