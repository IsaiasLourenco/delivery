<?php
// BANCO DE DADOS LOCAL
$banco = 'delivery';
$servidor = 'localhost';
$usuario = 'root';
$senha = '';

// BANCO DE DADOS HOSPEDADO
// $banco = 'isaia876_delivery';
// $servidor = 'localhost';
// $usuario = 'isaia876_delivery';
// $senha = 'DeliveyVetor256.';

date_default_timezone_set('America/Sao_Paulo');

try{
    $pdo = new PDO("mysql:dbname=$banco;
                          host=$servidor;
                          charset=utf8",
                          "$usuario",
                          "$senha");
} catch(Exception $e) {
    echo 'Não conectado ao banco de dados! <br>' . $e;
}

$nome_sistema = 'Delivery Interativo';
$email_sistema = 'isaias@vetor256.com';
$telefone_sistema = '(19)99674-5466';
$telefone_url = '55'.preg_replace('/[()-]+/', '', $telefone_sistema);
$endereco_sistema = 'Rua Mococa, 880 - Itacolomy - Mogi Guaçu SP';
?>