<?php require_once("header.php"); 
$url = $_GET['url'] ?? '';
$query = $pdo->query("SELECT * FROM categorias WHERE url = '$url' AND ativo = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if ($total_reg > 0) {
    $id_produto = $res[0]['id'];
    $nome_produto = $res[0]['nome'];
    $foto_produto = $res[0]['foto'];
    $id_categoria = $res[0]['categoria'];
    $valor_produto = $res[0]['valor_venda'];
    $valor_produtoF = "R$ " . number_format($valor_produto, 2, ',', '.');
    $queryCat = $pdo->query("SELECT url FROM categorias WHERE id = '$id_categoria'");
    $resCat = $queryCat->fetch(PDO::FETCH_ASSOC);
    $url_categoria = $resCat['url'];
}
?>

<div class="main-container">
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="variacoes.php" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens">ADICIONAIS</span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <ol class="list-group mg-t-6">
        <a href="adicionais.php" class="link-neutro">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="sem-bold">Borda Catupiry <span class="valor-item">R$ 5,00</span></span>
                <i class="bi bi-square"></i>
            </li>
        </a>
        <a href="adicionais.php" class="link-neutro">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="sem-bold">Borda Cheddar <span class="valor-item">R$ 5,00</span></span>
                <i class="bi bi-square"></i>
            </li>
        </a>
        <a href="adicionais.php" class="link-neutro">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="sem-bold">Bacon <span class="valor-item">R$ 5,00</span></span>
                <i class="bi bi-square"></i>
            </li>
        </a>
        <a href="adicionais.php" class="link-neutro">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="sem-bold">Cebola <span class="valor-item">R$ 3,00</span></span>
                <i class="bi bi-square"></i>
            </li>
        </a>
        <a href="adicionais.php" class="link-neutro">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="sem-bold">Palmito <span class="valor-item">R$ 4,00</span></span>
                <i class="bi bi-square"></i>
            </li>
        </a>
    </ol>

    <div class="remover-ing">
        Remover Ingredientes?

        <ol class="list-group mg-t-1">
            <a href="adicionais.php" class="link-neutro">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="sem-bold">Cebola <span class="valor-menos">R$ 3,00</span></span>
                    <i class="bi bi-check-square"></i>
                </li>
            </a>
            <a href="adicionais.php" class="link-neutro">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="sem-bold">Azeitona <span class="valor-menos">R$ 2,00</span></span>
                    <i class="bi bi-check-square"></i>
                </li>
            </a>
            <a href="adicionais.php" class="link-neutro">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="sem-bold">Tomate <span class="valor-menos">R$ 2,00</span></span>
                    <i class="bi bi-check-square"></i>
                </li>
            </a>
            <a href="adicionais.php" class="link-neutro">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="sem-bold">Palmito <span class="valor-menos">R$ 2,00</span></span>
                    <i class="bi bi-check-square"></i>
                </li>
            </a>
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