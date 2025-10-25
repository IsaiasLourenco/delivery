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
}
?>

<div class="main-container">
    <?php
    $sigla_var = $_GET['sigla_var'] ?? '';
    $queryVar = $pdo->query("SELECT * FROM variacoes WHERE produto = '$id_produto' AND sigla = '$sigla_var'");
    $resVar = $queryVar->fetch(PDO::FETCH_ASSOC);

    $descricao_var = $resVar['descricao'] ?? '';
    ?>
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="variacoes.php?url=<?php echo $url ?>" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens"><?php echo mb_strtoupper($nome_produto) ?> - <?php echo $sigla_var ?></span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <ol class="list-group mg-t-6">

        <div class="remover-ing">
            Adicionais?
            <?php
            $queryAd = $pdo->query("SELECT * FROM adicionais WHERE produto = '$id_produto' AND ativo = 'Sim'");
            $resAd = $queryAd->fetchAll(PDO::FETCH_ASSOC);
            $total_reg_ad = count($resAd);
            if ($total_reg_ad > 0) {
                for ($i = 0; $i < $total_reg_ad; $i++) {
                    foreach ($resAd[$i] as $key => $value) {
                    }
                    $id_ad = $resAd[$i]['id'];
                    $nome_ad = $resAd[$i]['nome'];
                    $valor_ad = $resAd[$i]['valor'];
                    $valor_adF = "R$ " . number_format($valor_ad, 2, ',', '.');
            ?>

                    <a href="adicionais.php" class="link-neutro">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="sem-bold"><?php echo $nome_ad ?> <span class="valor-item"><?php echo $valor_adF ?></span></span>
                            <i class="bi bi-square"></i>
                        </li>
                    </a>
                <?php
                } ?>
        </div>
    </ol>
    <div class="remover-ing">
        Remover Ingredientes?

        <ol class="list-group mg-t-1">

            <?php
                $queryIng = $pdo->query("SELECT * FROM ingredientes WHERE produto = '$id_produto' AND ativo = 'Sim'");
                $resIng = $queryIng->fetchAll(PDO::FETCH_ASSOC);
                $total_reg_ing = count($resIng);
                if ($total_reg_ing > 0) {
                    for ($i = 0; $i < $total_reg_ing; $i++) {
                        foreach ($resIng[$i] as $key => $value) {
                        }
                        $id_ing = $resIng[$i]['id'];
                        $nome_ing = $resIng[$i]['nome'];
                        $valor_ing = $resIng[$i]['valor'];
                        $valor_ingF = "R$ " . number_format($valor_ing, 2, ',', '.');
            ?>
                    <a href="adicionais.php" class="link-neutro">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="sem-bold"><?php echo $nome_ing ?> <span class="valor-menos"><?php echo $valor_ingF ?></span></span>
                            <i class="bi bi-check-square"></i>
                        </li>
                    </a>
        <?php }
                }
            } ?>
        </ol>
    </div>

    <div class="total">
        <p>Total <strong>R$ 25,00</strong></p>
    </div>

    <div class="mg-t-2">
        <a href="observacoes.php" class="btn btn-primary w-100">Avançar →</a>
    </div>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>