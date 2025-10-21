<?php
    require_once("../../../conexao.php");
    @session_start();
    $tabela = 'vendas';
    $id_usuario = $_SESSION['id'];
    $id = $_POST['id'];
    $acao = $_POST['acao'];

    if ($acao == 'Finalizado') {
        $pdo->query("UPDATE $tabela SET status_venda = '$acao', pago = 'Sim', usuario_baixa = '$id_usuario' WHERE id = '$id'");    
    } else {
        $pdo->query("UPDATE $tabela SET status_venda = '$acao' WHERE id = '$id'");
    }
    echo 'Alterado com sucesso ';
?>