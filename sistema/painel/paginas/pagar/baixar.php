<?php 
require_once("../../../conexao.php");
@session_start();
$tabela = 'pagar';
$id_usuario = $_SESSION['id'];

$id = $_POST['id'];

$pdo->query("UPDATE $tabela SET pago = 'Sim', 
                                usuario_baixa = '$id_usuario', 
                                data_pagamento = curDate() 
                                WHERE id = '$id'");

echo 'Baixado com Sucesso';
 ?>