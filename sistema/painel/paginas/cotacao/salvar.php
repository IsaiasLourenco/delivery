<?php
require_once('../../../conexao.php');
$tabela = 'fornecedores_produtos';

$id = $_POST['id'];
$fornecedor_id = $_POST['fornecedor_id'];
$produto_id = $_POST['produto_id'];
$valor_compra = $_POST['valor_compra'];
$valor_compra = str_replace(['R$', '.', ','], ['', '', '.'], $valor_compra);
$prazo_entrega = $_POST['prazo_entrega'];
$principal = $_POST['principal'];
$observacoes = $_POST['observacoes'];

// Validação opcional: evitar duplicidade
$query = $pdo->query("SELECT * FROM $tabela WHERE fornecedor_id = '$fornecedor_id' AND produto_id = '$produto_id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 && $id != $res[0]['id']) {
    echo 'Este vínculo já existe!';
    exit();
}

if ($id == "" || $id == null) {
    $query = $pdo->prepare("INSERT INTO $tabela SET 
        fornecedor_id = :fornecedor_id,
        produto_id = :produto_id,
        valor_compra = :valor_compra,
        prazo_entrega = :prazo_entrega,
        principal = :principal,
        observacoes = :observacoes");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET 
        fornecedor_id = :fornecedor_id,
        produto_id = :produto_id,
        valor_compra = :valor_compra,
        prazo_entrega = :prazo_entrega,
        principal = :principal,
        observacoes = :observacoes
        WHERE id = '$id'");
}

$query->bindValue(":fornecedor_id", $fornecedor_id);
$query->bindValue(":produto_id", $produto_id);
$query->bindValue(":valor_compra", $valor_compra);
$query->bindValue(":prazo_entrega", $prazo_entrega);
$query->bindValue(":principal", $principal);
$query->bindValue(":observacoes", $observacoes);
$query->execute();

echo 'Salvo com Sucesso';