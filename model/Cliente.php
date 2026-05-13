<?php

class Cliente {
    private $id;
    private $nome;
    private $cpf;
    private $cnh;
    private $contato;

    public function __construct($nome, $cpf, $cnh, $contato) {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->cnh = $cnh;
        $this->contato = $contato;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNome() { return $this->nome; }
    public function setNome($v) { $this->nome = $v; }

    public function getCpf() { return $this->cpf; }
    public function setCpf($v) { $this->cpf = $v; }

    public function getCnh() { return $this->cnh; }
    public function setCnh($v) { $this->cnh = $v; }

    public function getContato() { return $this->contato; }
    public function setContato($v) { $this->contato = $v; }
}
