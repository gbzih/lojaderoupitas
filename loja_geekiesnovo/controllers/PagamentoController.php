<?php

require_once __DIR__ . '/../models/Pagamento.php';
require_once __DIR__ . '/AuthController.php';

class PagamentoController
{
    // Exibe a página administrativa de pagamentos
    public function index(): void
    {
        $this->onlyAdmin();

        $pagamentoModel = new Pagamento();

        $statusFiltro = $_GET['status'] ?? '';

        $statusPermitidos = [
            '',
            'pendente',
            'aprovado',
            'recusado',
            'cancelado'
        ];

        if (!in_array(
            $statusFiltro,
            $statusPermitidos,
            true
        )) {
            $statusFiltro = '';
        }

        // Lista os pagamentos
        $pagamentos = $pagamentoModel->listarTodos(
            $statusFiltro
        );

        // Indicadores gerais
        $totalPagamentos =
            $pagamentoModel->totalPagamentos();

        $totalAprovados =
            $pagamentoModel->totalPorStatus(
                'aprovado'
            );

        $totalPendentes =
            $pagamentoModel->totalPorStatus(
                'pendente'
            );

        $totalRecusados =
            $pagamentoModel->totalPorStatus(
                'recusado'
            );

        $totalCancelados =
            $pagamentoModel->totalPorStatus(
                'cancelado'
            );

        $totalRecebido =
            $pagamentoModel->totalRecebido();

        $valorPendente =
            $pagamentoModel->totalPendente();

        $resumoFormas =
            $pagamentoModel->resumoPorForma();

        // Pagamento selecionado
        $pagamentoSelecionado = null;

        $erro = null;

        if (isset($_GET['id'])) {

            $idPagamento = filter_var(
                $_GET['id'],
                FILTER_VALIDATE_INT,
                [
                    'options' => [
                        'min_range' => 1,
                        'max_range' => 2147483647
                    ]
                ]
            );

            if ($idPagamento === false) {

                $erro = 'ID do pagamento inválido.';

            } else {

                $pagamentoSelecionado =
                    $pagamentoModel->buscarPorId(
                        $idPagamento
                    );

                if (!$pagamentoSelecionado) {

                    $erro =
                        'Pagamento não encontrado.';
                }
            }
        }

        require_once __DIR__
            . '/../views/pagamentos.php';
    }

    // Restringe o acesso aos administradores
    private function onlyAdmin(): void
    {
        $auth = new AuthController();

        $auth->onlyAdmin();
    }
}