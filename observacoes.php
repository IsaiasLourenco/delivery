<?php require_once("header.php"); 

?>

<div class="main-container">
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="itens.php" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens">RESUMO DO ITEM</span>
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
            <i class="bi bi-dash-circle-fill text-danger" title="Diminuir quantidade"></i>
            <strong> 01 </strong>
            <i class="bi bi-plus-circle-fill text-success" title="Aumentar quantidade"></i>
        </span>
    </div>

    <div class="txt-area-obs">
        OBSERVAÇÕES
        <div class="form-group">
            <textarea class="form-control" name="obs" id="obs"></textarea>
        </div>
    </div>

    <div class="total">
        <p>Total <strong>R$ 25,00</strong></p>
    </div>

    <div class="mg-t-2">
        <a href="#popup-2" class="btn btn-primary w-100">Adicionar ao carrinho</a>
    </div>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>

<div id="popup-2" class="overlay-2">
    <div class="popup-2">
        <form action="carrinho.php" method="POST">
            <div class="row">
                <div class="col-10">
                    <div class="row">
                        <div class="col-12">
                            <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Telefone" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mg-t-2">
                            <input type="text" name="nome" class="form-control" placeholder="Nome" required>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <a class="close-2" href="#">&times;</a>
                </div>
            </div>
            <div class="row">
                <div class="col-6 text-center">
                    <a href="index.php" class="btn btn-primary mg-t-2 wd-100">Comprar mais</a>
                </div>
                <div class="col-6 text-center">
                    <button class="btn btn-success mg-t-2 wd-100">Finalizar pedido</button>
                </div>
            </div>
            <hr class="linha">
            <div class="conteudo-popup">
                Você só precisará preencher seus dados no primeiro pedido!
            </div>
        </form>
    </div>
</div>