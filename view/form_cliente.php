<?php require_once 'view/nav.php'; ?>

<form method="post" action="clientes.php">
  <input type="hidden" name="id" value="<?php echo isset($clienteEdit) ? $clienteEdit->getId() : ''; ?>">

  <label>Nome:
    <input type="text" name="nome" required value="<?php echo isset($clienteEdit) ? h($clienteEdit->getNome()) : ''; ?>">
  </label><br>

  <label>CPF:
    <input type="text" name="cpf" required value="<?php echo isset($clienteEdit) ? h($clienteEdit->getCpf()) : ''; ?>">
  </label><br>

  <label>CNH:
    <input type="text" name="cnh" required value="<?php echo isset($clienteEdit) ? h($clienteEdit->getCnh()) : ''; ?>">
  </label><br>

  <label>Contato:
    <input type="text" name="contato" required value="<?php echo isset($clienteEdit) ? h($clienteEdit->getContato()) : ''; ?>">
  </label><br>

  <button type="submit" name="acao" value="salvar">Salvar</button>
</form>
