<?php
require_once("../../../conexao.php");

$id_fornecedor = $_POST['fornecedor'] ?? 0;

if ($id_fornecedor == 0) {
    echo '<option value="">Nenhum produto vinculado</option>';
    exit();
}

// Buscar todos os produtos vinculados ao fornecedor
$query = $pdo->prepare("SELECT fp.produto_id, p.nome 
                        FROM fornecedores_produtos fp 
                        JOIN produtos p ON fp.produto_id = p.id 
                        WHERE fp.fornecedor_id = :id 
                        ORDER BY p.nome ASC");
$query->bindValue(":id", $id_fornecedor);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) == 0) {
    echo '<option value="">Nenhum produto vinculado</option>';
    exit();
}

echo '<option value="">Selecione um Produto</option>';
foreach ($res as $row) {
    echo '<option value="' . $row['produto_id'] . '">' . $row['nome'] . '</option>';
}