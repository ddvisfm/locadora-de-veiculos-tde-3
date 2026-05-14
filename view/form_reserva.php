<?php
require_once __DIR__ . '/nav.php';

$veiculos = $veiculos ?? [];
$clientes = $clientes ?? [];
$prefVeiculoId = $prefVeiculoId ?? '';
$prefClienteId = $prefClienteId ?? '';
$prefRetirada = $prefRetirada ?? '';
$prefDevolucao = $prefDevolucao ?? '';

$id = '';
$selectedVeiculo = '';
$selectedCliente = '';
$dataRetirada = date('Y-m-d');
$dataDevolucao = date('Y-m-d', strtotime('+1 day'));

if (isset($reservaEdit) && is_array($reservaEdit)) {
    $id = $reservaEdit['id'] ?? '';
    $selectedVeiculo = $reservaEdit['veiculo_id'] ?? '';
    $selectedCliente = $reservaEdit['cliente_id'] ?? '';
    $dataRetirada = $reservaEdit['data_retirada'] ?? $dataRetirada;
    $dataDevolucao = $reservaEdit['data_devolucao'] ?? $dataDevolucao;
} else {
    if ($prefVeiculoId !== '') $selectedVeiculo = $prefVeiculoId;
    if ($prefClienteId !== '') $selectedCliente = $prefClienteId;
    if ($prefRetirada !== '') $dataRetirada = $prefRetirada;
    if ($prefDevolucao !== '') $dataDevolucao = $prefDevolucao;
}
?>

<form method="post" action="reservas.php">
  <input type="hidden" name="id" value="<?php echo h((string)$id); ?>">

  <label>Veículo:
    <select name="veiculo_id" required>
      <option value="">Selecione</option>
      <?php foreach ($veiculos as $v): ?>
        <option value="<?php echo h((string)$v->getId()); ?>" <?php echo ((string)$selectedVeiculo === (string)$v->getId()) ? 'selected' : ''; ?>>
          <?php echo h($v->getPlaca() . ' - ' . $v->getMarca() . ' ' . $v->getModelo() . ' (' . rotulo_categoria($v->getCategoria()) . ')'); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label><br>

  <label>Cliente:
    <select name="cliente_id" required>
      <option value="">Selecione</option>
      <?php foreach ($clientes as $c): ?>
        <option value="<?php echo h((string)$c->getId()); ?>" <?php echo ((string)$selectedCliente === (string)$c->getId()) ? 'selected' : ''; ?>>
          <?php echo h($c->getNome() . ' (' . $c->getCpf() . ')'); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label><br>

  <label>Data de retirada:
    <input type="date" name="data_retirada" required value="<?php echo h((string)$dataRetirada); ?>">
  </label><br>

  <label>Data de devolução:
    <input type="date" name="data_devolucao" required value="<?php echo h((string)$dataDevolucao); ?>">
  </label><br>

  <button type="submit" name="acao" value="salvar">Salvar</button>
</form>
