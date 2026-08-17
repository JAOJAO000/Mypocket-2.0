<?php
 
session_start();
 
require_once "classes/Carteira.php";
 
$carteira = new Carteira();
 
$id = (int) ($_GET["id"] ?? $_POST["id"] ?? 0);
 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
 
    $tipo = $_POST["tipo"] ?? "";
    $valor = (float) ($_POST["valor"] ?? 0);
    $descricao = trim($_POST["descricao"] ?? "");
    $data = $_POST["data"] ?? "";
 
    try {
 
        if ($tipo == "entrada") {
 
            $transacao = new Receita(
                $valor,
                $descricao,
                $data,
                $id
            );
 
        } else {
 
            $transacao = new Despesa(
                $valor,
                $descricao,
                $data,
                $id
            );
        }
 
        $carteira->atualizarTransacao($id, $transacao);
 
        $_SESSION["mensagem"] = "Transação atualizada.";
 
    } catch (Exception $e) {
 
        $_SESSION["mensagem"] = $e->getMessage();
    }
 
    header("Location: index.php");
    exit;
}
 
$transacao = $carteira->buscarPorId($id);
 
if (!$transacao) {
    header("Location: index.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html>
 
<head>
 
<meta charset="UTF-8">
 
<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">
 
<title>MyPocket - Editar</title>
 
</head>
 
<body>
 
<div class="container mt-5">
 
<h1>Editar Transação</h1>
 
<form action="atualizar.php" method="POST">
 
<input type="hidden" name="id" value="<?= $transacao->getId() ?>">
 
<select name="tipo" class="form-select mb-2">
 
<option
value="entrada"
<?= $transacao instanceof Receita ? "selected" : "" ?>>
Receita
</option>
 
<option
value="saida"
<?= $transacao instanceof Despesa ? "selected" : "" ?>>
Despesa
</option>
 
</select>
 
<input
type="number"
step="0.01"
name="valor"
class="form-control mb-2"
value="<?= $transacao->getValor() ?>"
required>
 
<input
type="text"
name="descricao"
class="form-control mb-2"
value="<?= htmlspecialchars($transacao->getDescricao()) ?>"
required>
 
<input
type="date"
name="data"
class="form-control mb-3"
value="<?= $transacao->getData() ?>"
required>
 
<button class="btn btn-primary">
Salvar
</button>
 
<a href="index.php" class="btn btn-secondary">
Cancelar
</a>
 
</form>
 
</div>
 
</body>
 
</html>