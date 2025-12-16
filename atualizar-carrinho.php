<?php
session_start();
require_once("./sistema/conexao.php"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_item = (int)$_POST['id_item'];
    $delta = (int)$_POST['delta']; // A quantidade a ser adicionada (+1 ou -1)

    // Validação simples
    if ($id_item > 0 && abs($delta) === 1) {
        // Buscar o item no carrinho
        $stmt = $pdo->prepare("
            SELECT * FROM carrinho_temp WHERE id = :id_item
        ");
        $stmt->execute([':id_item' => $id_item]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // Calcula a nova quantidade
            $nova_quantidade = max(1, $item['quantidade'] + $delta); // Não deixar quantidade abaixo de 1
            $novo_total = $nova_quantidade * $item['valor_item'];

            // Atualiza a quantidade e o valor total no carrinho
            $stmtUpdate = $pdo->prepare("
                UPDATE carrinho_temp
                SET quantidade = :quantidade, valor_total = :valor_total
                WHERE id = :id_item
            ");
            $stmtUpdate->execute([
                ':quantidade' => $nova_quantidade,
                ':valor_total' => $novo_total,
                ':id_item' => $id_item
            ]);

            // Retorna o novo total
            $stmtTotal = $pdo->prepare("
                SELECT COALESCE(SUM(valor_total), 0) FROM carrinho_temp WHERE sessao = :sessao
            ");
            $stmtTotal->execute([':sessao' => $_SESSION['sessao_usuario']]);
            $total = $stmtTotal->fetchColumn();

            echo $total; // Retorna o total atualizado
        }
    }
}
?>
