<?php
require_once __DIR__ . '/controller/ClienteController.php';
require_once __DIR__ . '/config/Helpers.php';

$controller = new ClienteController();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome = trim($_POST['nome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $cnh = trim($_POST['cnh'] ?? '');
        $contato = trim($_POST['contato'] ?? '');
        $id = $_POST['id'] ?? '';

        if ($nome === '' || $cpf === '' || $cnh === '' || $contato === '') {
            throw new Exception('Preencha todos os campos.');
        }

        if (strlen($nome) < 3) {
            throw new Exception('Nome inválido. Informe pelo menos 3 caracteres.');
        }

        if (!cpf_valido($cpf)) {
            throw new Exception('CPF inválido.');
        }

        if (!cnh_valida($cnh)) {
            throw new Exception('CNH inválida. Informe 11 dígitos.');
        }

        if (!telefone_valido($contato)) {
            throw new Exception('Contato inválido. Informe telefone com DDD, com 10 ou 11 dígitos.');
        }

        $cpf = formatar_cpf($cpf);
        $cnh = somente_digitos($cnh);
        $contato = formatar_telefone($contato);

        if (($_POST['acao'] ?? '') === 'salvar') {
            if ($id) {
                $controller->atualizar($id, $nome, $cpf, $cnh, $contato);
                $msg = "Cliente atualizado com sucesso!";
            } else {
                $controller->salvar($nome, $cpf, $cnh, $contato);
                $msg = "Cliente salvo com sucesso!";
            }
        }
    } catch (Exception $e) {
        $msg = "Erro: " . $e->getMessage();
    }
}

if (isset($_GET['editar'])) {
    $clienteEdit = $controller->buscarPorId(exigir_int($_GET['editar']));
}

if (isset($_GET['deletar'])) {
    try {
        $controller->deletar(exigir_int($_GET['deletar']));
        $msg = "Cliente excluído com sucesso!";
    } catch (Exception $e) {
        $msg = "Erro: " . $e->getMessage();
    }
}

require_once __DIR__ . '/view/form_cliente.php';

if ($msg) echo "<div class='msg'>".h($msg)."</div>";

$clientes = $controller->listar();

echo "<h2>Lista de Clientes</h2>";
echo "<table><tr><th>Nome</th><th>CPF</th><th>CNH</th><th>Contato</th><th>Ações</th></tr>";
foreach ($clientes as $c) {
    echo "<tr>";
    echo "<td>".h($c->getNome())."</td>";
    echo "<td>".h($c->getCpf())."</td>";
    echo "<td>".h($c->getCnh())."</td>";
    echo "<td>".h($c->getContato())."</td>";
    echo "<td>";
    echo "<a href='?editar=".$c->getId()."'>Editar</a> | ";
    echo "<a href='?deletar=".$c->getId()."' onclick=\"return confirm('Deseja realmente excluir?');\">Excluir</a>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
