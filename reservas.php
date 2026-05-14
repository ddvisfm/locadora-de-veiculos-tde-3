<?php
require_once __DIR__ . '/controller/ReservaController.php';
require_once __DIR__ . '/controller/VeiculoController.php';
require_once __DIR__ . '/controller/ClienteController.php';
require_once __DIR__ . '/config/Helpers.php';

$controller = new ReservaController();
$vController = new VeiculoController();
$cController = new ClienteController();

$msg = '';

$veiculos = $vController->listarAtivos();
$clientes = $cController->listar();

$prefVeiculoId = $_GET['veiculo_id'] ?? '';
$prefRetirada = $_GET['data_retirada'] ?? '';
$prefDevolucao = $_GET['data_devolucao'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? '';
        $veiculoId = $_POST['veiculo_id'] ?? '';
        $clienteId = $_POST['cliente_id'] ?? '';
        $dataRetirada = $_POST['data_retirada'] ?? '';
        $dataDevolucao = $_POST['data_devolucao'] ?? '';

        if (($_POST['acao'] ?? '') === 'salvar') {
            if ($id) {
                $estimado = $controller->atualizar($id, $veiculoId, $clienteId, $dataRetirada, $dataDevolucao);
                $msg = "Reserva atualizada! Estimativa: R$ " . $estimado;
            } else {
                $estimado = $controller->salvar($veiculoId, $clienteId, $dataRetirada, $dataDevolucao);
                $msg = "Reserva criada! Estimativa: R$ " . $estimado;
            }
        }
    } catch (Exception $e) {
        $msg = "Erro: " . $e->getMessage();
    }
}

if (isset($_GET['editar'])) {
    $reservaEdit = $controller->buscarPorId(exigir_int($_GET['editar']));
}

if (isset($_GET['cancelar'])) {
    try { $controller->cancelar(exigir_int($_GET['cancelar'])); $msg = "Reserva cancelada."; }
    catch (Exception $e) { $msg = "Erro: ".$e->getMessage(); }
}


if (isset($_GET['finalizar'])) {
    try { $total = $controller->finalizar(exigir_int($_GET['finalizar'])); $msg = "Locação finalizada. Total: R$ " . $total; }
    catch (Exception $e) { $msg = "Erro: ".$e->getMessage(); }
}

if (isset($_GET['deletar'])) {
    try { $controller->deletar(exigir_int($_GET['deletar'])); $msg = "Registro excluído."; }
    catch (Exception $e) { $msg = "Erro: ".$e->getMessage(); }
}

require_once __DIR__ . '/view/form_reserva.php';

if ($msg) echo "<div class='msg'>".h($msg)."</div>";

$reservas = $controller->listar();

echo "<h2>Lista de Reservas</h2>";
echo "<table><tr><th>ID</th><th>Veículo</th><th>Cliente</th><th>Período</th><th>Status</th><th>Estimativa</th><th>Final</th><th>Ações</th></tr>";
foreach ($reservas as $r) {
    echo "<tr>";
    echo "<td>".h((string)$r['id'])."</td>";
    echo "<td>".h($r['marca'].' '.$r['modelo'].' ('.$r['placa'].')')."</td>";
    echo "<td>".h($r['nome_cliente'].' ('.$r['cpf'].')')."</td>";
    echo "<td>".h($r['data_retirada'])." → ".h($r['data_devolucao'])."</td>";
    echo "<td>".h($r['status'])."</td>";
    echo "<td>R$ ".h((string)$r['valor_estimado'])."</td>";
    echo "<td>".($r['valor_final']!==null ? "R$ ".h((string)$r['valor_final']) : "-")."</td>";

    echo "<td>";
    if ($r['status'] === 'reservado') {
        echo "<a href='?editar=".$r['id']."'>Editar</a> | ";
        echo "<a href='?finalizar=".$r['id']."' onclick=\"return confirm('Finalizar locação?');\">Finalizar</a> | ";
                echo "<a href='?cancelar=".$r['id']."' onclick=\"return confirm('Cancelar?');\">Cancelar</a> | ";
    }
echo "<a href='?deletar=".$r['id']."' onclick=\"return confirm('Excluir registro?');\">Excluir</a>";
    echo "</td>";

    echo "</tr>";
}
if (count($reservas)===0) {
    echo "<tr><td colspan='8'>Nenhuma reserva.</td></tr>";
}
echo "</table>";
