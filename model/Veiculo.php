<?php

class Veiculo {
    private $id;
    private $placa;
    private $marca;
    private $modelo;
    private $ano;
    private $categoria;
    private $valorDaDiaria;
    private $status;

    public function __construct($placa, $marca, $modelo, $ano, $categoria, $valorDaDiaria, $status) {
        $this->placa = $placa;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->ano = $ano;
        $this->categoria = $categoria;
        $this->valorDaDiaria = $valorDaDiaria;
        $this->status = $status;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getPlaca() { return $this->placa; }
    public function setPlaca($v) { $this->placa = $v; }

    public function getMarca() { return $this->marca; }
    public function setMarca($v) { $this->marca = $v; }

    public function getModelo() { return $this->modelo; }
    public function setModelo($v) { $this->modelo = $v; }

    public function getAno() { return $this->ano; }
    public function setAno($v) { $this->ano = $v; }

    public function getCategoria() { return $this->categoria; }
    public function setCategoria($v) { $this->categoria = $v; }

    public function getValorDaDiaria() { return $this->valorDaDiaria; }
    public function setValorDaDiaria($v) { $this->valorDaDiaria = $v; }

    public function getStatus() { return $this->status; }
    public function setStatus($v) { $this->status = $v; }
}
