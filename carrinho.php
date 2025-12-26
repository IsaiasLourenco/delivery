<?php require_once("header.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

// BUSCA ITENS DO CARRINHO TEMPORÁRIO
$stmtItens = $pdo->prepare("
        SELECT ct.*, 
               p.nome AS nome_produto, 
               v.descricao AS nome_variacao, 
               a.nome AS nome_adicional, 
               i.nome AS nome_ingrediente
        FROM carrinho_temp ct
        LEFT JOIN produtos p ON ct.produto_id = p.id
        LEFT JOIN variacoes v ON ct.id_item = v.id AND ct.tipo = 'variacao'
        LEFT JOIN adicionais a ON ct.id_item = a.id AND ct.tipo = 'adicional'
        LEFT JOIN ingredientes i ON ct.id_item = i.id AND ct.tipo = 'ingrediente'
        WHERE ct.sessao = :sessao
        ORDER BY ct.id ASC
    ");
$stmtItens->execute([':sessao' => $sessao]);
$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

// TOTAL GERAL
$stmtTotal = $pdo->prepare("SELECT COALESCE(SUM(valor_total), 0)
                                FROM carrinho_temp
                                WHERE sessao = :sessao");
$stmtTotal->execute([':sessao' => $sessao]);
$totalBase = (float)$stmtTotal->fetchColumn();
$totalBaseF = 'R$ ' . number_format($totalBase, 2, ',', '.');
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

    <?php if (!empty($itens)) { ?>
        <ol class="list-group mg-t-6">
            <?php foreach ($itens as $item) { ?>
                <li class="list-group-item" id="item-<?php echo $item['id']; ?>">
                    <!-- Linha do nome do produto e ícone de lixo (na mesma linha) -->
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="sem-bold" style="flex-grow: 1;">
                            <strong>
                                <?php
                                if ($item['tipo'] == 'produto') {
                                    echo $item['nome_produto'];
                                } elseif ($item['tipo'] == 'variacao') {
                                    echo $item['nome_variacao'];
                                } elseif ($item['tipo'] == 'adicional') {
                                    echo $item['nome_adicional'];
                                } elseif ($item['tipo'] == 'ingrediente') {
                                    echo $item['nome_ingrediente'];
                                }
                                ?>
                            </strong>
                        </span>

                        <!-- Ícone de exclusão à direita -->
                        <i class="bi bi-trash text-danger maozinha" title="Excluir produto" onclick="excluirItem(<?php echo $item['id']; ?>)"></i>
                    </div>

                    <!-- Linha de quantidade e preço (na linha abaixo) -->
                    <div class="area-qtd d-flex justify-content-end align-items-center">
                        <?php if ($item['tipo'] != 'ingrediente') { ?>
                            <!-- Botões de aumentar e diminuir para produtos, variações e adicionais -->
                            <i class="bi bi-dash-circle-fill text-danger" title="Diminuir quantidade" onclick="alterarQtd(<?php echo $item['id']; ?>, -1)"></i>
                            <strong id="qtd-<?php echo $item['id']; ?>"><?php echo $item['quantidade']; ?></strong>
                            <i class="bi bi-plus-circle-fill text-success" title="Aumentar quantidade" onclick="alterarQtd(<?php echo $item['id']; ?>, 1)"></i>
                        <?php } else { ?>
                            <!-- Para ingredientes, só mostra a quantidade sem botões de controle -->
                            <strong id="qtd-<?php echo $item['id']; ?>" class="qtd-ingrediente"><?php echo $item['quantidade']; ?></strong>
                        <?php } ?>

                        <!-- Preço à esquerda -->
                        <span class="valor-total" id="valor-<?php echo $item['id']; ?>" data-preco="<?php echo $item['valor_item']; ?>">
                            R$ <?php echo number_format($item['valor_total'], 2, ',', '.'); ?>
                        </span>
                    </div>
                </li>
            <?php } ?>
        </ol>
    <?php } else { ?>
        <p class="mg-t-6 text-center">Seu carrinho está vazio!!</p>
    <?php } ?>


    <hr class="mg-t-3">
    <div class="mg-t-2">
        <div class="total">
            <p class="area-qtd" id="subtotal">SubTotal <span class="bold"><?php echo $totalBaseF; ?></span></p>
        </div>
    </div>

    <div class="mg-t-8">
        <a href="finalizar.php" class="btn btn-primary w-100">Finalizar Pedido</a>
    </div>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>

<script>
    function alterarQtd(id, delta) {
        console.log(id, delta);
        $.post('atualizar-carrinho.php', {
            id_item: id,
            delta: delta
        }, function(total) {
            console.log(total);
            // Atualiza a quantidade no front-end
            const qtdEl = document.getElementById('qtd-' + id);
            let novaQtd = Math.max(1, parseInt(qtdEl.textContent) + delta);
            qtdEl.textContent = novaQtd;

            // Atualiza o valor do item
            const valorTotalEl = document.getElementById('valor-' + id);

            if (valorTotalEl) {
                const preco = parseFloat(valorTotalEl.dataset.preco);

                // usa novaQtd, já validada com Math.max(1, …)
                const novoValor = preco * novaQtd;

                valorTotalEl.innerHTML =
                    'R$ ' + novoValor.toFixed(2).replace('.', ',');

                atualizarPillCarrinho();
                atualizarPopupCarrinho();
            }


            // Atualiza o total geral
            const subtotalEl = document.getElementById('subtotal');
            if (subtotalEl) {
                let novoSubtotal = parseFloat(total.replace(',', '.'));
                if (!isNaN(novoSubtotal)) {
                    const spanSubtotal = subtotalEl.querySelector('.bold');
                    if (spanSubtotal) {
                        spanSubtotal.innerHTML = 'R$ ' + novoSubtotal.toFixed(2).replace('.', ',');
                    }
                } else {
                    console.error('Erro: Total Inválido!', total);
                }
            }
        });
    }

    function excluirItem(id) {
        if (confirm('Deseja realmente excluir este item?')) {
            $.post('excluir-carrinho.php', {
                id_item: id
            }, function() {

                // remove item da lista
                const li = document.getElementById('item-' + id);
                if (li) li.remove();

                atualizarPillCarrinho();
                atualizarPopupCarrinho();
            });
        }
    }

    function atualizarPopupCarrinho() {
        fetch('popup-carrinho.php')
            .then(res => res.text())
            .then(html => {
                const container = document.querySelector('.conteudo-popup');
                if (container) container.innerHTML = html;
            });
    }
</script>