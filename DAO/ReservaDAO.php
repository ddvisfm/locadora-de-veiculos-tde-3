<?php
require_once 'config/Conexao.php';
require_once 'model/Reserva.php';

class ReservaDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConn();
    }

    public function salvar(Reserva $r) {
        $stmt = $this->conn->prepare("
            INSERT INTO reservas (veiculo_id, cliente_id, data_retirada, data_devolucao, valor_diaria, valor_estimado, status)
            VALUES (?,?,?,?,?,?,?)
        ");
        $stmt->execute([
            $r->getVeiculoId(),
            $r->getClienteId(),
            $r->getDataRetirada(),
            $r->getDataDevolucao(),
            $r->getValorDaDiaria(),
            $r->getValorEstimado(),
            $r->getStatus(),
        ]);
    }

    public function listar() {
        $sql = "
            SELECT r.*,
                   v.placa, v.marca, v.modelo,
                   c.nome AS nome_cliente, c.cpf
            FROM reservas r
            JOIN veiculos v ON v.id = r.veiculo_id
            JOIN clientes c ON c.id = r.cliente_id
            ORDER BY r.id DESC
        ";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM reservas WHERE id=?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function atualizarBasico(Reserva $r) {
        $stmt = $this->conn->prepare("
            UPDATE reservas
            SET veiculo_id=?, cliente_id=?, data_retirada=?, data_devolucao=?, valor_diaria=?, valor_estimado=?
            WHERE id=?
        ");
        $stmt->execute([
            $r->getVeiculoId(),
            $r->getClienteId(),
            $r->getDataRetirada(),
            $r->getDataDevolucao(),
            $r->getValorDaDiaria(),
            $r->getValorEstimado(),
            $r->getId(),
        ]);
    }

    public function atualizarStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE reservas SET status=? WHERE id=?");
        $stmt->execute([$status, (int)$id]);
    }

    public function finalizar($id, $dataDevolucaoReal, $valorFinal) {
        $stmt = $this->conn->prepare("UPDATE reservas SET status='finalizado', data_devolucao_real=?, valor_final=? WHERE id=?");
        $stmt->execute([$dataDevolucaoReal, $valorFinal, (int)$id]);
    }

    public function deletar($id) {
        $stmt = $this->conn->prepare("DELETE FROM reservas WHERE id=?");
        $stmt->execute([(int)$id]);
    }

    public function estaDisponivel($veiculoId, $dataRetirada, $dataDevolucao, $excluirId = null) {
        $sql = "
            SELECT COUNT(*) AS c
            FROM reservas
            WHERE veiculo_id = :vid
              AND status IN ('reservado')
              AND NOT (data_devolucao < :retirada OR data_retirada > :devolucao)
        ";
        if ($excluirId !== null) {
            $sql .= " AND id <> :rid";
        }
        $stmt = $this->conn->prepare($sql);
        $params = [':vid'=>(int)$veiculoId, ':retirada'=>$dataRetirada, ':devolucao'=>$dataDevolucao];
        if ($excluirId !== null) $params[':rid'] = (int)$excluirId;
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ((int)$row['c']) === 0;
    }
}
