<?php

session_start();

require_once "classes/Carteira.php";

$carteira = new Carteira();
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<title>MyPocket</title>

</head>

<body>

<div class="container mt-5">

<h1>MyPocket</h1>

<?php if (isset($_SESSION["mensagem"])): ?>

<div class="alert alert-info">
<?= htmlspecialchars($_SESSION["mensagem"]) ?>
</div>

<?php unset($_SESSION["mensagem"]); endif; ?>

<h3>
Saldo Atual:
R$
<?= number_format($carteira->getSaldo(),2,",",".") ?>
</h3>

<form action="processa.php" method="POST">

<select name="tipo" class="form-select mb-2">

<option value="entrada">Receita</option>
<option value="saida">Despesa</option>

</select>

<input
type="number"
step="0.01"
name="valor"
class="form-control mb-2"
placeholder="Valor"
required>

<input
type="text"
name="descricao"
class="form-control mb-2"
placeholder="Descrição"
required>

<input
type="date"
name="data"
class="form-control mb-3"
required>

<button class="btn btn-primary">
Cadastrar
</button>

</form>

<hr>

<h2>Extrato</h2>

<table class="table">

<tr>
<th>Tipo</th>
<th>Descrição</th>
<th>Data</th>
<th>Valor</th>
<th>Ações</th>
</tr>

<?php foreach ($carteira->getTransacoes() as $transacao): ?>

<tr>

<td>

<?php if ($transacao->getTipo() == "Entrada"): ?>

<span class="badge bg-success">
Entrada
</span>

<?php else: ?>

<span class="badge bg-danger">
Saída
</span>

<?php endif; ?>

</td>

<td>
<?= htmlspecialchars($transacao->getDescricao()) ?>
</td>

<td>
<?= $transacao->getData() ?>
</td>

<td>
R$ <?= number_format($transacao->getValor(),2,",",".") ?>
</td>

<td>

<a
href="atualizar.php?id=<?= $transacao->getId() ?>"
class="btn btn-sm btn-warning">
Editar
</a>

<a
href="delete.php?id=<?= $transacao->getId() ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Confirma exclusão desta transação?')">
Excluir
</a>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</body>

</html>