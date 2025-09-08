<?php require_once("header.php"); ?>

<div class="main-container">
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="index.php" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens">PIZZAS</span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <ol class="list-group mg-t-6">
        <a href="variacoes.php" class="link-neutro">
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                    <div class="font-weight-bold">
                        <span>Pizza Calabresa</span>
                    </div>
                    <p class="descricao-item">Com generosa cobertura de linguiça calabresa, queijo mussarela, azeitonas pretas e cebola, traz aquela 
                        combinação tradicional que todo mundo adora. Prática para o dia a dia, fica pronta em poucos minutos no forno.</p>
                </div>
            </li>
        </a>
        <a href="variacoes.php" class="link-neutro">
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                    <div class="font-weight-bold">
                        <span>Pizza Portuguesa</span>
                    </div>
                    <p class="descricao-item">A Pizza Portuguesa é um sabor de pizza brasileiro, não português, popular no Brasil e caracterizado pela base 
                        de molho de tomate, queijo mussarela, presunto, ovos cozidos, cebola e azeitonas, com a possibilidade de adicionar outros 
                        ingredientes como ervilha, palmito, pimentão e milho. A sua origem está ligada à influência da cultura portuguesa na culinária brasileira, 
                        sendo uma criação das pizzarias locais para homenagear a comunidade lusa. </p>
                </div>
            </li>
        </a>
        <a href="variacoes.php" class="link-neutro">
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                    <div class="font-weight-bold">
                        <span>Pizza Bacon</span>
                    </div>
                    <p class="descricao-item">É uma pizza com massa de longa levitação, aerada por dentro e crocante por fora, com recheio de bacon e 
                        cebola. A combinação de seus ingredientes resulta em um sabor intenso e equilibrado, trazendo um toque salgado e um leve 
                        adocicado.</p>
                </div>
            </li>
        </a>
    </ol>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>