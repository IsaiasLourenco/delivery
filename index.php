<?php
require_once("header.php");
require_once("funcoes.php");
$queryConfig = $pdo->query("SELECT * FROM config");
$resConfig = $queryConfig->fetchAll(PDO::FETCH_ASSOC);
$total_reg_config = count($resConfig);
if ($total_reg_config > 0) {
    $cards = $resConfig[0]['cards'];
}
?>
<div class="main-container">
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="index">
                <img src="img/<?php echo $logo_sistema; ?>" width="30" height="30" class="d-inline-block align-top" alt="Logo Delivery">
                <?php echo $nome_sistema; ?>
            </a>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>
    <div class="row cards">

        <?php
        $query = $pdo->query("SELECT * FROM categorias WHERE ativo = 'Sim'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = count($res);
        if ($total_reg > 0) {
            for ($i = 0; $i < $total_reg; $i++) {
                foreach ($res[$i] as $key => $value) {
                }
                $id_categoria = $res[$i]['id'];
                $cor = $res[$i]['cor'];
                $foto = $res[$i]['foto'];
                $nome = $res[$i]['nome'];
                $url = gerarSlug($nome);

                $queryProduto = $pdo->query("SELECT * FROM produtos WHERE categoria = '$id_categoria'");
                $resProduto = $queryProduto->fetchAll(PDO::FETCH_ASSOC);
                $tem_produto = count($resProduto);
                $mostrar = 'ocultar';

                if ($tem_produto > 0) {
                    for ($iProduto = 0; $iProduto < $tem_produto; $iProduto++) {
                        $estoque = $resProduto[$iProduto]['estoque'];
                        $tem_estoque = $resProduto[$iProduto]['tem_estoque'];

                        if (($tem_estoque == 'Sim' && $estoque > 0) || ($tem_estoque == 'Não')) {
                            $mostrar = '';
                            break; // já achou um produto visível, pode parar
                        }
                    }
                } else {
                    $mostrar = 'ocultar';
                }

        ?>
                <div class="col-md-4 col-6 <?php echo $mostrar ?>">
                    <a href="categoria-<?php echo $url ?>" class="link-card">
                        <div class="<?php echo ($cards == 'Foto') ? 'foto' : 'card ' . $cor; ?>"
                            <?php if ($cards == 'Foto' && !empty($foto)) { ?>
                            style="background-image: url('sistema/painel/images/categorias/<?php echo $foto; ?>');"
                            <?php } ?>>

                            <?php if ($cards == 'Foto') { ?>
                                <div class="badge2"><?php echo $nome; ?></div>
                            <?php } else { ?>
                                <h3 class="card-title"><?php echo $nome; ?></h3>
                            <?php } ?>

                        </div>
                    </a>
                </div>
        <?php }
        } ?>

    </div>
</div>

<?php require_once("footer.php"); ?>

</body>

</html>