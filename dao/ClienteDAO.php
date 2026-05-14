<?php
require_once __DIR__ . '/../config/Conexao.php';
require_once __DIR__ . '/../model/Cliente.php';

class ClienteDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Cliente $c) {
        $stmt = $this->conn->prepare("INSERT INTO clientes (nome, cpf, cnh, contato) VALUES (?,?,?,?)");
        $stmt->execute([$c->getNome(), $c->getCpf(), $c->getCnh(), $c->getContato()]);
    }

    public function listar() {
        $stmt = $this->conn->query("SELECT * FROM clientes ORDER BY id DESC");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $c = new Cliente($row['nome'], $row['cpf'], $row['cnh'], $row['contato']);
            $c->setId($row['id']);
            $lista[] = $c;
        }
        return $lista;
    }

    public function atualizar(Cliente $c) {
        $stmt = $this->conn->prepare("UPDATE clientes SET nome=?, cpf=?, cnh=?, contato=? WHERE id=?");
        $stmt->execute([$c->getNome(), $c->getCpf(), $c->getCnh(), $c->getContato(), $c->getId()]);
    }

    public function deletar($id) {
        // Bloqueia exclusão se houver reserva ativa
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS c FROM reservas WHERE cliente_id=? AND status IN ('reservado')");
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ((int)$row['c'] > 0) {
            throw new Exception('Não é possível excluir: cliente possui reserva ativa.');
        }

        $stmt = $this->conn->prepare("DELETE FROM clientes WHERE id=?");
        $stmt->execute([(int)$id]);
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM clientes WHERE id=?");
        $stmt->execute([(int)$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $c = new Cliente($row['nome'], $row['cpf'], $row['cnh'], $row['contato']);
            $c->setId($row['id']);
            return $c;
        }
        return null;
    }
}
