<?php
require_once("header.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();
$_SESSION['sessao_usuario'] = $sessao;

/* LIMPA STORAGE SE CARRINHO VAZIO */
$stmtCheck = $pdo->prepare("
    SELECT COUNT(*) 
    FROM carrinho_temp 
    WHERE sessao = :sessao
");
$stmtCheck->execute([':sessao' => $sessao]);

if ($stmtCheck->fetchColumn() == 0) {
    echo "<script>
        sessionStorage.removeItem('estado_itens');
        sessionStorage.removeItem('produto_atual');
    </script>";
}

/* PRODUTO */
$url  = $_GET['url']  ?? '';
$item = $_GET['item'] ?? '';

$stmt = $pdo->prepare("
    SELECT * 
    FROM produtos 
    WHERE url = :url AND ativo = 'Sim'
");
$stmt->execute([':url' => $url]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die('Produto não encontrado');
}

$id_produto   = (int)$produto['id'];
$nome_produto = $produto['nome'];
$valor_base   = (float)$produto['valor_venda'];

/* VERIFICA SE TEM VARIAÇÃO */
$stmtTemVar = $pdo->prepare("
    SELECT COUNT(*) 
    FROM variacoes 
    WHERE produto = :produto AND ativo = 'Sim'
");
$stmtTemVar->execute([':produto' => $id_produto]);
$tem_variacao = $stmtTemVar->fetchColumn() > 0;

/* VARIAÇÃO */
$descricao_var = '';
$sigla_var     = '';
$valor_var     = 0;
$id_variacao   = null;

if ($item) {
    $stmtVar = $pdo->prepare("
        SELECT * 
        FROM variacoes 
        WHERE produto = :produto 
          AND sigla = :sigla
    ");
    $stmtVar->execute([
        ':produto' => $id_produto,
        ':sigla'   => $item
    ]);
    $var = $stmtVar->fetch(PDO::FETCH_ASSOC);

    if ($var) {
        $id_variacao   = (int)$var['id'];
        $descricao_var = $var['descricao'];
        $sigla_var     = $var['sigla'];
        $valor_var     = (float)$var['valor'];
    }
}

/* GARANTE PRODUTO PAI PARA ESTE PRODUTO/SESSÃO */
$stmtProdutoCarrinho = $pdo->prepare("
    SELECT id 
    FROM carrinho_temp
    WHERE sessao = :sessao
      AND tipo = 'produto'
      AND id_item = :produto
    ORDER BY id DESC
    LIMIT 1
");
$stmtProdutoCarrinho->execute([
    ':sessao'  => $sessao,
    ':produto' => $id_produto
]);
$produto_pai_id = (int)$stmtProdutoCarrinho->fetchColumn();

/* SE NÃO EXISTIR AINDA, CRIA O PRODUTO PAI */
if ($produto_pai_id === 0) {

    // valor base do pai: se tiver variação selecionada, usa o valor da variação
    $valor_pai = $tem_variacao && $id_variacao && $valor_var > 0 
        ? $valor_var 
        : $valor_base;

    $insertProduto = $pdo->prepare("
        INSERT INTO carrinho_temp
        (sessao, tipo, id_item, produto_pai_id, quantidade, valor_item, valor_total)
        VALUES
        (:sessao, 'produto', :produto, NULL, 1, :valor_item, :valor_total)
    ");

    $insertProduto->execute([
        ':sessao'      => $sessao,
        ':produto'     => $id_produto,
        ':valor_item'  => $valor_pai,
        ':valor_total' => $valor_pai
    ]);

    $produto_pai_id = (int)$pdo->lastInsertId();
}

/* SE TIVER VARIAÇÃO, GARANTE O REGISTRO DA VARIAÇÃO COMO FILHO (SEM VALOR) */
if ($id_variacao) {

    $checkVar = $pdo->prepare("
        SELECT COUNT(*) 
        FROM carrinho_temp
        WHERE sessao = :sessao
          AND tipo = 'variacao'
          AND produto_pai_id = :pai
    ");
    $checkVar->execute([
        ':sessao' => $sessao,
        ':pai'    => $produto_pai_id
    ]);

    if ($checkVar->fetchColumn() == 0) {
        $insertVar = $pdo->prepare("
            INSERT INTO carrinho_temp 
            (sessao, tipo, id_item, produto_pai_id, quantidade, valor_item, valor_total)
            VALUES 
            (:sessao, 'variacao', :variacao, :pai, 1, 0, 0)
        ");

        $insertVar->execute([
            ':sessao'   => $sessao,
            ':variacao' => $id_variacao,
            ':pai'      => $produto_pai_id
        ]);
    }
}

/* TOTAL APENAS DO PRODUTO PAI + FILHOS */
$stmtTotal = $pdo->prepare("SELECT COALESCE(SUM(valor_total),0)
                            FROM carrinho_temp
                            WHERE sessao = :sessao
                            AND (id = :pai OR produto_pai_id = :pai)");
$stmtTotal->execute([
    ':sessao' => $sessao,
    ':pai'    => $produto_pai_id
]);
$total  = (float)$stmtTotal->fetchColumn();
$totalF = 'R$ ' . number_format($total, 2, ',', '.');
?>

<style>
    .desktop-only {
        display: inline;
    }

    .mobile-only {
        display: none;
    }

    @media (max-width: 768px) {
        .desktop-only {
            display: none;
        }

        .mobile-only {
            display: inline;
        }
    }
</style>

<link rel="stylesheet" href="./sistema/painel/css/style.css">

<div class="main-container sair-do-header">
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="variacoes.php?url=<?php echo $url ?>" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens">
                    <?php echo mb_strtoupper($nome_produto); ?>
                    <?php if ($descricao_var) { ?>
                        <span class="desktop-only"> - <?php echo $descricao_var; ?></span>
                    <?php } ?>
                    <?php if ($sigla_var) { ?>
                        <span class="mobile-only"> - <?php echo $sigla_var; ?></span>
                    <?php } ?>
                </span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <p class="subTitle">Adicionais</p>
    <?php
    $ads = $pdo->prepare("
        SELECT * 
        FROM adicionais 
        WHERE produto = :produto 
          AND ativo = 'Sim'
    ");
    $ads->execute([':produto' => $id_produto]);
    foreach ($ads->fetchAll(PDO::FETCH_ASSOC) as $ad) {
    ?>
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <span>
                <?php echo $ad['nome']; ?>
                <span class="valor-item">R$ <?php echo number_format($ad['valor'], 2, ',', '.'); ?></span>
            </span>
            <input type="checkbox"
                   class="adicional"
                   data-id="<?php echo $ad['id']; ?>"
                   onchange="adicionar(this,'adicional')">
        </div>
    <?php } ?>

    <p class="subTitle">Ingredientes</p>
    <?php
    $ings = $pdo->prepare("
        SELECT * 
        FROM ingredientes 
        WHERE produto = :produto 
          AND ativo = 'Sim'
    ");
    $ings->execute([':produto' => $id_produto]);
    foreach ($ings->fetchAll(PDO::FETCH_ASSOC) as $ing) {
    ?>
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <span>
                <?php echo $ing['nome']; ?>
                <span class="valor-menos">- R$ <?php echo number_format($ing['valor'], 2, ',', '.'); ?></span>
            </span>
            <input type="checkbox"
                   class="ingrediente"
                   data-id="<?php echo $ing['id']; ?>"
                   checked
                   onchange="adicionar(this,'ingrediente')">
        </div>
    <?php } ?>

    <div class="total">
        <p>Total <strong id="total"><?php echo $totalF; ?></strong></p>
    </div>

    <a href="observacoes.php?pai=<?= $produto_pai_id ?>" class="btn btn-primary w-100">Avançar →</a>
</div>

<?php require_once("footer.php"); ?>

<script>
    const PRODUTO_PAI_ID = <?php echo (int)$produto_pai_id; ?>;

    function adicionar(el, tipo) {
        let marcado = 'false';

        if (tipo === 'adicional') {
            marcado = el.checked ? 'true' : 'false';
        }

        if (tipo === 'ingrediente') {
            marcado = el.checked ? 'false' : 'true';
        }

        $.post('inserir-carrinho.php', {
            produto_pai_id: PRODUTO_PAI_ID,
            id_item: el.dataset.id,
            tipo: tipo,
            marcado: marcado
        }, function(total) {
            $('#total').text('R$ ' + total);
        });
    }
</script>