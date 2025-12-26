<?php
session_start();
require_once './sistema/conexao.php';

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

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

$stmtTotal = $pdo->prepare("
    SELECT COALESCE(SUM(valor_total),0)
    FROM carrinho_temp
    WHERE sessao = :sessao
");
$stmtTotal->execute([':sessao' => $sessao]);
$total = (float)$stmtTotal->fetchColumn();

if (empty($itens)) {
    echo '<p class="text-center">Seu carrinho está vazio</p>';
    exit;
}

echo '<ul class="list-group mb-3">';
foreach ($itens as $item) {

    $nome = match ($item['tipo']) {
        'produto' => $item['nome_produto'],
        'variacao' => $item['nome_variacao'],
        'adicional' => $item['nome_adicional'],
        'ingrediente' => $item['nome_ingrediente'],
    };

    echo '
    <li class="list-group-item d-flex justify-content-between">
        <span>' . $nome . ' <small class="text-muted">x' . $item['quantidade'] . '</small></span>
        <span>R$ ' . number_format($item['valor_total'], 2, ',', '.') . '</span>
    </li>';
}
echo '</ul>';

echo '
<div class="d-flex justify-content-between bold mb-3">
    <span>Total</span>
    <span>R$ ' . number_format($total, 2, ',', '.') . '</span>
</div>
<a href="carrinho.php" class="btn btn-primary w-100">Ver carrinho</a>';
