<?php

function h(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

function exigir_int($valor): int {
    if (!isset($valor) || !is_numeric($valor)) {
        throw new Exception('ID inválido.');
    }
    return (int)$valor;
}

function parse_data(string $valor): DateTime {
    $dt = DateTime::createFromFormat('Y-m-d', $valor);
    if (!$dt) throw new Exception('Data inválida.');
    $dt->setTime(0,0,0);
    return $dt;
}

function diarias(DateTime $inicio, DateTime $fim): int {
    $dias = (int)$inicio->diff($fim)->days;
    return max(1, $dias + 1); // inclusivo
}

function rotulo_categoria(string $cat): string {
    return match($cat) {
        'economico' => 'Econômico',
        'sedan' => 'Sedan',
        'suv' => 'SUV',
        default => $cat
    };
}

function rotulo_status_veiculo(string $s): string {
    return $s === 'manutencao' ? 'manutenção' : 'ativo';
}
