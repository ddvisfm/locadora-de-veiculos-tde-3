<?php require_once __DIR__ . '/nav.php'; ?>

<form method="post" action="clientes.php">
  <input type="hidden" name="id" value="<?php echo isset($clienteEdit) ? h((string)$clienteEdit->getId()) : ''; ?>">

  <label>Nome:
    <input type="text" name="nome" required minlength="3" value="<?php echo isset($clienteEdit) ? h($clienteEdit->getNome()) : ''; ?>">
  </label><br>

  <label>CPF:
    <input type="text" name="cpf" required maxlength="14" placeholder="000.000.000-00" value="<?php echo isset($clienteEdit) ? h($clienteEdit->getCpf()) : ''; ?>">
  </label><br>

  <label>CNH:
    <input type="text" name="cnh" required maxlength="11" placeholder="11 dígitos" value="<?php echo isset($clienteEdit) ? h($clienteEdit->getCnh()) : ''; ?>">
  </label><br>

  <label>Contato:
    <input type="text" name="contato" required maxlength="15" placeholder="(00) 00000-0000" value="<?php echo isset($clienteEdit) ? h($clienteEdit->getContato()) : ''; ?>">
  </label><br>

  <button type="submit" name="acao" value="salvar">Salvar</button>
</form>
