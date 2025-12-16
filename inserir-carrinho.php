<?php
require_once("sistema/conexao.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sessao     = $_SESSION['sessao_usuario'] ?? session_id();
$produto_id = (int)($_POST['produto_id'] ?? 0);
$id_item    = (int)($_POST['id_item'] ?? 0);
$tipo       = $_POST['tipo'] ?? '';
$marcado    = ($_POST['marcado'] === 'true'); // <<< SEM filter_var

if ($produto_id <= 0 || $id_item <= 0 || !in_array($tipo, ['adicional', 'ingrediente'])) {
    exit('Dados inválidos');
}

$valor = 0;

/**
 * CALCULA VALOR SOMENTE SE FOR INSERÇÃO
 */
if ($marcado) {

    if ($tipo === 'adicional') {
        $stmt = $pdo->prepare("SELECT valor FROM adicionais WHERE id = :id");
        $stmt->execute([':id' => $id_item]);
        $valor = (float)$stmt->fetchColumn(); // positivo

    } elseif ($tipo === 'ingrediente') {
        $stmt = $pdo->prepare("SELECT valor FROM ingredientes WHERE id = :id");
        $stmt->execute([':id' => $id_item]);
        $valor = -(float)$stmt->fetchColumn(); // negativo
    }
}

/**
 * INSERÇÃO
 */
if ($marcado) {

    $check = $pdo->prepare("
        SELECT COUNT(*) 
        FROM carrinho_temp
        WHERE sessao = :sessao
          AND produto_id = :produto_id
          AND tipo = :tipo
          AND id_item = :id_item
    ");

    $check->execute([
        ':sessao'      => $sessao,
        ':produto_id' => $produto_id,
        ':tipo'        => $tipo,
        ':id_item'     => $id_item
    ]);

    if ($check->fetchColumn() == 0) {

        $stmt = $pdo->prepare("
            INSERT INTO carrinho_temp
            (sessao, produto_id, tipo, id_item, quantidade, valor_item, valor_total)
            VALUES
            (:sessao, :produto_id, :tipo, :id_item, 1, :valor, :valor)
        ");

        $stmt->execute([
            ':sessao'      => $sessao,
            ':produto_id' => $produto_id,
            ':tipo'        => $tipo,
            ':id_item'     => $id_item,
            ':valor'       => $valor
        ]);
    }

} 
/**
 * REMOÇÃO
 */
else {

    $stmt = $pdo->prepare("
        DELETE FROM carrinho_temp
        WHERE sessao = :sessao
          AND produto_id = :produto_id
          AND tipo = :tipo
          AND id_item = :id_item
    ");

    $stmt->execute([
        ':sessao'      => $sessao,
        ':produto_id' => $produto_id,
        ':tipo'        => $tipo,
        ':id_item'     => $id_item
    ]);

}
// CALCULA TOTAL ATUALIZADO DO CARRINHO
$stmtTotal = $pdo->prepare("
    SELECT COALESCE(SUM(valor_total),0)
    FROM carrinho_temp
    WHERE sessao = :sessao
");
$stmtTotal->execute([':sessao' => $sessao]);

// RETORNA APENAS O TOTAL FORMATADO
echo number_format($stmtTotal->fetchColumn(), 2, ',', '.');
exit;
