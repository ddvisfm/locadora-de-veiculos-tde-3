<?php
require_once __DIR__ . '/../dao/ClienteDAO.php';
require_once __DIR__ . '/../config/Helpers.php';

class ClienteController {
    public function salvar($nome, $cpf, $cnh, $contato) {
        $c = new Cliente($nome, $cpf, $cnh, $contato);
        (new ClienteDAO())->salvar($c);
    }

    public function listar() {
        return (new ClienteDAO())->listar();
    }

    public function atualizar($id, $nome, $cpf, $cnh, $contato) {
        $c = new Cliente($nome, $cpf, $cnh, $contato);
        $c->setId((int)$id);
        (new ClienteDAO())->atualizar($c);
    }

    public function deletar($id) {
        (new ClienteDAO())->deletar((int)$id);
    }

    public function buscarPorId($id) {
        return (new ClienteDAO())->buscarPorId((int)$id);
    }
}
