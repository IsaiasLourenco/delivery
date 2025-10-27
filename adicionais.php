<?php require_once("header.php");
$url = $_GET['url'] ?? '';
$total_item = $_GET['total'] ?? 0;
$query = $pdo->query("SELECT * FROM produtos WHERE url = '$url' AND ativo = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if ($total_reg > 0) {
    $id_produto = $res[0]['id'];
    $nome_produto = $res[0]['nome'];
    $foto_produto = $res[0]['foto'];
    $id_categoria = $res[0]['categoria'];
    $valor_produto = $res[0]['valor_venda'];
    $valor_produtoF = "R$ " . number_format($valor_produto, 2, ',', '.');
}
?>

<div class="main-container">
    <?php
    $sigla_var = $_GET['sigla_var'] ?? '';
    $queryVar = $pdo->query("SELECT * FROM variacoes WHERE produto = '$id_produto' AND sigla = '$sigla_var'");
    $resVar = $queryVar->fetch(PDO::FETCH_ASSOC);

    $descricao_var = $resVar['descricao'] ?? '';
    ?>
    <!-- Imagem e texto -->
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="variacoes.php?url=<?php echo $url ?>" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens"><?php echo mb_strtoupper($nome_produto) ?> - <?php echo $sigla_var ?></span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <?php
    $queryAd = $pdo->query("SELECT * FROM adicionais WHERE produto = '$id_produto' AND ativo = 'Sim'");
    $resAd = $queryAd->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_ad = count($resAd);
    if ($total_reg_ad > 0) { ?>
        <div class="remover-ing">
            <ol class="list-group mg-t-6">
                Adicionais?
                <?php
                for ($i = 0; $i < $total_reg_ad; $i++) {
                    $id_ad = $resAd[$i]['id'];
                    $nome_ad = $resAd[$i]['nome'];
                    $valor_ad = $resAd[$i]['valor'];
                    $valor_adF = "R$ " . number_format($valor_ad, 2, ',', '.');
                ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="sem-bold">
                            <?php echo $nome_ad ?> <span class="valor-item"><?php echo $valor_adF ?></span>
                        </span>
                        <input type="checkbox"
                            class="adicional"
                            data-valor="<?php echo $valor_ad ?>"
                            value="<?php echo $valor_ad ?>"
                            data-categoria="<?php echo $resAd[$i]['categoria']; ?>"
                            onchange="atualizarTotal()"
                            style="display:none;">
                        <i class="bi bi-square" onclick="toggleCheckbox(this)"></i>
                    </li>
                <?php } ?>
            </ol>
        </div>
    <?php } ?>

    <?php

    $queryIng = $pdo->query("SELECT * FROM ingredientes WHERE produto = '$id_produto' AND ativo = 'Sim'");
    $resIng = $queryIng->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_ing = count($resIng);
    if ($total_reg_ing > 0) { ?>
        <div class="remover-ing">
            Remover Ingredientes?
            <ol class="list-group mg-t-1">
                <?php
                for ($i = 0; $i < $total_reg_ing; $i++) {
                    $id_ing = $resIng[$i]['id'];
                    $nome_ing = $resIng[$i]['nome'];
                    $valor_ing = $resIng[$i]['valor'];
                    $valor_ingF = "R$ " . number_format($valor_ing, 2, ',', '.');
                ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="sem-bold">
                            <?php echo $nome_ing ?> <span class="valor-menos"><?php echo $valor_ingF ?></span>
                        </span>
                        <input type="checkbox"
                            class="ingrediente"
                            data-valor="<?php echo $valor_ing ?>"
                            value="<?php echo $valor_ing ?>"
                            onchange="atualizarTotal()"
                            style="display:none;"
                            checked>
                        <i class="bi bi-check-square" onclick="toggleCheckbox(this)"></i>
                    </li>
                <?php } ?>
            </ol>
        </div>

    <?php } ?>

    <div class="total">
        <?php
        $total_itemF = "R$ " . number_format($total_item, 2, ',', '.');

        ?>
        <p>Total <strong><?php echo $total_itemF ?></strong></p>
    </div>

    <div class="mg-t-2">
        <a href="observacoes.php?total=<?php echo $total_item ?>" class="btn btn-primary w-100" id="btn-avancar">Avançar →</a>
    </div>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>

<script>
    function toggleCheckbox(icon) {
        const li = icon.closest('li');
        const checkbox = li.querySelector('input[type="checkbox"]');
        const categoria = checkbox.dataset.categoria;

        const isAdicional = checkbox.classList.contains('adicional');
        const isIngrediente = checkbox.classList.contains('ingrediente');

        // Alternar o estado do checkbox
        checkbox.checked = !checkbox.checked;

        // Atualizar o ícone visual
        icon.className = checkbox.checked ? 'bi bi-check-square' : 'bi bi-square';

        // Se for adicional, aplicar exclusividade visual por categoria
        if (isAdicional) {
            if (checkbox.checked) {
                document.querySelectorAll(`.adicional[data-categoria="${categoria}"]`).forEach(el => {
                    if (el !== checkbox) {
                        el.checked = false;
                        el.closest('li').querySelector('i').className = 'bi bi-square';
                        el.closest('li').classList.add('text-muted');
                    }
                });
                li.classList.remove('text-muted');
            } else {
                li.classList.remove('text-muted');
            }
        }

        atualizarTotal();
    }

    function inicializarCheckboxes() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            const icon = checkbox.closest('li').querySelector('i');
            if (icon) {
                icon.className = checkbox.checked ? 'bi bi-check-square' : 'bi bi-square';
            }

            // Se for adicional e estiver marcado, aplicar exclusividade visual
            if (checkbox.classList.contains('adicional') && checkbox.checked) {
                const categoria = checkbox.dataset.categoria;
                document.querySelectorAll(`.adicional[data-categoria="${categoria}"]`).forEach(el => {
                    if (el !== checkbox) {
                        el.closest('li').classList.add('text-muted');
                    }
                });
            }
        });

        atualizarTotal();
    }

    const valorBaseProduto = <?php echo $total_item ?>;

    function atualizarTotal() {
        let total = valorBaseProduto || 0;

        // Soma os adicionais marcados
        document.querySelectorAll('.adicional:checked').forEach((checkbox) => {
            total += parseFloat(checkbox.dataset.valor) || 0;
        });

        // Subtrai os ingredientes desmarcados
        document.querySelectorAll('.ingrediente:not(:checked)').forEach((checkbox) => {
            total -= parseFloat(checkbox.dataset.valor) || 0;
        });

        const totalElement = document.querySelector('.total strong');
        if (totalElement) {
            totalElement.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
        }
        const btnAvancar = document.getElementById('btn-avancar');
        if (btnAvancar) {
            const urlParams = new URLSearchParams(window.location.search);
            const url = urlParams.get('url') || '';
            const sigla_var = urlParams.get('sigla_var') || '';
            btnAvancar.href = `observacoes.php?total=${total.toFixed(2)}`;
        }
    }
</script>

window.addEventListener('load', inicializarCheckboxes);