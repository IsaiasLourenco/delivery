<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("header.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();
$_SESSION['sessao_usuario'] = $sessao;

$url  = $_GET['url']  ?? '';
$item = $_GET['item'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE url = :url AND ativo = 'Sim'");
$stmt->execute([':url' => $url]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die('Produto não encontrado');
}

$id_produto   = (int)$produto['id'];
$nome_produto = $produto['nome'];
$valor_base   = (float)$produto['valor_venda'];

$descricao_var = '';
$valor_var     = 0;
$id_variacao   = null;

if ($item) {
    $stmtVar = $pdo->prepare("
        SELECT * FROM variacoes 
        WHERE produto = :produto AND sigla = :sigla
    ");
    $stmtVar->execute([
        ':produto' => $id_produto,
        ':sigla'   => $item
    ]);
    $var = $stmtVar->fetch(PDO::FETCH_ASSOC);

    if ($var) {
        $id_variacao   = (int)$var['id'];
        $descricao_var = $var['descricao'];
        $valor_var     = (float)$var['valor'];
    }
}

/* PRODUTO BASE (SOMENTE SE NÃO TIVER VARIAÇÃO) */
if (!$id_variacao) {

    $checkProduto = $pdo->prepare("
        SELECT COUNT(*) 
        FROM carrinho_temp 
        WHERE sessao = :sessao AND tipo = 'produto'
    ");
    $checkProduto->execute([':sessao' => $sessao]);

    if ($checkProduto->fetchColumn() == 0) {
        $insertProduto = $pdo->prepare("
            INSERT INTO carrinho_temp
            (sessao, produto_id, tipo, id_item, quantidade, valor_item, valor_total)
            VALUES
            (:sessao, :produto, 'produto', :produto, 1, :valor, :valor)
        ");
        $insertProduto->execute([
            ':sessao'  => $sessao,
            ':produto' => $id_produto,
            ':valor'   => $valor_base
        ]);
    }
}

/* VARIAÇÃO */
if ($id_variacao && $valor_var > 0) {

    $checkVar = $pdo->prepare("
        SELECT COUNT(*) 
        FROM carrinho_temp 
        WHERE sessao = :sessao AND tipo = 'variacao'
    ");
    $checkVar->execute([':sessao' => $sessao]);

    if ($checkVar->fetchColumn() == 0) {
        $insertVar = $pdo->prepare("
            INSERT INTO carrinho_temp
            (sessao, produto_id, tipo, id_item, quantidade, valor_item, valor_total)
            VALUES
            (:sessao, :produto, 'variacao', :variacao, 1, :valor, :valor)
        ");
        $insertVar->execute([
            ':sessao'   => $sessao,
            ':produto'  => $id_produto,
            ':variacao' => $id_variacao,
            ':valor'    => $valor_var
        ]);
    }
}

/* TOTAL */
$stmtTotal = $pdo->prepare("
    SELECT COALESCE(SUM(valor_total),0) 
    FROM carrinho_temp 
    WHERE sessao = :sessao
");
$stmtTotal->execute([':sessao' => $sessao]);
$total  = (float)$stmtTotal->fetchColumn();
$totalF = "R$ " . number_format($total, 2, ',', '.');
?>

<div class="main-container">
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="variacoes.php?url=<?php echo $url ?>" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens">
                    <?php echo mb_strtoupper($nome_produto); ?>
                    <?php if ($descricao_var) echo " - $descricao_var"; ?>
                </span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <!-- ADICIONAIS -->
    <?php
    $ads = $pdo->prepare("
        SELECT * FROM adicionais 
        WHERE produto = :produto AND ativo = 'Sim'
    ");
    $ads->execute([':produto' => $id_produto]);
    $listaAds = $ads->fetchAll(PDO::FETCH_ASSOC);

    if ($listaAds) {
    ?>
        <div class="list-group mg-t-6">
            <strong>Adicionais?</strong>

            <?php foreach ($listaAds as $ad) { ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <?php echo $ad['nome']; ?>
                        <span class="valor-item">R$ <?php echo number_format($ad['valor'], 2, ',', '.'); ?></span>
                    </span>

                    <input type="checkbox"
                        class="adicional"
                        data-id="<?php echo $ad['id']; ?>"
                        data-valor="<?php echo $ad['valor']; ?>"
                        onchange="adicionar(this,'adicional')">
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <!-- INGREDIENTES -->
    <?php
    $ings = $pdo->prepare("
        SELECT * FROM ingredientes 
        WHERE produto = :produto AND ativo = 'Sim'
    ");
    $ings->execute([':produto' => $id_produto]);
    $listaIng = $ings->fetchAll(PDO::FETCH_ASSOC);

    if ($listaIng) {
    ?>
        <div class="list-group mg-t-3">
            <strong>Ingredientes</strong>

            <?php foreach ($listaIng as $ing) { ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <?php echo $ing['nome']; ?>
                        <span class="valor-menos">- R$ <?php echo number_format($ing['valor'], 2, ',', '.'); ?></span>
                    </span>

                    <input type="checkbox"
                        class="ingrediente"
                        data-id="<?php echo $ing['id']; ?>"
                        data-valor="<?php echo $ing['valor']; ?>"
                        checked
                        onchange="adicionar(this,'ingrediente')">
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="total">
        <p>Total <strong id="total"><?php echo $totalF; ?></strong></p>
    </div>

    <div class="mg-t-2">
        <a href="observacoes.php" class="btn btn-primary w-100">
            Avançar →
        </a>
    </div>
</div>

<?php require_once("footer.php"); ?>

<script>
    const PRODUTO_ID = <?php echo $id_produto; ?>;

    function adicionar(el, tipo) {

        let marcado;

        if (tipo === 'adicional') {
            // adicional: checked = adicionar
            marcado = el.checked ? 'true' : 'false';
        }

        if (tipo === 'ingrediente') {
            // ingrediente: checked = manter, unchecked = remover
            marcado = el.checked ? 'false' : 'true';
        }

        $.post('inserir-carrinho.php', {
            produto_id: PRODUTO_ID,
            id_item: el.dataset.id,
            tipo: tipo,
            marcado: marcado
        }, function(total) {
            $('#total').text('R$ ' + total);
        });

    }
</script>