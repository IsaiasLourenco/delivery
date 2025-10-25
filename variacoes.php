<?php require_once("header.php");
$url = $_GET['url'] ?? '';
$query = $pdo->query("SELECT * FROM produtos WHERE url = '$url' AND ativo = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if ($total_reg > 0) {
    $id_produto = $res[0]['id'];
    $nome_produto = $res[0]['nome'];
    $foto_produto = $res[0]['foto'];
    $id_categoria = $res[0]['categoria'];
    $valor_produto = $res[0]['valor_venda'];
    $valor_produtoF = "R$ " . number_format($valor_produto, 2, ',', '.');
    $queryCat = $pdo->query("SELECT url FROM categorias WHERE id = '$id_categoria'");
    $resCat = $queryCat->fetch(PDO::FETCH_ASSOC);
    $url_categoria = $resCat['url'];
}
?>
<link rel="stylesheet" href="css/style.css">
<div class="main-container">
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="categoria-<?php echo $url_categoria ?>" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens"><?php echo mb_strtoupper($nome_produto) ?></span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <ol class="list-group mg-t-6">

        <?php
        $query = $pdo->query("SELECT * FROM variacoes WHERE produto = '$id_produto' AND ativo = 'Sim'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = count($res);
        if ($total_reg > 0) {
            for ($i = 0; $i < $total_reg; $i++) {
                foreach ($res[$i] as $key => $value) {
                }
                $id_var =  $res[$i]['id'];
                $produto_var =  $res[$i]['produto'];
                $sigla_var =  $res[$i]['sigla'];
                $nome_var =  $res[$i]['nome'];
                $descricao_var =  $res[$i]['descricao'];
                $valor_var =  $res[$i]['valor'];

                $valor_varF = "R$ " . number_format($valor_var, 2, ',', '.');
        ?>
                <a href="adicionais-<?php echo $url ?>&item-<?php echo $sigla_var ?>" class="link-neutro">

                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="me-auto">
                            <div class="font-weight-bold">
                                <span><?php echo $sigla_var ?></span><br>
                                <p><?php echo $descricao_var ?></p>
                            </div>
                            <p class="valor-item"><?php echo $valor_varF ?></p>
                        </div>
                    </li>
                </a>
            <?php }
        } else { ?>

            <div class="linha-produto">
                <div>
                    <span><?php echo $nome_produto ?></span>
                    <span class="valor-item-final">(<?php echo $valor_produtoF ?>)</span>
                </div>
                <i class="bi bi-check text-success fs-25"></i>
            </div>
        <?php } ?>
    </ol>

    <div>
        <img class="imagem-produto" src="sistema/painel/images/produtos/<?php echo $foto_produto ?>"
            alt="<?php echo $nome_produto ?>">
    </div>

    <div class='mg-t-2'>
        <a href='observacoes.php' class='btn btn-primary w-100'>Avançar →</a>
    </div>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>