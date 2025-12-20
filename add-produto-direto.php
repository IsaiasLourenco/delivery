<?php
require_once('./sistema/conexao.php');
session_start();

$sessao = $_SESSION['sessao_usuario'] ?? session_id();
$produto_id = (int)$_GET['produto'];
$qtd   = max(1, (int)$_GET['qtd']);
$valor = (float)$_GET['total'];
$total = $qtd * $valor;

$stmt = $pdo->prepare("
    INSERT INTO carrinho_temp
    (sessao, produto_id, tipo, quantidade, valor_item, valor_total)
    VALUES
    (:sessao, :produto_id, 'produto', :qtd, :valor, :total)
");

$stmt->execute([
    ':sessao'      => $sessao,
    ':produto_id' => $produto_id,
    ':qtd'         => $qtd,
    ':valor'       => $valor,
    ':total'       => $total
]);

header('Location: observacoes.php');
exit;