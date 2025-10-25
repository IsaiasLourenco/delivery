<?php
require_once('../../../conexao.php');
$tabela = 'ingredientes';

$produto = $_POST['id'];
$id_ing = $_POST['id_ing'];
$nome = $_POST['nome'];
$valor = $_POST['valor'];
$valor = str_replace(['R$', '.', ','], ['', '', '.'], $valor);
$ativo = $_POST['ativo'];

if ($id_ing == "" || $id_ing == null) {
$query = $pdo->prepare("INSERT INTO $tabela SET produto = '$produto',
                                                nome = :nome, 
                                                valor = :valor, 
                                                ativo = '$ativo'");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET produto = '$produto',
                                                nome = :nome, 
                                                valor = :valor, 
                                                ativo = '$ativo'
                                                WHERE id = '$id_ing'");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":valor", "$valor");
$query->execute();
echo 'Salvo com Sucesso';
