<?php require_once("header.php");
$url = $_GET['url'] ?? '';
$query = $pdo->query("SELECT * FROM produtos WHERE url = '$url' AND ativo = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if ($total_reg > 0) {
    $id_produto = $res[0]['id'];
    $nome_produto = $res[0]['nome'];
    $descricao_produto = $res[0]['descricao'];
    $foto_produto = $res[0]['foto'];
    $id_categoria = $res[0]['categoria'];
    $valor_produto = $res[0]['valor_venda'];
    $valor_produtoF = "R$ " . number_format($valor_produto, 2, ',', '.');
    $queryCat = $pdo->query("SELECT url FROM categorias WHERE id = '$id_categoria'");
    $resCat = $queryCat->fetch(PDO::FETCH_ASSOC);
    $url_categoria = $resCat['url'];

    // Verifica se há adicionais
    $queryAd = $pdo->query("SELECT * FROM adicionais WHERE produto = '$id_produto' AND ativo = 'Sim'");
    $resAd = $queryAd->fetchAll(PDO::FETCH_ASSOC);
    $total_adicionais = count($resAd);

    // Verifica se há ingredientes
    $queryIng = $pdo->query("SELECT * FROM ingredientes WHERE produto = '$id_produto' AND ativo = 'Sim'");
    $resIng = $queryIng->fetchAll(PDO::FETCH_ASSOC);
    $total_ingredientes = count($resIng);

    // Define se deve ir para adicionais.php ou direto para observacoes.php
    $tem_adicionais_ou_ingredientes = ($total_adicionais + $total_ingredientes) > 0;
}
?>
<style>
    .link-neutro,
    .link-neutro:hover,
    .link-neutro:focus,
    .link-neutro:visited {
        text-decoration: none;
        color: inherit;
        font-weight: bold;
        cursor: pointer;
    }
</style>
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
                $total_item = $valor_var;
        ?>
                <?php if ($tem_adicionais_ou_ingredientes) { ?>
                    <a href="adicionais.php?url=<?php echo $url ?>&item=<?php echo $sigla_var ?>" class="link-neutro">
                    <?php } else { ?>
                        <a href="add-produto-direto.php?produto=<?php echo $id_produto ?>&variacao=<?php echo $id_var ?>" class="link-neutro">
                        <?php } ?>

                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="me-auto">
                                <div class="font-weight-bold">
                                    <span><?php echo $descricao_var ?></span><br>
                                </div>
                                <p class="valor-item-final"><?php echo $valor_varF ?></p>
                            </div>
                        </li>
                        </a>
                    <?php }
            } else { ?>
                    <?php $total_item = $valor_produto; ?>
                    <div class="linha-produto font-weight-bold">
                        <a href="#" class="link-neutro"
                            onclick="adicionarProdutoDireto(<?php echo $id_produto ?>)">
                            <div class="link-neutro">
                                <span><?php echo $descricao_produto ?></span><br>
                                <span class="valor-item-final"><?php echo $valor_produtoF ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="qtd final">
                        Quantidade
                        <span class="area-qtd">
                            <button type="button" onclick="alterarQtd(-1)" class="btn btn-link text-danger">
                                <i class="bi bi-dash-circle-fill"></i>
                            </button>

                            <strong id="qtd">1</strong>
                            <input type="hidden" name="quantidade" id="qtd_input" value="1">

                            <button type="button" onclick="alterarQtd(1)" class="btn btn-link text-success">
                                <i class="bi bi-plus-circle-fill"></i>
                            </button>
                        </span>
                    </div>
                <?php } ?>
    </ol>

    <div>
        <img class="imagem-produto" src="sistema/painel/images/produtos/<?php echo $foto_produto ?>"
            alt="<?php echo $nome_produto ?>">
    </div>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>

<script>
    function alterarQtd(delta) {
        let qtd = parseInt(document.getElementById('qtd').textContent);
        qtd = Math.max(1, qtd + delta);

        document.getElementById('qtd').textContent = qtd;
        document.getElementById('qtd_input').value = qtd;

        let total = qtd * TOTAL_BASE;
        document.getElementById('total').textContent =
            'R$ ' + total.toFixed(2).replace('.', ',');
    }

    function adicionarProdutoDireto(produtoId) {
        const qtd = parseInt(document.getElementById('qtd').textContent);

        window.location.href =
            `add-produto-direto.php?produto=${produtoId}&qtd=${qtd}`;
    }
</script>