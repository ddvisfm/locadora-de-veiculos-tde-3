<?php
require_once __DIR__ . '/controller/VeiculoController.php';
require_once __DIR__ . '/config/Helpers.php';

$controller = new VeiculoController();

$msg = '';
$veiculos = [];
$pesquisou = isset($_GET['buscar']);

if ($pesquisou) {
    try {
        $veiculos = $controller->buscarDisponiveisSemData();
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $veiculos = [];
    }
}

require_once __DIR__ . '/view/form_busca.php';

if ($msg) {
    echo "<div class='msg'><b>Erro:</b> " . h($msg) . "</div>";
}

if ($pesquisou) {
    echo "<h2>Veículos disponíveis</h2>";
    echo "<table><tr><th>Veículo</th><th>Categoria</th><th>Valor da diária</th><th>Ação</th></tr>";
    foreach ($veiculos as $v) {
        $link = "reservas.php?novo=1&veiculo_id=" . $v->getId();
        echo "<tr>";
        echo "<td>" . h($v->getMarca() . " " . $v->getModelo() . " (" . $v->getPlaca() . ")") . "</td>";
        echo "<td>" . h(rotulo_categoria($v->getCategoria())) . "</td>";
        echo "<td>R$ " . h(formatar_preco($v->getValorDaDiaria())) . "</td>";
        echo "<td><a href='$link'>Reservar</a></td>";
        echo "</tr>";
    }
    if (count($veiculos) === 0) {
        echo "<tr><td colspan='4'>Nenhum veículo disponível.</td></tr>";
    }
    echo "</table>";
}
