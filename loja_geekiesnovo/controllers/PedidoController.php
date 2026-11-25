<?php

require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/AuthController.php';

class PedidoController
{
    // Exibe a página administrativa de pedidos
    public function index(): void
    {
        $this->onlyAdmin();

        $pedidoModel = new Pedido();

        $statusFiltro = $_GET['status'] ?? '';

        $statusPermitidos = [
            '',
            'pendente',
            'pago',
            'enviado',
            'entregue',
            'cancelado'
        ];

        if (
            !in_array(
                $statusFiltro,
                $statusPermitidos,
                true
            )
        ) {

            $statusFiltro = '';
        }

        // Lista os pedidos
        $pedidos = $pedidoModel->listarTodos(
            $statusFiltro
        );

        // Indicadores gerais
        $totalPedidos = $pedidoModel->totalPedidos();

        $totalPendentes = $pedidoModel->totalPorStatus(
            'pendente'
        );

        $totalPagos = $pedidoModel->totalPorStatus(
            'pago'
        );

        $totalEntregues = $pedidoModel->totalPorStatus(
            'entregue'
        );

        // Dados do pedido selecionado
        $pedidoSelecionado = null;

        $itens = [];

        $pagamentos = [];

        // Recupera mensagens da sessão
        $erro = $_SESSION['pedido_erro'] ?? null;

        $sucesso = $_SESSION['pedido_sucesso'] ?? null;

        unset(
            $_SESSION['pedido_erro'],
            $_SESSION['pedido_sucesso']
        );

        // Verifica se um pedido foi selecionado
        if (isset($_GET['id'])) {

            $idPedido = filter_var(
                $_GET['id'],
                FILTER_VALIDATE_INT,
                [
                    'options' => [
                        'min_range' => 1,
                        'max_range' => 2147483647
                    ]
                ]
            );

            if ($idPedido !== false) {

                $pedidoSelecionado =
                    $pedidoModel->buscarPorId($idPedido);

                if ($pedidoSelecionado) {

                    $itens = $pedidoModel->listarItens(
                        $idPedido
                    );

                    $pagamentos =
                        $pedidoModel->listarPagamentos(
                            $idPedido
                        );

                } else {

                    $erro = 'Pedido não encontrado.';
                }

            } else {

                $erro = 'ID do pedido inválido.';
            }
        }

        // Gera token de proteção CSRF
        if (empty($_SESSION['pedido_csrf'])) {

            $_SESSION['pedido_csrf'] =
                bin2hex(random_bytes(32));
        }

        $csrf = $_SESSION['pedido_csrf'];

        require_once __DIR__ . '/../views/pedidos.php';
    }

    // Atualiza a situação do pedido
    public function atualizarStatus(): void
    {
        $this->onlyAdmin();

        $this->exigirPost();

        // Valida o token CSRF
        $token = $_POST['csrf'] ?? '';

        if (
            !is_string($token) ||
            empty($_SESSION['pedido_csrf']) ||
            !hash_equals(
                $_SESSION['pedido_csrf'],
                $token
            )
        ) {

            http_response_code(403);

            exit('Requisição inválida.');
        }

        $idPedido = filter_var(
            $_POST['id_pedido'] ?? null,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1,
                    'max_range' => 2147483647
                ]
            ]
        );

        $novoStatus = $_POST['status'] ?? '';

        // O pagamento será aprovado em outro módulo
        $statusPermitidos = [
            'enviado',
            'entregue',
            'cancelado'
        ];

        if (
            $idPedido === false ||
            !in_array(
                $novoStatus,
                $statusPermitidos,
                true
            )
        ) {

            $this->erro(
                'Dados inválidos para atualização do pedido.'
            );
        }

        try {

            $pedidoModel = new Pedido();

            $pedidoModel->atualizarStatus(
                $idPedido,
                $novoStatus
            );

        } catch (PDOException $e) {

            error_log(
                'Erro ao atualizar pedido: ' .
                $e->getMessage()
            );

            $this->erro(
                'Não foi possível atualizar o pedido.',
                $idPedido
            );

        } catch (RuntimeException $e) {

            $this->erro(
                $e->getMessage(),
                $idPedido
            );
        }

        $_SESSION['pedido_sucesso'] =
            'Situação do pedido atualizada com sucesso!';

        $this->redirecionar($idPedido);
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

    // Armazena mensagem de erro
    private function erro(
        string $mensagem,
        int $idPedido = 0
    ): void {

        $_SESSION['pedido_erro'] = $mensagem;

        $this->redirecionar($idPedido);
    }

    // Redireciona para a página de pedidos
    private function redirecionar(
        int $idPedido = 0
    ): void {

        $url = 'index.php?controller=pedido&action=index';

        if ($idPedido > 0) {

            $url .= '&id=' . $idPedido;
        }

        header('Location: ' . $url);

        exit;
    }
}