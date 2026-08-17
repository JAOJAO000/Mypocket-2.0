<?php

session_start();

require_once "classes/Carteira.php";

$carteira = new Carteira();

$tipo = $_POST["tipo"] ?? "";
$valor = (float) ($_POST["valor"] ?? 0);
$descricao = trim($_POST["descricao"] ?? "");
$data = $_POST["data"] ?? "";

try {

    if ($tipo == "entrada") {

        $transacao = new Receita(
            $valor,
            $descricao,
            $data
        );

    } else {

        $transacao = new Despesa(
            $valor,
            $descricao,
            $data
        );
    }

    $carteira->adicionarTransacao($transacao);

    $_SESSION["mensagem"] = "Transação cadastrada.";

} catch (Exception $e) {

    $_SESSION["mensagem"] = $e->getMessage();
}

header("Location: index.php");
exit;