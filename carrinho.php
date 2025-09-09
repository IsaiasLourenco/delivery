<?php require_once("header.php"); ?>

<div class="main-container">
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="#" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens">RESUMO DO PEDIDO</span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <ol class="list-group mg-t-6">
        <a href="#popup-excluir" class="link-neutro mg-t-3">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="sem-bold"><strong>Pizza Calabresa Pequena</strong></span>
                <i class="bi bi-trash text-danger" title="Excluir produto"></i>
            </li>
            <span class="area-qtd">
                <i class="bi bi-dash-circle-fill text-danger" title="Diminuir quantidade"></i>
                <strong> 01 </strong>
                <i class="bi bi-plus-circle-fill text-success" title="Aumentar quantidade"></i>
                R$ 25,00
            </span>
        </a>
        <a href="#popup-excluir" class="link-neutro mg-t-3">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="sem-bold"><strong>Pastel Palmito Grande</strong></span>
                <i class="bi bi-trash text-danger" title="Excluir produto"></i>
            </li>
            <span class="area-qtd">
                <i class="bi bi-dash-circle-fill text-danger" title="Diminuir quantidade"></i>
                <strong> 01 </strong>
                <i class="bi bi-plus-circle-fill text-success" title="Aumentar quantidade"></i>
                R$ 12,00
            </span>
        </a>
        <a href="#popup-excluir" class="link-neutro mg-t-3">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="sem-bold"><strong>Coca Cola Lata</strong></span>
                <i class="bi bi-trash text-danger" title="Excluir produto"></i>
            </li>
            <span class="area-qtd">
                <i class="bi bi-dash-circle-fill text-danger" title="Diminuir quantidade"></i>
                <strong> 01 </strong>
                <i class="bi bi-plus-circle-fill text-success" title="Aumentar quantidade"></i>
                R$ 6,00
            </span>
        </a>
        <a href="#popup-excluir" class="link-neutro mg-t-3">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="sem-bold"><strong>Brigadeiro de Colher</strong></span>
                <i class="bi bi-trash text-danger" title="Excluir produto"></i>
            </li>
            <span class="area-qtd">
                <i class="bi bi-dash-circle-fill text-danger" title="Diminuir quantidade"></i>
                <strong> 01 </strong>
                <i class="bi bi-plus-circle-fill text-success" title="Aumentar quantidade"></i>
                R$ 10,00
            </span>
        </a>
    </ol>

    <hr class="mg-t-3">
    <div class="mg-t-2">
        <div class="total">
            <p class="area-qtd">SubTotal <strong>R$ 53,00</strong></p>
        </div>
    </div>

    <div class="mg-t-8">
        <a href="#" class="btn btn-primary w-100">Finalizar Pedido</a>
    </div>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>

<div id="popup-excluir" class="overlay-excluir">
    <div class="popup-excluir">
        <div class="row">
            <div class="col-9">
                <h3 class="titulo-popup">Deseja realmente excluir esse item?</h3>
            </div>
            <div class="col-3">
                <a class="close-excluir" href="#">&times;</a>
            </div>
        </div>
        <hr class="linha">
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary mx-2">Sim</button>
            <button class="btn btn-danger mx-2">Não</button>
        </div>
    </div>
</div>