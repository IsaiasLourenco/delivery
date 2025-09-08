<?php require_once("header.php"); ?>

<div class="main-container">
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="adicionais.php" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens">RESUMO DO PEDIDO</span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <div class="obs">
        <strong>PIZZA CALABRESA</strong>
    </div>

    <div class="qtd">
        Quantidade
        <span class="area-qtd">
            <i class="bi bi-dash-circle-fill text-danger"></i>
            <strong> 01 </strong>
            <i class="bi bi-plus-circle-fill text-success"></i>
        </span>
    </div>

    <div class="txt-area-obs">
        OBSERVAÇÕES
        <div class="form-group">
            <textarea class="form-control" name="obs" id="obs"></textarea>
        </div>
    </div>

    <div class="total">
        <p>Total - <strong>R$ 25,00</strong></p>
    </div>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>