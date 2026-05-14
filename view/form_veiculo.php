<?php require_once __DIR__ . '/nav.php'; ?>

<form method="post" action="veiculos.php">
  <input type="hidden" name="id" value="<?php echo isset($veiculoEdit) ? $veiculoEdit->getId() : ''; ?>">

  <label>Placa:
    <input type="text" name="placa" required value="<?php echo isset($veiculoEdit) ? h($veiculoEdit->getPlaca()) : ''; ?>">
  </label><br>

  <label>Marca:
    <input type="text" name="marca" required value="<?php echo isset($veiculoEdit) ? h($veiculoEdit->getMarca()) : ''; ?>">
  </label><br>

  <label>Modelo:
    <input type="text" name="modelo" required value="<?php echo isset($veiculoEdit) ? h($veiculoEdit->getModelo()) : ''; ?>">
  </label><br>

  <label>Ano:
    <input type="number" name="ano" required value="<?php echo isset($veiculoEdit) ? h((string)$veiculoEdit->getAno()) : ''; ?>">
  </label><br>

  <label>Categoria:
    <?php $cat = isset($veiculoEdit) ? $veiculoEdit->getCategoria() : 'economico'; ?>
    <select name="categoria" required>
      <option value="economico" <?php echo $cat==='economico'?'selected':''; ?>>Econômico</option>
      <option value="sedan" <?php echo $cat==='sedan'?'selected':''; ?>>Sedan</option>
      <option value="suv" <?php echo $cat==='suv'?'selected':''; ?>>SUV</option>
    </select>
  </label><br>

  <label>Valor da diária (R$):
    <input type="text" name="valor_diaria" required placeholder="Ex: 150,00" value="<?php echo isset($veiculoEdit) ? h((string)$veiculoEdit->getValorDaDiaria()) : ''; ?>">
  </label><br>

  <label>Status:
    <?php $st = isset($veiculoEdit) ? $veiculoEdit->getStatus() : 'ativo'; ?>
    <select name="status">
      <option value="ativo" <?php echo $st==='ativo'?'selected':''; ?>>ativo</option>
      <option value="manutencao" <?php echo $st==='manutencao'?'selected':''; ?>>manutencao</option>
    </select>
  </label><br>

  <button type="submit" name="acao" value="salvar">Salvar</button>
</form>
