<?php
require_once('./sistema/conexao.php');
session_start();

$telefone = trim($_POST['telefone'] ?? '');
$nome     = trim($_POST['nome'] ?? '');

if ($telefone === '' || $nome === '') {
    echo json_encode(['success' => false]);
    exit;
}

// gera dados técnicos
$email = $telefone . '@delivery.local';
$cpf   = '000.000.000-' . rand(10, 99);

$stmt = $pdo->prepare("INSERT INTO cliente (nome, telefone, email, cpf, data_cad)
                       VALUES (:nome, :telefone, :email, :cpf, CURDATE())");

$stmt->execute([
    ':nome'     => $nome,
    ':telefone' => $telefone,
    ':email'    => $email,
    ':cpf'      => $cpf
]);

$clienteId = $pdo->lastInsertId();

// grava sessão
$_SESSION['cliente_id']   = $clienteId;
$_SESSION['cliente_nome'] = $nome;

echo json_encode(['success' => true]);
