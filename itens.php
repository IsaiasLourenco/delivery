<?php require_once("header.php"); 
$url = $_GET['url'];
$query = $pdo->query("SELECT * FROM categorias WHERE url = '$url'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);  
$total_reg = count($res);
if ($total_reg > 0) {
    $id_categoria = $res[0]['id'];
    $nome_categoria = $res[0]['nome'];
    $descricao_categoria = $res[0]['descricao'];
}
?>
<link rel="stylesheet" href="css/style.css">
<div class="main-container">
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="index" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens"><?php echo mb_strtoupper(@$nome_categoria)?></span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <ol class="list-group mg-t-6">

    <?php
        $query = $pdo->query("SELECT * FROM produtos WHERE categoria = '$id_categoria' AND ativo = 'Sim'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = count($res);
        if ($total_reg > 0) {
            for ($i=0; $i < $total_reg; $i++) {
                foreach ($res[$i] as $key => $value) {}
                $id_produto = $res[$i]['id'];
                $nome_produto = $res[$i]['nome'];
                $descricao_produto = $res[$i]['descricao'];
                $foto_produto = $res[$i]['foto'];
                $url_produto = $res[$i]['url'];
                $estoque_produto = $res[$i]['estoque'];
                $tem_estoque_produto = $res[$i]['tem_estoque'];
                if ($tem_estoque_produto == 'Sim' AND $estoque_produto <= 0) {
                    $mostrar = 'ocultar';
                } else {
                    $mostrar = '';
                }                   
    ?>
        <a href="produto-<?php echo $url?>" class="link-neutro <?php echo $mostrar?>">
        <a href="variacoes.php?url=<?php echo $url_produto ?>" class="link-neutro <?php echo $mostrar?>">
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                    <div class="font-weight-bold">
                        <span><?php echo $nome_produto?></span>
                    </div>
                    <p class="descricao-item"><?php echo $descricao_produto?></p>
                </div>
            </li>
        </a>
        <?php } } ?>
    </ol>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>