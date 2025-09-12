<?php
require_once('conexao.php');
$email = $_POST['recuperar'];
$query = $pdo->query("SELECT * FROM usuarios WHERE email = '$email'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total == 0) {
    echo "Esse e-mail não está cadastrado!";
    exit;
} else {
    $senha = $res[0]['senha'];
    //envia o email
    $destinatario = $email;
    $assunto = $nome_sistema . ' - Recuperação de Senha';
    $mensagem = 'Sua senha é ' . $senha;
    $cabecalho = "From: $email_sistema\r\n";
    mail($destinatario, $assunto, $mensagem, $cabecalho);
    echo "Recuperado com Sucesso";
}
?>