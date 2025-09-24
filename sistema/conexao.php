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

$url_sistema = "http://$_SERVER[HTTP_HOST]/";
$url = explode("//", $url_sistema);
if ($url[1] == 'localhost/') {
    $url_sistema = "http://$_SERVER[HTTP_HOST]/delivery/";
}

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

?>