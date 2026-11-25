<?php

require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/AuthController.php';

class ClienteController
{
    // Exibe a página administrativa de clientes
    public function index(): void
    {
        $this->onlyAdmin();

        $clienteModel = new Cliente();

        $pedidoModel = new Pedido();

        // Recupera o termo pesquisado
        $busca = $_GET['busca'] ?? '';

        if (!is_string($busca)) {
            $busca = '';
        }

        $busca = trim($busca);

        $erro = null;

        if (mb_strlen($busca) > 150) {

            $erro = 'O termo de pesquisa é muito longo.';

            $busca = '';
        }

        // Lista os clientes
        $clientes = $clienteModel->listarTodos(
            $busca
        );

        // Indicadores gerais
        $totalClientes =
            $clienteModel->totalClientes();

        $totalComPedidos =
            $clienteModel->totalComPedidos();

        $totalComCompras =
            $clienteModel->totalComCompras();

        $totalSemPedidos = max(
            0,
            $totalClientes - $totalComPedidos
        );

        // Dados do cliente selecionado
        $clienteSelecionado = null;

        $pedidosCliente = [];

        $resumoCliente = [];

        // Verifica se um cliente foi selecionado
        if (isset($_GET['id'])) {

            $idCliente = filter_var(
                $_GET['id'],
                FILTER_VALIDATE_INT,
                [
                    'options' => [
                        'min_range' => 1,
                        'max_range' => 2147483647
                    ]
                ]
            );

            if ($idCliente === false) {

                $erro = 'ID do cliente inválido.';

            } else {

                $clienteSelecionado =
                    $clienteModel->buscarPorId(
                        $idCliente
                    );

                if (!$clienteSelecionado) {

                    $erro = 'Cliente não encontrado.';

                } else {

                    // Histórico de pedidos
                    $pedidosCliente =
                        $pedidoModel->listarPorCliente(
                            $idCliente
                        );

                    // Resumo das compras
                    $resumoCliente =
                        $clienteModel->resumoCompras(
                            $idCliente
                        );
                }
            }
        }

        require_once __DIR__
            . '/../views/clientes.php';
    }

    // Restringe o acesso aos administradores
    private function onlyAdmin(): void
    {
        $auth = new AuthController();

        $auth->onlyAdmin();
    }
}