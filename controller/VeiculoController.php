<?php
require_once __DIR__ . '/../dao/VeiculoDAO.php';
require_once __DIR__ . '/../config/Helpers.php';

class VeiculoController {
    public function salvar($placa, $marca, $modelo, $ano, $categoria, $valorDaDiaria, $status) {
        $v = new Veiculo($placa, $marca, $modelo, $ano, $categoria, $valorDaDiaria, $status);
        (new VeiculoDAO())->salvar($v);
    }

    public function listar() {
        return (new VeiculoDAO())->listar();
    }

    public function listarAtivos() {
        return (new VeiculoDAO())->listarAtivos();
    }

    public function atualizar($id, $placa, $marca, $modelo, $ano, $categoria, $valorDaDiaria, $status) {
        $v = new Veiculo($placa, $marca, $modelo, $ano, $categoria, $valorDaDiaria, $status);
        $v->setId((int)$id);
        (new VeiculoDAO())->atualizar($v);
    }

    public function deletar($id) {
        (new VeiculoDAO())->deletar((int)$id);
    }

    public function buscarPorId($id) {
        return (new VeiculoDAO())->buscarPorId((int)$id);
    }

    public function buscarDisponiveis($dataRetirada, $dataDevolucao) {
        return (new VeiculoDAO())->buscarDisponiveis($dataRetirada, $dataDevolucao);
    }

    public function buscarDisponiveisSemData() {
        return (new VeiculoDAO())->buscarDisponiveisSemData();
    }
}
