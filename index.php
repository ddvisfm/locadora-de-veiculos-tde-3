<?php
require_once 'controller/VeiculoController.php';
require_once 'config/Helpers.php';

$controller = new VeiculoController();

$dataRetirada = $_GET['data_retirada'] ?? date('Y-m-d');
$dataDevolucao = $_GET['data_devolucao'] ?? date('Y-m-d', strtotime('+1 day'));

$msg = '';
$veiculos = [];
$pesquisou = isset($_GET['data_retirada'], $_GET['data_devolucao']);

if ($pesquisou) {
    try {
        $inicio = parse_data($dataRetirada);
        $fim = parse_data($dataDevolucao);
        if ($fim < $inicio) throw new Exception('Devolução não pode ser anterior à retirada.');
        $veiculos = $controller->buscarDisponiveis($dataRetirada, $dataDevolucao);
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $veiculos = [];
    }
}

require_once 'view/form_busca.php';

if ($msg) {
    echo "<div class='msg'><b>Erro:</b> " . h($msg) . "</div>";
}

if ($pesquisou) {
    echo "<h2>Veículos disponíveis</h2>";
    echo "<table><tr><th>Veículo</th><th>Categoria</th><th>Valor da diária</th><th>Ação</th></tr>";
    foreach ($veiculos as $v) {
        $link = "reservas.php?novo=1&veiculo_id=" . $v->getId() . "&data_retirada=" . h($dataRetirada) . "&data_devolucao=" . h($dataDevolucao);
        echo "<tr>";
        echo "<td>" . h($v->getMarca() . " " . $v->getModelo() . " (" . $v->getPlaca() . ")") . "</td>";
        echo "<td>" . h(rotulo_categoria($v->getCategoria())) . "</td>";
        echo "<td>R$ " . h((string)$v->getValorDaDiaria()) . "</td>";
        echo "<td><a href='$link'>Reservar</a></td>";
        echo "</tr>";
    }
    if (count($veiculos) === 0) {
        echo "<tr><td colspan='4'>Nenhum veículo disponível.</td></tr>";
    }
    echo "</table>";
}
