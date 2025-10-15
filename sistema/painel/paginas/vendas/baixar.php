<?php 
require_once("../../../conexao.php");
@session_start();
$tabela = 'vendas';
$id_usuario = $_SESSION['id'];

$id = $_POST['id'];

$pdo->query("UPDATE $tabela SET pago = 'Sim', 
                                usuario_baixa = '$id_usuario'
                                where id = '$id'");

echo 'Baixado com Sucesso';
 ?>