<?php
require_once("header.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();
$_SESSION['sessao_usuario'] = $sessao;

// Verifica se o carrinho temporário está vazio
$stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM carrinho_temp WHERE sessao = :sessao");
$stmtCheck->execute([':sessao' => $sessao]);

if ($stmtCheck->fetchColumn() == 0) {
    // Aqui geramos JS para limpar o sessionStorage no navegador
    echo "<script>
        sessionStorage.removeItem('estado_itens');
        sessionStorage.removeItem('produto_atual');
    </script>";
}

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
$sigla_var     = '';
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
        $sigla_var = $var['sigla'];
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
            (:sessao, :produto,'variacao', :variacao, 1, :valor, :valor)
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
<style>
    /* por padrão, mostra desktop e esconde mobile */
    .desktop-only {
        display: inline;
    }

    .mobile-only {
        display: none;
    }

    /* quando a tela for pequena (mobile), inverte */
    @media (max-width: 768px) {
        .desktop-only {
            display: none;
        }

        .mobile-only {
            display: inline;
        }
    }
</style>
<div class="main-container">
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="variacoes.php?url=<?php echo $url ?>" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens">
                    <?php echo mb_strtoupper($nome_produto); ?>

                    <!-- versão desktop -->
                    <?php if ($descricao_var) { ?>
                        <span class="desktop-only"> - <?php echo $descricao_var; ?></span>
                    <?php } ?>

                    <!-- versão mobile -->
                    <?php if ($sigla_var) { ?>
                        <span class="mobile-only"> - <?php echo $sigla_var; ?></span>
                    <?php } ?>
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
    // 1️⃣ Limpa estado se mudar de produto
    const PRODUTO_ATUAL = "<?php echo $id_produto; ?>";
    const PRODUTO_ANTERIOR = sessionStorage.getItem('produto_atual');

    if (PRODUTO_ATUAL !== PRODUTO_ANTERIOR) {
        sessionStorage.removeItem('estado_itens');
        sessionStorage.setItem('produto_atual', PRODUTO_ATUAL);
    }

    // 2️⃣ Guarda a página de origem para voltar
    sessionStorage.setItem('back_url', window.location.href);

    // 3️⃣ Função para salvar estado dos checkboxes
    function salvarEstado() {
        const estado = {};
        document.querySelectorAll('.adicional, .ingrediente').forEach(el => {
            estado[el.dataset.id] = el.checked;
        });
        sessionStorage.setItem('estado_itens', JSON.stringify(estado));
    }

    // 4️⃣ Aplica estado salvo ao carregar a página
    window.addEventListener('DOMContentLoaded', () => {
        const estado = JSON.parse(sessionStorage.getItem('estado_itens') || '{}');
        document.querySelectorAll('.adicional, .ingrediente').forEach(el => {
            if (estado.hasOwnProperty(el.dataset.id)) {
                el.checked = estado[el.dataset.id];
            }
        });

        // Adiciona listener para atualizar estado sempre que o usuário marcar/desmarcar
        document.querySelectorAll('.adicional, .ingrediente').forEach(el => {
            el.addEventListener('change', salvarEstado);
        });
    });

    // 5️⃣ Função que envia alterações ao carrinho
    const PRODUTO_ID = <?php echo $id_produto; ?>;

    function adicionar(el, tipo) {
        let marcado;

        if (tipo === 'adicional') {
            marcado = el.checked ? 'true' : 'false';
        }

        if (tipo === 'ingrediente') {
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