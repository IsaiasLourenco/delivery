<?php
require_once("./sistema/conexao.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$id_item = (int)($_POST['id_item'] ?? 0);
$delta   = (int)($_POST['delta'] ?? 0);

/* Validações básicas */
if ($id_item <= 0 || abs($delta) !== 1) {
    exit;
}

/*
|--------------------------------------------------------------------------
| BUSCA ITEM NO CARRINHO
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
    SELECT id, tipo, quantidade, valor_item
    FROM carrinho_temp
    WHERE id = :id
      AND sessao = :sessao
");
$stmt->execute([
    ':id'     => $id_item,
    ':sessao' => $sessao
]);

$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    exit;
}

/*
|--------------------------------------------------------------------------
| BLOQUEIA ALTERAÇÃO PARA ITENS SEM QUANTIDADE
|--------------------------------------------------------------------------
*/
if (!in_array($item['tipo'], ['produto', 'variacao'])) {
    exit;
}

/*
|--------------------------------------------------------------------------
| CALCULA NOVOS VALORES
|--------------------------------------------------------------------------
*/
$novaQtd = max(1, $item['quantidade'] + $delta);
$novoTotal = $novaQtd * $item['valor_item'];

/*
|--------------------------------------------------------------------------
| ATUALIZA ITEM
|--------------------------------------------------------------------------
*/
$update = $pdo->prepare("
    UPDATE carrinho_temp
    SET quantidade = :qtd,
        valor_total = :total
    WHERE id = :id
");
$update->execute([
    ':qtd'   => $novaQtd,
    ':total' => $novoTotal,
    ':id'    => $id_item
]);

/*
|--------------------------------------------------------------------------
| TOTAL GERAL DO CARRINHO
|--------------------------------------------------------------------------
*/
$stmtTotal = $pdo->prepare("
    SELECT COALESCE(SUM(valor_total),0)
    FROM carrinho_temp
    WHERE sessao = :sessao
");
$stmtTotal->execute([':sessao' => $sessao]);

echo number_format($stmtTotal->fetchColumn(), 2, '.', '');
exit;
