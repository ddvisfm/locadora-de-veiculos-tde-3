<?php
require_once 'controller/VeiculoController.php';
require_once 'config/Helpers.php';

$controller = new VeiculoController();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $placa = strtoupper(trim($_POST['placa'] ?? ''));
        $marca = trim($_POST['marca'] ?? '');
        $modelo = trim($_POST['modelo'] ?? '');
        $ano = (int)($_POST['ano'] ?? 0);
        $categoria = $_POST['categoria'] ?? 'economico';
        $valor_diaria_txt = $_POST['valor_diaria'] ?? '0';
        $valorDaDiaria = (float)str_replace(',', '.', $valor_diaria_txt);
        $status = $_POST['status'] ?? 'ativo';

        if ($placa===''||$marca===''||$modelo==='') throw new Exception('Placa, marca e modelo são obrigatórios.');
        if ($ano<1900 || $ano>((int)date('Y')+1)) throw new Exception('Ano inválido.');
        if (!in_array($categoria,['economico','sedan','suv'],true)) throw new Exception('Categoria inválida.');
        if ($valorDaDiaria<=0) throw new Exception('Valor da diária deve ser maior que zero.');
        if (!in_array($status,['ativo','manutencao'],true)) throw new Exception('Status inválido.');

        $id = $_POST['id'] ?? '';

        if (($_POST['acao'] ?? '') === 'salvar') {
            if ($id) {
                $controller->atualizar($id, $placa, $marca, $modelo, $ano, $categoria, $valorDaDiaria, $status);
                $msg = "Veículo atualizado com sucesso!";
            } else {
                $controller->salvar($placa, $marca, $modelo, $ano, $categoria, $valorDaDiaria, $status);
                $msg = "Veículo salvo com sucesso!";
            }
        }
    } catch (Exception $e) {
        $msg = "Erro: " . $e->getMessage();
    }
}

if (isset($_GET['editar'])) {
    $veiculoEdit = $controller->buscarPorId(exigir_int($_GET['editar']));
}

if (isset($_GET['deletar'])) {
    try {
        $controller->deletar(exigir_int($_GET['deletar']));
        $msg = "Veículo excluído com sucesso!";
    } catch (Exception $e) {
        $msg = "Erro: " . $e->getMessage();
    }
}

require_once 'view/form_veiculo.php';

if ($msg) echo "<div class='msg'>".h($msg)."</div>";

$veiculos = $controller->listar();

echo "<h2>Lista de Veículos</h2>";
echo "<table><tr><th>Placa</th><th>Veículo</th><th>Ano</th><th>Categoria</th><th>Valor da diária</th><th>Status</th><th>Ações</th></tr>";
foreach ($veiculos as $v) {
    echo "<tr>";
    echo "<td>".h($v->getPlaca())."</td>";
    echo "<td>".h($v->getMarca().' '.$v->getModelo())."</td>";
    echo "<td>".h((string)$v->getAno())."</td>";
    echo "<td>".h(rotulo_categoria($v->getCategoria()))."</td>";
    echo "<td>R$ ".h((string)$v->getValorDaDiaria())."</td>";
    echo "<td>".h(rotulo_status_veiculo($v->getStatus()))."</td>";
    echo "<td>";
    echo "<a href='?editar=".$v->getId()."'>Editar</a> | ";
    echo "<a href='?deletar=".$v->getId()."' onclick=\"return confirm('Deseja realmente excluir?');\">Excluir</a>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
