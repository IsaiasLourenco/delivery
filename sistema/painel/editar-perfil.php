<?php
require_once('../conexao.php');
$id = $_POST['id-usuario'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$cep = $_POST['cep'];
$rua = $_POST['rua'];
$numero = $_POST['numero'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$senha = $_POST['senha'];
$conf_senha = $_POST['conf-senha'];
$nivel = $_POST['nivel'];
$ativo = $_POST['ativo'];

if ($senha != $conf_senha) {
    echo 'As senhas não se coincidem!! Elas PRECISAM ser iguais!!';
    exit;
}

//VALIDAR EMAIL
$query = $pdo->query("SELECT * FROM usuarios WHERE email = '$email'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 AND $id != $res[0]['id']) {
    echo 'EMAIL já cadastrado!!';
    exit;
}

//VALIDAR CPF
$query = $pdo->query("SELECT * FROM usuarios WHERE cpf = '$cpf'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 AND $id != $res[0]['id']) {
    echo 'CPF já cadastrado!!';
    exit;
}
?>