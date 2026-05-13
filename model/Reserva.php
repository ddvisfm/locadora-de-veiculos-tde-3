<?php

class Reserva {
    private $id;
    private $veiculoId;
    private $clienteId;
    private $dataRetirada;
    private $dataDevolucao;
    private $valorDaDiaria;
    private $valorEstimado;
    private $status;

    public function __construct($veiculoId, $clienteId, $dataRetirada, $dataDevolucao, $valorDaDiaria, $valorEstimado, $status) {
        $this->veiculoId = $veiculoId;
        $this->clienteId = $clienteId;
        $this->dataRetirada = $dataRetirada;
        $this->dataDevolucao = $dataDevolucao;
        $this->valorDaDiaria = $valorDaDiaria;
        $this->valorEstimado = $valorEstimado;
        $this->status = $status;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getVeiculoId() { return $this->veiculoId; }
    public function setVeiculoId($v) { $this->veiculoId = $v; }

    public function getClienteId() { return $this->clienteId; }
    public function setClienteId($v) { $this->clienteId = $v; }

    public function getDataRetirada() { return $this->dataRetirada; }
    public function setDataRetirada($v) { $this->dataRetirada = $v; }

    public function getDataDevolucao() { return $this->dataDevolucao; }
    public function setDataDevolucao($v) { $this->dataDevolucao = $v; }

    public function getValorDaDiaria() { return $this->valorDaDiaria; }
    public function setValorDaDiaria($v) { $this->valorDaDiaria = $v; }

    public function getValorEstimado() { return $this->valorEstimado; }
    public function setValorEstimado($v) { $this->valorEstimado = $v; }

    public function getStatus() { return $this->status; }
    public function setStatus($v) { $this->status = $v; }
}
