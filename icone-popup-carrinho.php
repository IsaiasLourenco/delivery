<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once './sistema/conexao.php';

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

/* ===============================
   QUANTIDADE DO CARRINHO (PILL)
   Conta SOMENTE produtos
================================ */
$stmtQtd = $pdo->prepare("
    SELECT COUNT(*)
    FROM carrinho_temp
    WHERE sessao = :sessao
      AND tipo = 'produto'
");
$stmtQtd->execute([':sessao' => $sessao]);
$qtdCarrinho = (int)$stmtQtd->fetchColumn();

/* ===============================
   ITENS DO POPUP (MODELO NOVO)
================================ */
$stmtItens = $pdo->prepare("
    SELECT 
        ct.tipo,
        ct.quantidade,
        ct.valor_total,
        p.nome  AS nome_produto,
        v.descricao AS nome_variacao,
        a.nome  AS nome_adicional,
        i.nome  AS nome_ingrediente
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

/* ===============================
   TOTAL DO CARRINHO
================================ */
$stmtTotal = $pdo->prepare("
    SELECT COALESCE(SUM(valor_total), 0)
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
                    <span id="popup-qtd"><?= $qtdCarrinho ?></span> item(ns) no carrinho
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
                                switch ($item['tipo']) {
                                    case 'produto':
                                        echo $item['nome_produto'];
                                        break;
                                    case 'variacao':
                                        echo '↳ ' . $item['nome_variacao'];
                                        break;
                                    case 'adicional':
                                        echo '+ ' . $item['nome_adicional'];
                                        break;
                                    case 'ingrediente':
                                        echo '- ' . $item['nome_ingrediente'];
                                        break;
                                }
                                ?>
                            </span>

                            <?php
                            $valorExibir = '';
                            if ($item['tipo'] === 'produto' || $item['tipo'] === 'adicional' || $item['tipo'] === 'ingrediente') {
                                $valorExibir = 'R$ ' . number_format($item['valor_total'], 2, ',', '.');
                            }
                            ?>
                            <span><?= $valorExibir ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="d-flex justify-content-between fw-bold mb-3">
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

                const tituloQtd = document.getElementById('popup-qtd');
                if (tituloQtd) {
                    tituloQtd.textContent = qtd;
                }
            });
    }
</script>