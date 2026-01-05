<?php
session_start();

$telefone = trim($_POST['telefone'] ?? '');

if ($telefone !== '') {
    $_SESSION['telefone_temp'] = $telefone;
}

echo json_encode(['success' => true]);