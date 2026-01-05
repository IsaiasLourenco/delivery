<?php
require_once("header.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

/*
|--------------------------------------------------------------------------
| ITENS DO CARRINHO (MODELO NOVO)
|--------------------------------------------------------------------------
*/
$stmtItens = $pdo->prepare("
    SELECT 
        ct.id,
        ct.tipo,
        ct.id_item,
        ct.produto_pai_id,
        ct.quantidade,
        ct.valor_item,
        ct.valor_total,
        p.nome      AS nome_produto,
        v.descricao AS nome_variacao,
        a.nome      AS nome_adicional,
        i.nome      AS nome_ingrediente
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
| TOTAL
|--------------------------------------------------------------------------
*/
$stmtTotal = $pdo->prepare("
    SELECT COALESCE(SUM(valor_total),0)
    FROM carrinho_temp
    WHERE sessao = :sessao
");
$stmtTotal->execute([':sessao' => $sessao]);
$total  = (float) $stmtTotal->fetchColumn();
$totalF = 'R$ ' . number_format($total, 2, ',', '.');
?>

<style>
    .bold {
        font-weight: bold;
    }
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

    <?php if (!empty($itens)): ?>
        <ol class="list-group mg-t-6">
            <?php foreach ($itens as $item): ?>
                <?php
                $idItemCarrinho = isset($item['id']) ? (int)$item['id'] : 0;

                switch ($item['tipo']) {
                    case 'produto':
                        $nomeItem = $item['nome_produto'] ?? 'Produto';
                        break;
                    case 'variacao':
                        $nomeItem = $item['nome_variacao'] ?? 'Variação';
                        break;
                    case 'adicional':
                        $nomeItem = '+ ' . ($item['nome_adicional'] ?? 'Adicional');
                        break;
                    case 'ingrediente':
                        $nomeItem = '- ' . ($item['nome_ingrediente'] ?? 'Ingrediente');
                        break;
                    default:
                        $nomeItem = 'Item';
                }

                $valorExibir = '';
                if (in_array($item['tipo'], ['produto', 'adicional', 'ingrediente'])) {
                    $valorExibir = 'R$ ' . number_format((float)$item['valor_total'], 2, ',', '.');
                }
                ?>
                <li class="list-group-item" id="item-<?= $idItemCarrinho ?>">
                    <div class="d-flex justify-content-between">
                        <strong><?= htmlspecialchars($nomeItem) ?></strong>

                        <?php if ($idItemCarrinho > 0): ?>
                            <i class="bi bi-trash text-danger maozinha"
                               onclick="excluirItem(<?= $idItemCarrinho ?>)"></i>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-end align-items-center mt-1">

                        <?php if ($item['tipo'] === 'produto' && $idItemCarrinho > 0): ?>
                            <i class="bi bi-dash-circle-fill text-danger"
                               onclick="alterarQtd(<?= $idItemCarrinho ?>,-1)"></i>

                            <strong id="qtd-<?= $idItemCarrinho ?>" class="mx-2">
                                <?= (int)$item['quantidade'] ?>
                            </strong>

                            <i class="bi bi-plus-circle-fill text-success"
                               onclick="alterarQtd(<?= $idItemCarrinho ?>,1)"></i>
                        <?php endif; ?>

                        <span class="ms-3"><?= $valorExibir ?></span>
                    </div>
                </li>

            <?php endforeach; ?>
        </ol>
    <?php else: ?>
        <p class="mg-t-6 text-center">Seu carrinho está vazio</p>
    <?php endif; ?>

    <hr>

    <p class="d-flex justify-content-end">
        SubTotal &nbsp <span class="bold" id="subtotal"><?= $totalF ?></span>
    </p>

    <form action="finalizar.php" method="post">
        <button class="btn btn-primary w-100">Finalizar Pedido</button>
    </form>
</div>

<?php require_once("footer.php"); ?>

<script>
    function alterarQtd(id, delta) {
        $.post('atualizar-carrinho.php', {
            id_item: id,
            delta: delta
        }, function (total) {
            $('#subtotal').text('R$ ' + total);
            location.reload();
        });
    }

    function excluirItem(id) {
        if (!confirm('Excluir item?')) return;

        $.post('excluir-carrinho.php', {
            id_item: id
        }, function () {
            location.reload();
        });
    }
</script>