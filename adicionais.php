<?php
require_once("header.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();
$_SESSION['sessao_usuario'] = $sessao;

/* LIMPA sessionStorage SE CARRINHO VAZIO */
$stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM carrinho_temp WHERE sessao = :sessao");
$stmtCheck->execute([':sessao' => $sessao]);

if ($stmtCheck->fetchColumn() == 0) {
    echo "<script>
        sessionStorage.removeItem('estado_itens');
        sessionStorage.removeItem('produto_atual');
    </script>";
}

$url  = $_GET['url']  ?? '';
$item = $_GET['item'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE url = :url AND ativo = 'Sim'");
$stmt->execute([':url' => $url]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die('Produto não encontrado');
}

$id_produto   = (int)$produto['id'];
$nome_produto = $produto['nome'];
$valor_base   = (float)$produto['valor_venda'];

$descricao_var = '';
$sigla_var     = '';
$valor_var     = 0;
$id_variacao   = null;

/* VARIAÇÃO */
if ($item) {
    $stmtVar = $pdo->prepare("
        SELECT * FROM variacoes 
        WHERE produto = :produto AND sigla = :sigla
    ");
    $stmtVar->execute([
        ':produto' => $id_produto,
        ':sigla'   => $item
    ]);
    $var = $stmtVar->fetch(PDO::FETCH_ASSOC);

    if ($var) {
        $id_variacao   = (int)$var['id'];
        $descricao_var = $var['descricao'];
        $sigla_var     = $var['sigla'];
        $valor_var     = (float)$var['valor'];
    }
}

/* PRODUTO BASE (SEM VARIAÇÃO) */
if (!$id_variacao) {

    $checkProduto = $pdo->prepare("
        SELECT COUNT(*) 
        FROM carrinho_temp 
        WHERE sessao = :sessao AND tipo = 'produto'
    ");
    $checkProduto->execute([':sessao' => $sessao]);

    if ($checkProduto->fetchColumn() == 0) {

        $insertProduto = $pdo->prepare("
            INSERT INTO carrinho_temp
            (sessao, tipo, id_item, quantidade, valor_item, valor_total)
            VALUES
            (:sessao, 'produto', :id_item, 1, :valor, :valor)
        ");

        $insertProduto->execute([
            ':sessao'  => $sessao,
            ':id_item' => $id_produto,
            ':valor'   => $valor_base
        ]);
    }
}

/* VARIAÇÃO */
if ($id_variacao && $valor_var > 0) {

    $checkVar = $pdo->prepare("
        SELECT COUNT(*) 
        FROM carrinho_temp 
        WHERE sessao = :sessao AND tipo = 'variacao'
    ");
    $checkVar->execute([':sessao' => $sessao]);

    if ($checkVar->fetchColumn() == 0) {

        $insertVar = $pdo->prepare("
            INSERT INTO carrinho_temp
            (sessao, tipo, id_item, produto_pai_id, quantidade, valor_item, valor_total)
            VALUES
            (:sessao, 'variacao', :variacao, :produto, 1, :valor, :valor)
        ");

        $insertVar->execute([
            ':sessao'   => $sessao,
            ':variacao' => $id_variacao,
            ':produto'  => $id_produto,
            ':valor'    => $valor_var
        ]);
    }
}

/* TOTAL */
$stmtTotal = $pdo->prepare("
    SELECT COALESCE(SUM(valor_total),0) 
    FROM carrinho_temp 
    WHERE sessao = :sessao
");
$stmtTotal->execute([':sessao' => $sessao]);
$total  = (float)$stmtTotal->fetchColumn();
$totalF = "R$ " . number_format($total, 2, ',', '.');
?>
