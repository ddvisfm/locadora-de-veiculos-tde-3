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
    public function setNome($c) { $this->nome = $c; }

    public function getCpf() { return $this->cpf; }
    public function setCpf($c) { $this->cpf = $c; }

    public function getCnh() { return $this->cnh; }
    public function setCnh($c) { $this->cnh = $c; }

    public function getContato() { return $this->contato; }
    public function setContato($c) { $this->contato = $c; }
}   
