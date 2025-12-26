<?php
require_once './sistema/conexao.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

// Cliente (se identificado)
$clienteId   = $_SESSION['cliente_id']   ?? null;
$nomeCliente = $_SESSION['cliente_nome'] ?? null;
$telefone    = $_SESSION['cliente_tel']  ?? null;

try {
    $pdo->beginTransaction();

    // 🔹 Total do carrinho
    $stmtTotal = $pdo->prepare("
        SELECT COALESCE(SUM(valor_total), 0)
        FROM carrinho_temp
        WHERE sessao = :sessao
    ");
    $stmtTotal->execute([':sessao' => $sessao]);
    $total = (float) $stmtTotal->fetchColumn();

    // 🔹 Criar pedido
    $stmtPedido = $pdo->prepare("
        INSERT INTO pedidos
        (sessao, cliente_id, nome_cliente, telefone, total, status)
        VALUES
        (:sessao, :cliente_id, :nome_cliente, :telefone, :total, :status)
    ");

    $stmtPedido->execute([
        ':sessao'       => $sessao,
        ':cliente_id'   => $clienteId,
        ':nome_cliente' => $nomeCliente,
        ':telefone'     => $telefone,
        ':total'        => $total,
        ':status'       => $clienteId ? 'confirmado' : 'aberto'
    ]);

    $pedidoId = $pdo->lastInsertId();

    // 🔹 Itens do carrinho
    $stmtItens = $pdo->prepare("
        SELECT *
        FROM carrinho_temp
        WHERE sessao = :sessao
    ");
    $stmtItens->execute([':sessao' => $sessao]);
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

    // 🔹 Inserir itens no pedido
    $stmtItem = $pdo->prepare("
        INSERT INTO pedido_itens
        (pedido_id, produto_id, tipo, id_item, quantidade, valor_item, valor_total)
        VALUES
        (:pedido_id, :produto_id, :tipo, :id_item, :quantidade, :valor_item, :valor_total)
    ");

    foreach ($itens as $item) {
        $stmtItem->execute([
            ':pedido_id'   => $pedidoId,
            ':produto_id'  => $item['produto_id'],
            ':tipo'        => $item['tipo'],
            ':id_item'     => $item['id_item'],
            ':quantidade'  => $item['quantidade'],
            ':valor_item'  => $item['valor_item'],
            ':valor_total' => $item['valor_total']
        ]);
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
    echo 'Erro ao finalizar pedido: ' . $e->getMessage();
}
