<?php require_once 'view/nav.php'; ?>

<form method="post" action="reservas.php">
  <input type="hidden" name="id" value="<?php echo isset($reservaEdit) ? h((string)$reservaEdit['id']) : ''; ?>">

  <label>Veículo:
    <?php $selectedVeiculo = isset($reservaEdit) ? (string)$reservaEdit['veiculo_id'] : (string)($prefVeiculoId ?? ''); ?>
    <select name="veiculo_id" required>
      <option value="">Selecione</option>
      <?php foreach ($veiculos as $v): ?>
        <option value="<?php echo h((string)$v->getId()); ?>" <?php echo $selectedVeiculo===(string)$v->getId()?'selected':''; ?>>
          <?php echo h($v->getPlaca().' - '.$v->getMarca().' '.$v->getModelo().' ('.rotulo_categoria($v->getCategoria()).')'); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label><br>

  <label>Cliente:
    <?php $selectedCliente = isset($reservaEdit) ? (string)$reservaEdit['cliente_id'] : (string)($prefClienteId ?? ''); ?>
    <select name="cliente_id" required>
      <option value="">Selecione</option>
      <?php foreach ($clientes as $c): ?>
        <option value="<?php echo h((string)$c->getId()); ?>" <?php echo $selectedCliente===(string)$c->getId()?'selected':''; ?>>
          <?php echo h($c->getNome().' ('.$c->getCpf().')'); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label><br>

  <label>Data de retirada:
    <input type="date" name="data_retirada" required value="<?php echo isset($reservaEdit) ? h($reservaEdit['data_retirada']) : h($prefRetirada ?? date('Y-m-d')); ?>">
  </label><br>

  <label>Data de devolução:
    <input type="date" name="data_devolucao" required value="<?php echo isset($reservaEdit) ? h($reservaEdit['data_devolucao']) : h($prefDevolucao ?? date('Y-m-d', strtotime('+1 day'))); ?>">
  </label><br>

  <button type="submit" name="acao" value="salvar">Salvar</button>
</form>
