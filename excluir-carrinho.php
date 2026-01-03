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

if ($id_item <= 0) {
    exit;
}

/*
|--------------------------------------------------------------------------
| BUSCA ITEM PARA SABER O TIPO
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
    SELECT id, tipo
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
| EXCLUSÃO
|--------------------------------------------------------------------------
*/
if (in_array($item['tipo'], ['produto', 'variacao'])) {

    // Exclui o item pai + filhos (adicionais/ingredientes)
    $del = $pdo->prepare("
        DELETE FROM carrinho_temp
        WHERE sessao = :sessao
          AND (id = :id OR produto_pai_id = :id)
    ");
    $del->execute([
        ':sessao' => $sessao,
        ':id'     => $id_item
    ]);

} else {

    // Exclui somente o item
    $del = $pdo->prepare("
        DELETE FROM carrinho_temp
        WHERE id = :id
          AND sessao = :sessao
    ");
    $del->execute([
        ':id'     => $id_item,
        ':sessao' => $sessao
    ]);
}

/*
|--------------------------------------------------------------------------
| TOTAL ATUALIZADO
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
