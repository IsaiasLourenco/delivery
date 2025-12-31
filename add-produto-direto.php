<?php
require_once './sistema/conexao.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

$produtoId = (int) ($_GET['produto'] ?? 0);
$qtd       = max(1, (int) ($_GET['qtd'] ?? 1));

if ($produtoId <= 0) {
    header('Location: index.php');
    exit;
}

/**
 * 🔹 Buscar preço do produto no banco
 */
$stmtProduto = $pdo->prepare("
    SELECT valor_venda
    FROM produtos
    WHERE id = :id
    AND ativo = 'Sim'
");
$stmtProduto->execute([':id' => $produtoId]);
$produto = $stmtProduto->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header('Location: index.php');
    exit;
}

$valorUnitario = (float) $produto['valor_venda'];
$valorTotal    = $valorUnitario * $qtd;

/**
 * 🔹 Inserir no carrinho temporário
 */
$stmtInsert = $pdo->prepare("
    INSERT INTO carrinho_temp
    (sessao, produto_id, tipo, quantidade, valor_item, valor_total)
    VALUES
    (:sessao, :produto_id, 'produto', :quantidade, :valor_item, :valor_total)
");

$stmtInsert->execute([
    ':sessao'      => $sessao,
    ':produto_id' => $produtoId,
    ':quantidade' => $qtd,
    ':valor_item' => $valorUnitario,
    ':valor_total'=> $valorTotal
]);

header('Location: observacoes.php');
exit;
