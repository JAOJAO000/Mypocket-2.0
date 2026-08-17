<?php
 
session_start();
 
require_once "classes/Carteira.php";
 
$carteira = new Carteira();
 
$id = (int) ($_GET["id"] ?? 0);
 
if ($id > 0) {
    $carteira->removerTransacao($id);
    $_SESSION["mensagem"] = "Transação removida.";
}
 
header("Location: index.php");
exit;
 