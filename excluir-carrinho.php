<?php
session_start();
require_once("./sistema/conexao.php"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_item = (int)$_POST['id_item'];

    // Validação
    if ($id_item > 0) {
        // Exclui o item do carrinho
        $stmtDelete = $pdo->prepare("
            DELETE FROM carrinho_temp WHERE id = :id_item
        ");
        $stmtDelete->execute([':id_item' => $id_item]);

        // Calcula o novo total após exclusão
        $stmtTotal = $pdo->prepare("
            SELECT COALESCE(SUM(valor_total), 0) FROM carrinho_temp WHERE sessao = :sessao
        ");
        $stmtTotal->execute([':sessao' => $_SESSION['sessao_usuario']]);
        $total = $stmtTotal->fetchColumn();

        echo $total; // Retorna o total atualizado
    }
}
?>
