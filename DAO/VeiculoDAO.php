<?php
require_once 'config/Conexao.php';
require_once 'model/Veiculo.php';

class VeiculoDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Veiculo $v) {
        $stmt = $this->conn->prepare("INSERT INTO veiculos (placa, marca, modelo, ano, categoria, valor_diaria, status) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([
            $v->getPlaca(),
            $v->getMarca(),
            $v->getModelo(),
            $v->getAno(),
            $v->getCategoria(),
            $v->getValorDaDiaria(),
            $v->getStatus()
        ]);
    }

    public function listar() {
        $stmt = $this->conn->query("SELECT * FROM veiculos ORDER BY id DESC");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $v = new Veiculo($row['placa'], $row['marca'], $row['modelo'], $row['ano'], $row['categoria'], $row['valor_diaria'], $row['status']);
            $v->setId($row['id']);
            $lista[] = $v;
        }
        return $lista;
    }

    public function listarAtivos() {
        $stmt = $this->conn->query("SELECT * FROM veiculos WHERE status='ativo' ORDER BY id DESC");
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $v = new Veiculo($row['placa'], $row['marca'], $row['modelo'], $row['ano'], $row['categoria'], $row['valor_diaria'], $row['status']);
            $v->setId($row['id']);
            $lista[] = $v;
        }
        return $lista;
    }

    public function atualizar(Veiculo $v) {
        $stmt = $this->conn->prepare("UPDATE veiculos SET placa=?, marca=?, modelo=?, ano=?, categoria=?, valor_diaria=?, status=? WHERE id=?");
        $stmt->execute([
            $v->getPlaca(),
            $v->getMarca(),
            $v->getModelo(),
            $v->getAno(),
            $v->getCategoria(),
            $v->getValorDaDiaria(),
            $v->getStatus(),
            $v->getId()
        ]);
    }

    public function deletar($id) {
        // Bloqueia exclusão se houver reserva ativa
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS c FROM reservas WHERE veiculo_id=? AND status IN ('reservado')");
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ((int)$row['c'] > 0) {
            throw new Exception('Não é possível excluir: veículo possui reserva ativa.');
        }

        $stmt = $this->conn->prepare("DELETE FROM veiculos WHERE id=?");
        $stmt->execute([(int)$id]);
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM veiculos WHERE id=?");
        $stmt->execute([(int)$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $v = new Veiculo($row['placa'], $row['marca'], $row['modelo'], $row['ano'], $row['categoria'], $row['valor_diaria'], $row['status']);
            $v->setId($row['id']);
            return $v;
        }
        return null;
    }

    public function buscarDisponiveis($dataRetirada, $dataDevolucao) {
        $sql = "
            SELECT v.*
            FROM veiculos v
            WHERE v.status='ativo'
              AND v.id NOT IN (
                SELECT r.veiculo_id
                FROM reservas r
                WHERE r.status IN ('reservado')
                  AND NOT (r.data_devolucao < :retirada OR r.data_retirada > :devolucao)
              )
            ORDER BY v.id DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':retirada'=>$dataRetirada, ':devolucao'=>$dataDevolucao]);

        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $v = new Veiculo($row['placa'], $row['marca'], $row['modelo'], $row['ano'], $row['categoria'], $row['valor_diaria'], $row['status']);
            $v->setId($row['id']);
            $lista[] = $v;
        }
        return $lista;
    }
}
