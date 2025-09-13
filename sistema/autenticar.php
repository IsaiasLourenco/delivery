<?php
session_start();
require_once('conexao.php');
$email = $_POST['email'];
$senha = $_POST['senha'];
$query = $pdo->query("SELECT * FROM usuarios WHERE (email = '$email' OR cpf = '$email') AND  senha = '$senha'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total > 0) {
    $_SESSION['id'] = $res[0]['id'];
    $_SESSION['nome'] = $res[0]['nome'];
    $_SESSION['email'] = $res[0]['email'];
    $_SESSION['cpf'] = $res[0]['cpf'];
    $_SESSION['nivel'] = $res[0]['nivel'];
    $_SESSION['ativo'] = $res[0]['ativo'];
    $_SESSION['data_cad'] = $res[0]['data_cad'];
    if ($_SESSION['ativo'] != 'Sim') {
        echo "<script>window.alert('Usuário INATIVO!');</script>";
        echo "<script>window.location.href='index.php';</script>";
    } else {
        header("Location: painel");
        exit();
    }
} else {
    echo "<script>window.alert('Usuário ou senha incorretos!');</script>";
    echo "<script>window.location.href='index.php';</script>";
}
