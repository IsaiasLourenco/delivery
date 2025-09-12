<?php
require_once ('conexao.php');
$email = $_POST['email'];
$senha = $_POST['senha'];
$query = $pdo->query("SELECT * FROM usuarios WHERE email = '$email' AND  senha = '$senha'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total > 0) {
    echo 'Logado';
} else {
    echo 'E-mail ou senha incorretos!';
}
?>