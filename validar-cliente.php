<?php
require_once('./sistema/conexao.php');
session_start();

$telefone =  $_POST['telefone'] ?? '';

if ($telefone === '') {
    echo json_encode(['success' => false]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        id, nome, bairro_id
    FROM cliente
    WHERE telefone = :telefone
    LIMIT 1
");
$stmt->execute([':telefone' => $telefone]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cliente) {
    $_SESSION['cliente_id']   = $cliente['id'];
    $_SESSION['cliente_nome'] = $cliente['nome'];
    $_SESSION['bairro_id']    = $cliente['bairro_id'];

    echo json_encode([
        'success' => true,
        'existente' => true
    ]);
} else {
    echo json_encode([
        'success' => true,
        'existente' => false
    ]);
}
