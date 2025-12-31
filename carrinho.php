<?php
require_once("header.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

/*
|--------------------------------------------------------------------------
| BUSCA ITENS DO CARRINHO TEMPORÁRIO (ESTRUTURA NOVA)
|--------------------------------------------------------------------------
*/
$stmtItens = $pdo->prepare("
    SELECT 
        ct.*,
        p.nome        AS nome_produto,
        v.descricao   AS nome_variacao,
        a.nome        AS nome_adicional,
        i.nome        AS nome_ingrediente
    FROM carrinho_temp ct
    LEFT JOIN produtos p 
        ON p.id = ct.id_item AND ct.tipo = 'produto'
    LEFT JOIN variacoes v 
        ON v.id = ct.id_item AND ct.tipo = 'variacao'
    LEFT JOIN adicionais a 
        ON a.id = ct.id_item AND ct.tipo = 'adicional'
    LEFT JOIN ingredientes i 
        ON i.id = ct.id_item AND ct.tipo = 'ingrediente'
    WHERE ct.sessao = :sessao
    ORDER BY ct.id ASC
");
$stmtItens->execute([':sessao' => $sessao]);
$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| TOTAL GERAL
|--------------------------------------------------------------------------
*/
$stmtTotal = $pdo->prepare("
    SELECT COALESCE(SUM(valor_total), 0)
    FROM carrinho_temp
    WHERE sessao = :sessao
");
$stmtTotal->execute([':sessao' => $sessao]);
$totalBase = (float) $stmtTotal->fetchColumn();
$totalBaseF = 'R$ ' . number_format($totalBase, 2, ',', '.');
?>
<style>
.bold { font-weight: bold; }
</style>

<div class="main-container">
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="#" onclick="history.back()" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens">RESUMO DO PEDIDO</span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <?php if (!empty($itens)) { ?>
        <ol class="list-group mg-t-6">
            <?php foreach ($itens as $item) { ?>
                <li class="list-group-item" id="item-<?php echo $item['id']; ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="flex-grow:1;">
                            <strong>
                                <?php
                                switch ($item['tipo']) {
                                    case 'produto':
                                        echo $item['nome_produto'];
                                        break;
                                    case 'variacao':
                                        echo $item['nome_variacao'];
                                        break;
                                    case 'adicional':
                                        echo $item['nome_adicional'];
                                        break;
                                    case 'ingrediente':
                                        echo $item['nome_ingrediente'];
                                        break;
                                }
                                ?>
                            </strong>
                        </span>

                        <i class="bi bi-trash text-danger maozinha"
                           onclick="excluirItem(<?php echo $item['id']; ?>)"></i>
                    </div>

                    <div class="area-qtd d-flex justify-content-end align-items-center">
                        <?php if ($item['tipo'] !== 'ingrediente') { ?>
                            <i class="bi bi-dash-circle-fill text-danger"
                               onclick="alterarQtd(<?php echo $item['id']; ?>, -1)"></i>

                            <strong id="qtd-<?php echo $item['id']; ?>">
                                <?php echo $item['quantidade']; ?>
                            </strong>

                            <i class="bi bi-plus-circle-fill text-success"
                               onclick="alterarQtd(<?php echo $item['id']; ?>, 1)"></i>
                        <?php } else { ?>
                            <strong class="qtd-ingrediente">
                                <?php echo $item['quantidade']; ?>
                            </strong>
                        <?php } ?>

                        <span class="valor-total"
                              id="valor-<?php echo $item['id']; ?>"
                              data-preco="<?php echo $item['valor_item']; ?>">
                            R$ <?php echo number_format($item['valor_total'], 2, ',', '.'); ?>
                        </span>
                    </div>
                </li>
            <?php } ?>
        </ol>
    <?php } else { ?>
        <p class="mg-t-6 text-center">Seu carrinho está vazio!</p>
    <?php } ?>

    <hr class="mg-t-3">

    <div class="mg-t-2">
        <p class="area-qtd" id="subtotal">
            SubTotal <span class="bold"><?php echo $totalBaseF; ?></span>
        </p>
    </div>

    <div class="mg-t-8">
        <form action="finalizar.php" method="post">
            <button type="submit" class="btn btn-primary w-100">
                Finalizar Pedido
            </button>
        </form>
    </div>
</div>

<?php require_once("footer.php"); ?>

<script>
function alterarQtd(id, delta) {
    $.post('atualizar-carrinho.php', { id_item: id, delta: delta }, function(total) {
        const qtdEl = document.getElementById('qtd-' + id);
        let novaQtd = Math.max(1, parseInt(qtdEl.textContent) + delta);
        qtdEl.textContent = novaQtd;

        const valorEl = document.getElementById('valor-' + id);
        const preco = parseFloat(valorEl.dataset.preco);
        valorEl.innerHTML = 'R$ ' + (preco * novaQtd).toFixed(2).replace('.', ',');

        const spanSubtotal = document.querySelector('#subtotal .bold');
        spanSubtotal.innerHTML = 'R$ ' + parseFloat(total).toFixed(2).replace('.', ',');
    });
}

function excluirItem(id) {
    if (!confirm('Deseja realmente excluir este item?')) return;

    $.post('excluir-carrinho.php', { id_item: id }, function() {
        const li = document.getElementById('item-' + id);
        if (li) li.remove();
    });
}
</script>
