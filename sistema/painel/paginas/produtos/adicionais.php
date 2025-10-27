<?php
require_once('../../../conexao.php');
$tabela = 'adicionais';

$produto = $_POST['id'];
$id_ad = $_POST['id_ad'];
$nome = $_POST['nome'];
$valor = $_POST['valor'];
$valor = str_replace(['R$', '.', ','], ['', '', '.'], $valor);
$ativo = $_POST['ativo'];
$categoria = $_POST['categoria'];

if ($id_ad == "" || $id_ad == null) {
$query = $pdo->prepare("INSERT INTO $tabela SET produto = '$produto',
                                                nome = :nome, 
                                                valor = :valor, 
                                                ativo = '$ativo',
                                                categoria = :categoria");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET produto = '$produto',
                                                nome = :nome, 
                                                valor = :valor, 
                                                ativo = '$ativo',
                                                categoria = :categoria
                                                WHERE id = '$id_ad'");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":valor", "$valor");
$query->bindValue(":categoria", "$categoria");
$query->execute();
echo 'Salvo com Sucesso';
