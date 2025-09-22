<?php
require_once('../../../conexao.php');
$tabela = 'variacoes';

$produto = $_POST['id'];
$id_var = $_POST['id_var'];
$sigla = $_POST['sigla'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$valor = $_POST['valor'];
$valor = str_replace(['R$', '.', ','], ['', '', '.'], $valor);
$ativo = $_POST['ativo'];

if ($id_var == "" || $id_var == null) {
$query = $pdo->prepare("INSERT INTO $tabela SET produto = '$produto',
                                                sigla = :sigla, 
                                                nome = :nome, 
                                                descricao = :descricao,
                                                valor = :valor,
                                                ativo = '$ativo'");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET produto = '$produto',
                                                sigla = :sigla, 
                                                nome = :nome, 
                                                descricao = :descricao,
                                                valor = :valor,
                                                ativo = '$ativo'
                                                WHERE id = '$id_var'");
}

$query->bindValue(":sigla", "$sigla");
$query->bindValue(":nome", "$nome");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->execute();
echo 'Salvo com Sucesso';
