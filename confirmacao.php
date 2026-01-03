<?php
require_once './sistema/conexao.php';
require_once 'header.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$clienteId = $_SESSION['cliente_id'] ?? null;

if (!$clienteId) {
    header('Location: login.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| VENDA ABERTA
|--------------------------------------------------------------------------
*/
$stmtVenda = $pdo->prepare("
    SELECT *
    FROM vendas
    WHERE cliente = :cliente
      AND status_venda = 'aberta'
    ORDER BY id DESC
    LIMIT 1
");
$stmtVenda->execute([':cliente' => $clienteId]);
$venda = $stmtVenda->fetch(PDO::FETCH_ASSOC);

if (!$venda) {
    header('Location: index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| CLIENTE
|--------------------------------------------------------------------------
*/
$stmtCliente = $pdo->prepare("SELECT * FROM cliente WHERE id = :id");
$stmtCliente->execute([':id' => $clienteId]);
$cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| ITENS DA VENDA
|--------------------------------------------------------------------------
*/
$stmtItens = $pdo->prepare("
    SELECT *
    FROM vendas_itens
    WHERE venda_id = :venda
");
$stmtItens->execute([':venda' => $venda['id']]);
$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| PREPARAR CONSULTA DE OPÇÕES
|--------------------------------------------------------------------------
*/
$stmtOpcoes = $pdo->prepare("
    SELECT *
    FROM vendas_itens_opcoes
    WHERE venda_item_id = :id
");

/*
|--------------------------------------------------------------------------
| BAIRROS (ENTREGA)
|--------------------------------------------------------------------------
*/
$stmtBairros = $pdo->query("SELECT * FROM bairros ORDER BY nome");
$bairros = $stmtBairros->fetchAll(PDO::FETCH_ASSOC);

$totalCompra = (float) $venda['valor_compra'];
?>

<style>
    .panel-body-scroll {
        max-height: 250px;
        overflow-y: auto;
    }

    .container {
        padding-bottom: 120px;
    }
</style>

<div class="container">
    <div class="general-grids">

        <h4 class="title2">Confirmação do Pedido</h4>

        <!-- CLIENTE -->
        <div class="panel panel-default">
            <div class="panel-heading">Cliente</div>
            <div class="panel-body">
                <p><strong>Nome:</strong> <?= $cliente['nome'] ?></p>
                <p><strong>Telefone:</strong> <?= $cliente['telefone'] ?></p>
            </div>
        </div>

        <!-- RESUMO -->
        <div class="panel panel-default">
            <div class="panel-heading">Resumo do Pedido</div>
            <div class="panel-body panel-body-scroll">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Qtd</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($itens as $item): ?>

                            <?php
                            // Nome do item principal
                            if ($item['item_tipo'] === 'produto') {
                                $stmtNome = $pdo->prepare("SELECT nome FROM produtos WHERE id = :id");
                            } else {
                                $stmtNome = $pdo->prepare("SELECT descricao FROM variacoes WHERE id = :id");
                            }
                            $stmtNome->execute([':id' => $item['item_id']]);
                            $nome = $stmtNome->fetchColumn() ?? 'Item';

                            // Buscar adicionais / ingredientes
                            $stmtOpcoes->execute([':id' => $item['id']]);
                            $opcoes = $stmtOpcoes->fetchAll(PDO::FETCH_ASSOC);
                            ?>

                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($nome) ?></strong>

                                    <?php if (!empty($opcoes)): ?>
                                        <ul style="margin:5px 0 0 15px; padding:0; list-style:disc;">
                                            <?php foreach ($opcoes as $op): ?>

                                                <?php
                                                // Nome da opção
                                                if ($op['tipo'] === 'adicional') {
                                                    $stmtNomeOp = $pdo->prepare("SELECT nome FROM adicionais WHERE id = :id");
                                                } else {
                                                    $stmtNomeOp = $pdo->prepare("SELECT nome FROM ingredientes WHERE id = :id");
                                                }
                                                $stmtNomeOp->execute([':id' => $op['id_item']]);
                                                $nomeOpcao = $stmtNomeOp->fetchColumn() ?? 'Opção';
                                                ?>

                                                <li style="font-size:13px;">
                                                    <?= $nomeOpcao ?> —
                                                    R$ <?= number_format($op['valor_total'], 2, ',', '.') ?>
                                                </li>

                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </td>

                                <td><?= $item['quantidade'] ?></td>
                                <td>R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>

                <p class="text-right">
                    <strong>Total:</strong> R$ <?= number_format($totalCompra, 2, ',', '.') ?>
                </p>

            </div>
        </div>

        <!-- TIPO DE PEDIDO -->
        <div class="panel panel-default">
            <div class="panel-heading" style="font-size: larger; font-weight:bold;">Tipo do Pedido</div>
            <div class="panel-body">

                <label><input type="radio" name="tipo_pedido" value="retirada"> Retirada</label><br>
                <label><input type="radio" name="tipo_pedido" value="local"> Consumo no local</label><br>
                <label><input type="radio" name="tipo_pedido" value="entrega"> Entrega</label>

            </div>
        </div>

        <!-- ENDEREÇO -->
        <div class="panel panel-default" id="boxEndereco" style="display:none;">
            <div class="panel-heading">Endereço de Entrega</div>
            <div class="panel-body">

                <div class="form-group">
                    <label>Bairro</label>
                    <select id="bairro" class="form-control">
                        <option value="">Selecione</option>
                        <?php foreach ($bairros as $b): ?>
                            <option value="<?= $b['id'] ?>" data-valor="<?= $b['valor'] ?>">
                                <?= $b['nome'] ?> - R$ <?= number_format($b['valor'], 2, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Rua</label>
                    <input type="text" id="rua" class="form-control" value="<?= $cliente['rua'] ?>">
                </div>

                <div class="form-group">
                    <label>Número</label>
                    <input type="text" id="numero" class="form-control" value="<?= $cliente['numero'] ?>">
                </div>

            </div>
        </div>

        <!-- PAGAMENTO -->
        <div class="panel panel-default">
            <div class="panel-heading" style="font-size: larger; font-weight:bold;">Pagamento</div>
            <div class="panel-body">

                <label><input type="radio" name="pagamento" value="pix"> Pix</label><br>
                <label><input type="radio" name="pagamento" value="dinheiro"> Dinheiro</label><br>
                <label><input type="radio" name="pagamento" value="cartao"> Cartão</label>

                <div id="trocoBox" style="display:none;">
                    <label style="font-size: larger; font-weight:bold;">Troco para quanto?</label>
                    <input type="number" id="troco" class="form-control">
                </div>

            </div>
        </div>

        <button class="btn btn-success w-100" id="confirmarPedido">
            Confirmar Pedido
        </button>

    </div>
</div>

<?php require_once 'footer.php'; ?>

<script>
    $('input[name="tipo_pedido"]').on('change', function() {
        $('#boxEndereco').toggle(this.value === 'entrega');
    });

    $('input[name="pagamento"]').on('change', function() {
        $('#trocoBox').toggle(this.value === 'dinheiro');
    });
</script>