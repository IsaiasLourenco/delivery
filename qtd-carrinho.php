<?php
require_once './sistema/conexao.php';
session_start();

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

$stmt = $pdo->prepare("SELECT COALESCE(SUM(quantidade), 0)
                       FROM carrinho_temp
                       WHERE sessao = :sessao");
$stmt->execute([':sessao' => $sessao]);

echo $stmt->fetchColumn();
