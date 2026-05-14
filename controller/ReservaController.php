<?php
require_once __DIR__ . '/../dao/ReservaDAO.php';
require_once __DIR__ . '/../dao/VeiculoDAO.php';
require_once __DIR__ . '/../dao/ClienteDAO.php';
require_once __DIR__ . '/../config/Helpers.php';

class ReservaController {

    public function listar() {
        return (new ReservaDAO())->listar();
    }

    public function buscarPorId($id) {
        return (new ReservaDAO())->buscarPorId((int)$id);
    }

    public function salvar($veiculoId, $clienteId, $dataRetirada, $dataDevolucao) {
        $veiculoId = (int)$veiculoId;
        $clienteId = (int)$clienteId;

        $inicio = parse_data($dataRetirada);
        $fim = parse_data($dataDevolucao);
        if ($fim < $inicio) throw new Exception('Devolução não pode ser anterior à retirada.');

        $veiculo = (new VeiculoDAO())->buscarPorId($veiculoId);
        if (!$veiculo) throw new Exception('Veículo não encontrado.');
        if ($veiculo->getStatus() !== 'ativo') throw new Exception('Veículo não está ativo.');

        $cliente = (new ClienteDAO())->buscarPorId($clienteId);
        if (!$cliente) throw new Exception('Cliente não encontrado.');

        $dao = new ReservaDAO();
        if (!$dao->estaDisponivel($veiculoId, $dataRetirada, $dataDevolucao, null)) {
            throw new Exception('Veículo indisponível no período.');
        }

        $qtdDiarias = diarias($inicio, $fim);
        $valorDaDiaria = (float)$veiculo->getValorDaDiaria();
        $valorEstimado = $qtdDiarias * $valorDaDiaria;

        $r = new Reserva($veiculoId, $clienteId, $dataRetirada, $dataDevolucao, $valorDaDiaria, $valorEstimado, 'reservado');
        $dao->salvar($r);

        return $valorEstimado;
    }

    public function atualizar($id, $veiculoId, $clienteId, $dataRetirada, $dataDevolucao) {
        $id = (int)$id;
        $veiculoId = (int)$veiculoId;
        $clienteId = (int)$clienteId;

        $dao = new ReservaDAO();
        $atual = $dao->buscarPorId($id);
        if (!$atual) throw new Exception('Reserva não encontrada.');
        if ($atual['status'] !== 'reservado') throw new Exception('Só é permitido editar quando status é reservado.');

        $inicio = parse_data($dataRetirada);
        $fim = parse_data($dataDevolucao);
        if ($fim < $inicio) throw new Exception('Devolução não pode ser anterior à retirada.');

        $veiculo = (new VeiculoDAO())->buscarPorId($veiculoId);
        if (!$veiculo) throw new Exception('Veículo não encontrado.');
        if ($veiculo->getStatus() !== 'ativo') throw new Exception('Veículo não está ativo.');

        $cliente = (new ClienteDAO())->buscarPorId($clienteId);
        if (!$cliente) throw new Exception('Cliente não encontrado.');

        if (!$dao->estaDisponivel($veiculoId, $dataRetirada, $dataDevolucao, $id)) {
            throw new Exception('Veículo indisponível no período.');
        }

        $qtdDiarias = diarias($inicio, $fim);
        $valorDaDiaria = (float)$veiculo->getValorDaDiaria();
        $valorEstimado = $qtdDiarias * $valorDaDiaria;

        $r = new Reserva($veiculoId, $clienteId, $dataRetirada, $dataDevolucao, $valorDaDiaria, $valorEstimado, 'reservado');
        $r->setId($id);
        $dao->atualizarBasico($r);

        return $valorEstimado;
    }

    public function cancelar($id) {
        $dao = new ReservaDAO();
        $row = $dao->buscarPorId((int)$id);
        if (!$row) throw new Exception('Reserva não encontrada.');
        if (!in_array($row['status'], ['reservado'], true)) throw new Exception('Só cancela reserva ativa.');
        $dao->atualizarStatus((int)$id, 'cancelado');
    }

    public function finalizar($id) {
        $dao = new ReservaDAO();
        $row = $dao->buscarPorId((int)$id);
        if (!$row) throw new Exception('Reserva não encontrada.');
        if ($row['status'] !== 'reservado') throw new Exception('Só finaliza quando status é reservado.');

        // Para demonstração: finaliza usando a data_devolucao cadastrada.
$inicio = parse_data($row['data_retirada']);
        $fim = parse_data($row['data_devolucao']);
        $qtdDiarias = diarias($inicio, $fim);
        $valorFinal = $qtdDiarias * (float)$row['valor_diaria'];

        $dao->finalizar((int)$id, $row['data_devolucao'], $valorFinal);
        return $valorFinal;
    }

    public function deletar($id) {
        (new ReservaDAO())->deletar((int)$id);
    }
}
