<?php
require_once("header.php");
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
            <a class="navbar-brand" href="index.php">
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
                $cor = $res[$i]['cor'];
                $nome = $res[$i]['nome'];
                $foto = $res[$i]['foto'];
        ?>
                <div class="col-md-4 col-6">
                    <a href="itens.php" class="link-card">
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