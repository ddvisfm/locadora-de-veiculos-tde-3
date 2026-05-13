<?php require_once 'view/nav.php'; ?>

<form method="get" action="index.php">
  <label>Retirada:
    <input type="date" name="data_retirada" required value="<?php echo h($dataRetirada); ?>">
  </label>
  <label>Devolução:
    <input type="date" name="data_devolucao" required value="<?php echo h($dataDevolucao); ?>">
  </label>
  <button type="submit">Buscar</button>
</form>
