<?php
require_once './sistema/conexao.php';
session_start();

$telefone = trim($_POST['telefone'] ?? '');

if ($telefone === '') {
    echo json_encode([
        'success' => false,
        'mensagem' => 'Telefone não informado'
    ]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, nome, rua, numero, bairro_id, cpf, email
                       FROM cliente
                       WHERE telefone = :telefone
                       LIMIT 1");
$stmt->execute([':telefone' => $telefone]);
$cli = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cli) {

    // grava sessão do cliente existente
    $_SESSION['cliente_id']   = $cli['id'];
    $_SESSION['cliente_nome'] = $cli['nome'];
    $_SESSION['bairro_id']    = $cli['bairro_id'];

    echo json_encode([
        'success'   => true,
        'existente' => true,
        'nome'      => $cli['nome']
    ]);
    exit;
}

echo json_encode([
    'success'   => true,
    'existente' => false
]);