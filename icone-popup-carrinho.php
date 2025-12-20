<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once './sistema/conexao.php';

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

/* Quantidade total (pill) */
$stmtQtd = $pdo->prepare("
    SELECT COALESCE(SUM(quantidade), 0)
    FROM carrinho_temp
    WHERE sessao = :sessao
");
$stmtQtd->execute([':sessao' => $sessao]);
$qtdCarrinho = (int)$stmtQtd->fetchColumn();

/* Itens do carrinho */
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

/* Total */
$stmtTotal = $pdo->prepare("
    SELECT COALESCE(SUM(valor_total),0)
    FROM carrinho_temp
    WHERE sessao = :sessao
");
$stmtTotal->execute([':sessao' => $sessao]);
$total = (float)$stmtTotal->fetchColumn();
?>

<!-- ÍCONE DO CARRINHO -->
<div class="d-flex position-relative">
    <a href="#popup-1" class="text-dark carrinho">
        <i class="bi bi-cart-fill"></i>

        <?php if ($qtdCarrinho > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge bg-danger pilula">
                <?= $qtdCarrinho ?>
            </span>
        <?php endif; ?>
    </a>
</div>

<!-- POPUP -->
<div id="popup-1" class="overlay">
    <div class="popup">

        <div class="row">
            <div class="col-9">
                <h5 class="titulo-popup">
                    <?= $qtdCarrinho ?> item(ns) no carrinho
                </h5>
            </div>
            <div class="col-3 text-end">
                <a class="close" href="#">&times;</a>
            </div>
        </div>

        <hr class="linha">

        <div class="conteudo-popup">

            <?php if (empty($itens)): ?>
                <p class="text-center">Seu carrinho está vazio</p>
            <?php else: ?>

                <ul class="list-group mb-3">
                    <?php foreach ($itens as $item): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>
                                <?php
                                if ($item['tipo'] === 'produto') {
                                    echo $item['nome_produto'];
                                } elseif ($item['tipo'] === 'variacao') {
                                    echo $item['nome_variacao'];
                                } elseif ($item['tipo'] === 'adicional') {
                                    echo $item['nome_adicional'];
                                } elseif ($item['tipo'] === 'ingrediente') {
                                    echo $item['nome_ingrediente'];
                                }
                                ?>
                                <small class="text-muted">
                                    x<?= $item['quantidade'] ?>
                                </small>
                            </span>

                            <span>
                                R$ <?= number_format($item['valor_total'], 2, ',', '.') ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="d-flex justify-content-between bold mb-3">
                    <span>Total</span>
                    <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                </div>

                <a href="carrinho.php" class="btn btn-primary w-100">
                    Ver carrinho
                </a>

            <?php endif; ?>

        </div>
    </div>
</div>

<script>
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && window.location.hash === '#popup-1') {
            window.location.hash = '';
        }
    });
</script>

<script>
    function atualizarPillCarrinho() {
        fetch('qtd-carrinho.php')
            .then(res => res.text())
            .then(qtd => {
                qtd = parseInt(qtd);

                const carrinho = document.querySelector('.carrinho');
                let pill = carrinho.querySelector('.pilula');

                if (qtd > 0) {
                    if (!pill) {
                        pill = document.createElement('span');
                        pill.className =
                            'position-absolute top-0 start-100 translate-middle badge bg-danger pilula';
                        carrinho.appendChild(pill);
                    }
                    pill.textContent = qtd;
                } else if (pill) {
                    pill.remove();
                }
            });
    }
</script>