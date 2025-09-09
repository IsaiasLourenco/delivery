<?php require_once("header.php"); ?>

<div class="main-container">
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="itens.php" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens">PIZZA CALABRESA</span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <ol class="list-group mg-t-6">
        <a href="adicionais.php" class="link-neutro">
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                    <div class="font-weight-bold">
                        <span>Pequena</span>
                    </div>
                    <p class="valor-item">R$ 25,00</p>
                </div>
            </li>
        </a>
        <a href="#" class="link-neutro">
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                    <div class="font-weight-bold">
                        <span>Média</span>
                    </div>
                    <p class="valor-item">R$ 30,00</p>
                </div>
            </li>
        </a>
        <a href="#" class="link-neutro">
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                    <div class="font-weight-bold">
                        <span>Grande</span>
                    </div>
                    <p class="valor-item">R$ 45,00</p>
                </div>
            </li>
        </a>
    </ol>

    <div>
        <img class="imagem-produto" src="img/produtos/calabresa.jpg" alt="Pizza Calabresa">
    </div>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>