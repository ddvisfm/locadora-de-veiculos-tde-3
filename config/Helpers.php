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
    $erros = DateTime::getLastErrors();

    if (!$dt || ($erros !== false && ($erros['warning_count'] > 0 || $erros['error_count'] > 0))) {
        throw new Exception('Data inválida.');
    }

    $dt->setTime(0, 0, 0);
    return $dt;
}

function diarias(DateTime $inicio, DateTime $fim): int {
    $dias = (int)$inicio->diff($fim)->days;
    return max(1, $dias + 1);
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

function somente_digitos(string $valor): string {
    return preg_replace('/\D+/', '', $valor);
}

function cpf_valido(string $cpf): bool {
    $cpf = somente_digitos($cpf);

    if (strlen($cpf) !== 11) return false;
    if (preg_match('/^(\d)\1{10}$/', $cpf)) return false;

    for ($t = 9; $t < 11; $t++) {
        $soma = 0;

        for ($i = 0; $i < $t; $i++) {
            $soma += (int)$cpf[$i] * (($t + 1) - $i);
        }

        $digito = ((10 * $soma) % 11) % 10;

        if ((int)$cpf[$t] !== $digito) return false;
    }

    return true;
}

function formatar_cpf(string $cpf): string {
    $cpf = somente_digitos($cpf);

    return substr($cpf, 0, 3) . '.' .
           substr($cpf, 3, 3) . '.' .
           substr($cpf, 6, 3) . '-' .
           substr($cpf, 9, 2);
}

function cnh_valida(string $cnh): bool {
    $cnh = somente_digitos($cnh);

    if (strlen($cnh) !== 11) return false;
    if (preg_match('/^(\d)\1{10}$/', $cnh)) return false;

    return true;
}

function telefone_valido(string $telefone): bool {
    $telefone = somente_digitos($telefone);

    if (!in_array(strlen($telefone), [10, 11], true)) return false;
    if (preg_match('/^(\d)\1+$/', $telefone)) return false;

    $ddd = (int)substr($telefone, 0, 2);
    return $ddd >= 11 && $ddd <= 99;
}

function formatar_telefone(string $telefone): string {
    $telefone = somente_digitos($telefone);

    if (strlen($telefone) === 11) {
        return '(' . substr($telefone, 0, 2) . ') ' .
               substr($telefone, 2, 5) . '-' .
               substr($telefone, 7, 4);
    }

    if (strlen($telefone) === 10) {
        return '(' . substr($telefone, 0, 2) . ') ' .
               substr($telefone, 2, 4) . '-' .
               substr($telefone, 6, 4);
    }

    return $telefone;
}

function normalizar_preco(string $valor): float {
    $valor = trim($valor);

    if ($valor === '') {
        throw new Exception('Valor da diária é obrigatório.');
    }

    $valor = str_replace(['R$', ' '], '', $valor);
    $valor = str_replace('.', '', $valor);
    $valor = str_replace(',', '.', $valor);

    if (!is_numeric($valor)) {
        throw new Exception('Valor da diária inválido. Use apenas números.');
    }

    $numero = (float)$valor;

    if ($numero <= 0) {
        throw new Exception('Valor da diária deve ser maior que zero.');
    }

    if ($numero > 10000) {
        throw new Exception('Valor da diária muito alto. Informe até R$ 10.000,00.');
    }

    return $numero;
}

function formatar_preco($valor): string {
    return number_format((float)$valor, 2, ',', '.');
}
