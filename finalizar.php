<?php
require_once("./sistema/conexao.php"); // Incluindo a conexão com o banco

session_start();

// Verifica se o usuário está logado (pode ajustar conforme o seu fluxo)
if (!isset($_SESSION['sessao_usuario'])) {
    header("Location: login.php");
    exit();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

// Registrar pedido no banco
try {
    // Inicia uma transação
    $pdo->beginTransaction();

    // Inserir os dados do pedido no banco (ajuste conforme sua estrutura de tabelas)
    $stmt = $pdo->prepare("INSERT INTO pedidos (sessao, total, data_criacao) VALUES (:sessao, :total, NOW())");
    $stmt->execute([
        ':sessao' => $sessao,
        ':total' => $totalBase // Pode ser o total do carrinho, já calculado anteriormente
    ]);

    // Obtém o ID do pedido inserido
    $pedido_id = $pdo->lastInsertId();

    // Inserir itens do carrinho no pedido (ajuste conforme sua estrutura)
    $stmtItens = $pdo->prepare("SELECT * FROM carrinho_temp WHERE sessao = :sessao");
    $stmtItens->execute([':sessao' => $sessao]);
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC());

    foreach ($itens as $item) {
        $stmtItemPedido = $pdo->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco) VALUES (:pedido_id, :produto_id, :quantidade, :preco)");
        $stmtItemPedido->execute([
            ':pedido_id' => $pedido_id,
            ':produto_id' => $item['produto_id'], // Ou outro campo relevante
            ':quantidade' => $item['quantidade'],
            ':preco' => $item['valor_item'] // Preço do item
        ]);
    }

    // Apagar o carrinho temporário após a finalização
    $stmtDelete = $pdo->prepare("DELETE FROM carrinho_temp WHERE sessao = :sessao");
    $stmtDelete->execute([':sessao' => $sessao]);

    // Confirmar a transação
    $pdo->commit();

    // Redireciona para a página de confirmação ou pagamento
    header("Location: confirmacao.php");
    exit();
} catch (Exception $e) {
    // Se houver algum erro, desfaz a transação
    $pdo->rollBack();
    echo "Erro ao finalizar o pedido: " . $e->getMessage();
}