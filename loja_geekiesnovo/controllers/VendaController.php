<?php

require_once __DIR__ . '/../models/Venda.php';
require_once __DIR__ . '/AuthController.php';

class VendaController
{
    // Exibe a página administrativa de vendas
    public function index(): void
    {
        $this->onlyAdmin();

        $vendaModel = new Venda();

        $inicio = trim($_GET['inicio'] ?? '');

        $fim = trim($_GET['fim'] ?? '');

        $erro = null;

        // Indicadores gerais
        $totalVendas = $vendaModel->totalVendas();

        $totalUnidades = $vendaModel->totalUnidades();

        $faturamentoTotal =
            $vendaModel->faturamentoTotal();

        $faturamentoHoje =
            $vendaModel->faturamentoHoje();

        $maisVendidos =
            $vendaModel->produtosMaisVendidos();

        // Filtro por período
        if ($inicio !== '' || $fim !== '') {

            if (
                !$this->validarData($inicio) ||
                !$this->validarData($fim)
            ) {

                $erro =
                    'Informe uma data inicial e uma data final válidas.';

                $vendas = $vendaModel->listarTodas();

            } elseif ($inicio > $fim) {

                $erro =
                    'A data inicial não pode ser maior que a data final.';

                $vendas = $vendaModel->listarTodas();

            } else {

                $vendas = $vendaModel->listarPorPeriodo(
                    $inicio,
                    $fim
                );
            }

        } else {

            $vendas = $vendaModel->listarTodas();
        }

        // Calcula o resumo das vendas exibidas
        $totalPeriodo = 0;

        $unidadesPeriodo = 0;

        foreach ($vendas as $venda) {

            $totalPeriodo +=
                (float) $venda['valor_total'];

            $unidadesPeriodo +=
                (int) $venda['quantidade'];
        }

        require_once __DIR__ . '/../views/vendas.php';
    }

    // Valida uma data no formato AAAA-MM-DD
    private function validarData(string $data): bool
    {
        if ($data === '') {

            return false;
        }

        $resultado = DateTime::createFromFormat(
            '!Y-m-d',
            $data
        );

        return $resultado !== false
            && $resultado->format('Y-m-d') === $data;
    }

    // Restringe o acesso aos administradores
    private function onlyAdmin(): void
    {
        $auth = new AuthController();

        $auth->onlyAdmin();
    }
}