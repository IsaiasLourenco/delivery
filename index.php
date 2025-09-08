<?php require_once("header.php"); ?>
<div class="main-container">
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="Logo Delivery">
                Delivery Interativo
            </a>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>
    <div class="row cards">
        <div class="col-md-4 col-6">
            <a href="itens.php" class="link-card">
                <div class="card azul">
                    <h3 class="card-title">PIZZAS</h3>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-6">
            <a href="#" class="link-card">
                <div class="card rosa">
                    <h3 class="card-title">LANCHES</h3>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-6">
            <a href="#" class="link-card">
                <div class="card azul-escuro">
                    <h3 class="card-title">PASTÉIS</h3>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-6">
            <a href="#" class="link-card">
                <div class="card verde">
                    <h3 class="card-title">ESPETINHOS</h3>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-6">
            <a href="#" class="link-card">
                <div class="card roxo">
                    <h3 class="card-title">SOBREMESAS</h3>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-6">
            <a href="#" class="link-card">
                <div class="card vermelho">
                    <h3 class="card-title">BEBIDAS</h3>
                </div>
            </a>
        </div>
    </div>
</div>

<?php require_once("footer.php"); ?>

</body>

</html>