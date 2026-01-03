<?php
require_once './sistema/conexao.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$clienteId = $_SESSION['cliente_id'] ?? null;

if (!$clienteId) {
    http_response_code(403);
    exit('Cliente não autenticado');
}

/*
|--------------------------------------------------------------------------
| DADOS RECEBIDOS
|--------------------------------------------------------------------------
*/
$vendaId      = (int) ($_POST['venda_id'] ?? 0);
$tipoPedido   = $_POST['tipo_pedido'] ?? '';
$pagamento    = $_POST['pagamento'] ?? '';
$troco        = (float) ($_POST['troco'] ?? 0);
$bairroId     = (int) ($_POST['bairro'] ?? 0);
$rua          = trim($_POST['rua'] ?? '');
$numero       = trim($_POST['numero'] ?? '');

/*
|--------------------------------------------------------------------------
| VALIDAÇÕES BÁSICAS
|--------------------------------------------------------------------------
*/
if ($vendaId <= 0 || !in_array($tipoPedido, ['retirada','local','entrega'])) {
    exit('Dados inválidos');
}

if (!in_array($pagamento, ['pix','dinheiro','cartao'])) {
    exit('Pagamento inválido');
}

/*
|--------------------------------------------------------------------------
| BUSCA VALOR ENTREGA
|--------------------------------------------------------------------------
*/
$valorEntrega = 0.00;

if ($tipoPedido === 'entrega') {

    if ($bairroId <= 0) {
        exit('Bairro obrigatório');
    }

    $stmtBairro = $pdo->prepare("
        SELECT valor
        FROM bairros
        WHERE id = :id
    ");
    $stmtBairro->execute([':id' => $bairroId]);
    $valorEntrega = (float) $stmtBairro->fetchColumn();

    if ($valorEntrega <= 0) {
        exit('Bairro inválido');
    }
}

/*
|--------------------------------------------------------------------------
| ATUALIZA VENDA
|--------------------------------------------------------------------------
*/
$pdo->beginTransaction();

$stmtUpdate = $pdo->prepare("
    UPDATE vendas SET
        tipo_pedido    = :tipo_pedido,
        forma_pagamento= :pagamento,
        valor_entrega  = :valor_entrega,
        valor_pago     = :valor_pago,
        troco          = :troco,
        status_venda   = 'confirmada'
    WHERE id = :id
      AND cliente = :cliente
");

$valorPago = ($pagamento === 'dinheiro') ? $troco : 0;

$stmtUpdate->execute([
    ':tipo_pedido'   => $tipoPedido,
    ':pagamento'     => $pagamento,
    ':valor_entrega' => $valorEntrega,
    ':valor_pago'    => $valorPago,
    ':troco'         => $troco,
    ':id'            => $vendaId,
    ':cliente'       => $clienteId
]);

/*
|--------------------------------------------------------------------------
| SALVA ENDEREÇO (SE ENTREGA)
|--------------------------------------------------------------------------
*/
if ($tipoPedido === 'entrega') {

    $stmtEndereco = $pdo->prepare("
        UPDATE cliente SET
            rua = :rua,
            numero = :numero,
            bairro_id = :bairro
        WHERE id = :cliente
    ");

    $stmtEndereco->execute([
        ':rua'     => $rua,
        ':numero'  => $numero,
        ':bairro'  => $bairroId,
        ':cliente' => $clienteId
    ]);
}

$pdo->commit();

echo 'OK';
