<?php
require_once './sistema/conexao.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao    = $_SESSION['sessao_usuario'] ?? session_id();
$clienteId = $_SESSION['cliente_id'] ?? null;

try {
    $pdo->beginTransaction();

    /*
    |--------------------------------------------------------------------------
    | TOTAL DO CARRINHO
    |--------------------------------------------------------------------------
    */
    $stmtTotal = $pdo->prepare("
        SELECT COALESCE(SUM(valor_total), 0)
        FROM carrinho_temp
        WHERE sessao = :sessao
    ");
    $stmtTotal->execute([':sessao' => $sessao]);
    $total = (float) $stmtTotal->fetchColumn();

    if ($total <= 0) {
        throw new Exception('Carrinho vazio');
    }

    /*
    |--------------------------------------------------------------------------
    | CRIA VENDA
    |--------------------------------------------------------------------------
    */
    $stmtVenda = $pdo->prepare("
        INSERT INTO vendas
        (cliente, valor_compra, valor_pago, troco, data_pagamento, hora_pagamento, status_venda, pago)
        VALUES
        (:cliente, :valor_compra, 0, 0, CURDATE(), CURTIME(), 'aberta', 'Nao')
    ");

    $stmtVenda->execute([
        ':cliente'      => $clienteId,
        ':valor_compra' => $total
    ]);

    $vendaId = $pdo->lastInsertId();

    /*
    |--------------------------------------------------------------------------
    | BUSCA ITENS DO CARRINHO
    |--------------------------------------------------------------------------
    */
    $stmtItens = $pdo->prepare("
        SELECT *
        FROM carrinho_temp
        WHERE sessao = :sessao
        ORDER BY id ASC
    ");
    $stmtItens->execute([':sessao' => $sessao]);
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

    /*
    |--------------------------------------------------------------------------
    | PREPARE STATEMENTS
    |--------------------------------------------------------------------------
    */
    $stmtItem = $pdo->prepare("
        INSERT INTO vendas_itens
        (venda_id, item_tipo, item_id, quantidade, valor_unitario, valor_total)
        VALUES
        (:venda_id, :item_tipo, :item_id, :quantidade, :valor_unitario, :valor_total)
    ");

    $stmtOpcao = $pdo->prepare("
        INSERT INTO vendas_itens_opcoes
        (venda_item_id, tipo, id_item, quantidade, valor_unitario, valor_total)
        VALUES
        (:venda_item_id, :tipo, :id_item, :quantidade, :valor_unitario, :valor_total)
    ");

    $mapVendaItens = [];

    /*
    |--------------------------------------------------------------------------
    | INSERE ITENS PRINCIPAIS
    |--------------------------------------------------------------------------
    */
    foreach ($itens as $item) {

        if (in_array($item['tipo'], ['produto', 'variacao'])) {

            $stmtItem->execute([
                ':venda_id'       => $vendaId,
                ':item_tipo'      => $item['tipo'],
                ':item_id'        => $item['id_item'],
                ':quantidade'     => $item['quantidade'],
                ':valor_unitario' => $item['valor_item'],
                ':valor_total'    => $item['valor_total']
            ]);

            // guarda relação carrinho_temp.id → vendas_itens.id
            $mapVendaItens[$item['id']] = $pdo->lastInsertId();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INSERE OPÇÕES (ADICIONAIS / INGREDIENTES)
    |--------------------------------------------------------------------------
    */
    foreach ($itens as $item) {

        if (in_array($item['tipo'], ['adicional', 'ingrediente'])) {

            $pai = $item['produto_pai_id'] ?? null;

            if (!$pai || !isset($mapVendaItens[$pai])) {
                throw new Exception('Opção sem item pai válido');
            }

            $stmtOpcao->execute([
                ':venda_item_id'  => $mapVendaItens[$pai],
                ':tipo'           => $item['tipo'],
                ':id_item'        => $item['id_item'],
                ':quantidade'     => $item['quantidade'],
                ':valor_unitario' => $item['valor_item'],
                ':valor_total'    => $item['valor_total']
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LIMPA CARRINHO
    |--------------------------------------------------------------------------
    */
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
