<?php

require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/AuthController.php';

class PedidoController
{
    public function index(): void
    {
        $this->onlyAdmin();

        $pedidoModel = new Pedido();

        $statusFiltro = $this->validarFiltro(
            $_GET['status'] ?? ''
        );

        $pedidos = $pedidoModel->listarTodos(
            $statusFiltro
        );

        $pedidoSelecionado = null;
        $itens = [];
        $pagamentos = [];

        $erro = $_SESSION['pedido_erro'] ?? null;
        $sucesso = $_SESSION['pedido_sucesso'] ?? null;

        unset(
            $_SESSION['pedido_erro'],
            $_SESSION['pedido_sucesso']
        );

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

            if ($idPedido === false) {

                $erro = 'ID do pedido inválido.';

            } else {

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
            }
        }

        if (empty($_SESSION['pedido_csrf'])) {

            $_SESSION['pedido_csrf'] =
                bin2hex(random_bytes(32));
        }

        $csrf = $_SESSION['pedido_csrf'];

        require_once __DIR__ . '/../views/pedidos.php';
    }

    public function atualizarStatus(): void
    {
        $this->onlyAdmin();
        $this->exigirPost();

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

        $statusFiltro = $this->validarFiltro(
            $_POST['status_filtro'] ?? ''
        );

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
                'Dados inválidos para atualização do pedido.',
                0,
                $statusFiltro
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
                $idPedido,
                $statusFiltro
            );

        } catch (RuntimeException $e) {

            $this->erro(
                $e->getMessage(),
                $idPedido,
                $statusFiltro
            );
        }

        $_SESSION['pedido_sucesso'] =
            'Situação do pedido atualizada com sucesso!';

        $this->redirecionar(
            $idPedido,
            $statusFiltro
        );
    }

    private function validarFiltro($status): string
    {
        $permitidos = [
            '',
            'pendente',
            'pago',
            'enviado',
            'entregue',
            'cancelado'
        ];

        return in_array($status, $permitidos, true)
            ? $status
            : '';
    }

    private function onlyAdmin(): void
    {
        $auth = new AuthController();

        $auth->onlyAdmin();
    }

    private function exigirPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            http_response_code(405);
            exit('Método não permitido.');
        }
    }

    private function erro(
        string $mensagem,
        int $idPedido = 0,
        string $statusFiltro = ''
    ): void {

        $_SESSION['pedido_erro'] = $mensagem;

        $this->redirecionar(
            $idPedido,
            $statusFiltro
        );
    }

    private function redirecionar(
        int $idPedido = 0,
        string $statusFiltro = ''
    ): void {

        $url = 'index.php?controller=pedido&action=index';

        if ($statusFiltro !== '') {

            $url .= '&status=' . urlencode($statusFiltro);
        }

        if ($idPedido > 0) {

            $url .= '&id=' . $idPedido;
        }

        header('Location: ' . $url);
        exit;
    }
}