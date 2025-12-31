<?php
require_once './sistema/conexao.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();
$clienteId = $_SESSION['cliente_id'] ?? null;

try {
    $pdo->beginTransaction();

    // 🔹 Total do carrinho
    $stmtTotal = $pdo->prepare("
        SELECT COALESCE(SUM(valor_total), 0)
        FROM carrinho_temp
        WHERE sessao = :sessao
    ");
    $stmtTotal->execute([':sessao' => $sessao]);
    $total = (float)$stmtTotal->fetchColumn();

    if ($total <= 0) {
        throw new Exception('Carrinho vazio');
    }

    // 🔹 Criar venda
    $stmtVenda = $pdo->prepare("
        INSERT INTO vendas
        (cliente, valor_compra, valor_pago, troco, data_pagamento, hora_pagamento, status_venda, pago)
        VALUES
        (:cliente, :valor_compra, :valor_pago, :troco, CURDATE(), CURTIME(), :status_venda, :pago)
    ");

    $stmtVenda->execute([
        ':cliente'       => $clienteId,
        ':valor_compra'  => $total,
        ':valor_pago'    => 0.00,
        ':troco'         => 0.00,
        ':status_venda'  => 'aberta',
        ':pago'          => 'Nao'
    ]);

    $vendaId = $pdo->lastInsertId();

    // 🔹 Itens do carrinho
    $stmtItens = $pdo->prepare("
        SELECT *
        FROM carrinho_temp
        WHERE sessao = :sessao
    ");
    $stmtItens->execute([':sessao' => $sessao]);
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

    // 🔹 Inserir itens da venda
    $stmtItem = $pdo->prepare("INSERT INTO vendas_itens (venda_id, produto_id, quantidade, valor_unitario, valor_total)
                               VALUES (:venda_id, :produto_id, :quantidade, :valor_unitario, :valor_total)");

    $stmtOpcao = $pdo->prepare("INSERT INTO vendas_itens_opcoes (venda_item_id, tipo, id_item, quantidade, valor_unitario, valor_total)
                                VALUES (:venda_item_id, :tipo, :id_item, :quantidade, :valor_unitario, :valor_total)");

    $vendaItemId = null;

    foreach ($itens as $item) {

        if ($item['tipo'] === 'produto') {

            $stmtItem->execute([
                ':venda_id'       => $vendaId,
                ':produto_id'     => $item['produto_id'],
                ':quantidade'     => $item['quantidade'],
                ':valor_unitario' => $item['valor_item'],
                ':valor_total'    => $item['valor_total']
            ]);

            // guarda o ID do item principal
            $vendaItemId = $pdo->lastInsertId();
        } else {

            // segurança extra
            if (!$vendaItemId) {
                throw new Exception('Opção sem item principal');
            }

            $stmtOpcao->execute([
                ':venda_item_id'  => $vendaItemId,
                ':tipo'           => $item['tipo'],
                ':id_item'        => $item['id_item'],
                ':quantidade'     => $item['quantidade'],
                ':valor_unitario' => $item['valor_item'],
                ':valor_total'    => $item['valor_total']
            ]);
        }
    }

    // 🔹 Limpar carrinho
    $stmtLimpa = $pdo->prepare("
        DELETE FROM carrinho_temp
        WHERE sessao = :sessao
    ");
    $stmtLimpa->execute([':sessao' => $sessao]);

    $pdo->commit();

    header('Location: confirmacao.php');
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    echo 'Erro ao finalizar venda: ' . $e->getMessage();
}
