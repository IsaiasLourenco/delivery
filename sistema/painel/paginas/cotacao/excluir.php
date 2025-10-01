<?php
require_once("../../../conexao.php");
$tabela = 'fornecedores_produtos';

$id = $_POST['id'];
$pdo->query("DELETE FROM $tabela WHERE id = '$id'");
echo 'Excluído com Sucesso';