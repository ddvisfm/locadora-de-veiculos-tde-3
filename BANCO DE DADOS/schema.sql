-- Schema da Locadora (nomes em português)
-- Dica: se você estiver com tabelas antigas, esse script recria tudo do zero.

DROP DATABASE IF EXISTS locadora;
CREATE DATABASE locadora
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE locadora;

-- Remove tabelas antigas (português e inglês), caso existam
DROP TABLE IF EXISTS reservas;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS veiculos;

DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS vehicles;

CREATE TABLE veiculos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  placa VARCHAR(10) NOT NULL UNIQUE,
  marca VARCHAR(60) NOT NULL,
  modelo VARCHAR(80) NOT NULL,
  ano INT NOT NULL,
  categoria ENUM('economico','sedan','suv') NOT NULL DEFAULT 'economico',
  valor_diaria DECIMAL(10,2) NOT NULL,
  status ENUM('ativo','manutencao') NOT NULL DEFAULT 'ativo',
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  cpf VARCHAR(14) NOT NULL UNIQUE,
  cnh VARCHAR(20) NOT NULL,
  contato VARCHAR(120) NOT NULL,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reservas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  veiculo_id INT NOT NULL,
  cliente_id INT NOT NULL,
  data_retirada DATE NOT NULL,
  data_devolucao DATE NOT NULL,
  data_devolucao_real DATE NULL,
  valor_diaria DECIMAL(10,2) NOT NULL,
  valor_estimado DECIMAL(10,2) NOT NULL,
  valor_final DECIMAL(10,2) NULL,
  status ENUM('reservado','finalizado','cancelado') NOT NULL DEFAULT 'reservado',
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reserva_veiculo FOREIGN KEY (veiculo_id) REFERENCES veiculos(id) ON UPDATE CASCADE,
  CONSTRAINT fk_reserva_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON UPDATE CASCADE
);

CREATE INDEX idx_veiculos_status ON veiculos(status);
CREATE INDEX idx_reservas_status ON reservas(status);
CREATE INDEX idx_reservas_datas ON reservas(data_retirada, data_devolucao);
