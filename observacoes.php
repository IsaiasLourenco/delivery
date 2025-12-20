<?php
require_once("header.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

/**
 * SE FOI ENVIADO O FORMULÁRIO DE FINALIZAÇÃO
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantidade = (int)($_POST['quantidade'] ?? 1);
    $observacao = trim($_POST['observacao'] ?? '');

    // Atualiza todos os itens do carrinho temporário dessa sessão
    $stmtUpdate = $pdo->prepare("
        UPDATE carrinho_temp
        SET quantidade = :quantidade,
            valor_total = quantidade * valor_item,
            observacao = :observacao
        WHERE sessao = :sessao
        AND tipo != 'ingrediente'
    ");
    $stmtUpdate->execute([
        ':quantidade' => $quantidade,
        ':observacao' => $observacao,
        ':sessao'     => $sessao
    ]);

    // Redireciona para o carrinho real
    header("Location: carrinho.php");
    exit;
}

/**
 * BUSCA ITENS DO CARRINHO (PRODUTO + VARIAÇÃO + ADICIONAIS + INGREDIENTES)
 */
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

/**
 * TOTAL GERAL
 */
$stmtTotal = $pdo->prepare("
    SELECT COALESCE(SUM(valor_total),0)
    FROM carrinho_temp
    WHERE sessao = :sessao
");
$stmtTotal->execute([':sessao' => $sessao]);
$totalBase = (float)$stmtTotal->fetchColumn();
$totalBaseF = 'R$ ' . number_format($totalBase, 2, ',', '.');
?>
<style>
    .bck-verde {
        background-color: #008F39;
        color: white;
    }
</style>
<div class="main-container">
    <nav class="navbar navbar-light bg-light fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="#" id="voltar-link" class="link-neutro">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="margin-itens">RESUMO DO ITEM</span>
            </div>
            <?php require_once("icone-popup-carrinho.php"); ?>
        </div>
    </nav>

    <?php if (!empty($itens)) { ?>
        <div class="resumo-adicionais mg-t-6">
            <ul class="list-group">
                <?php foreach ($itens as $item) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
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
                        </span>
                        <span>
                            <?php echo 'R$ ' . number_format($item['valor_total'], 2, ',', '.'); ?>
                        </span>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

    <form action="" method="POST">
        <input type="hidden" name="sessao" value="<?php echo $sessao ?>">

        <div class="qtd final">
            Quantidade
            <span class="area-qtd">
                <button type="button" onclick="alterarQtd(-1)" class="btn btn-link text-danger">
                    <i class="bi bi-dash-circle-fill"></i>
                </button>

                <strong id="qtd">1</strong>
                <input type="hidden" name="quantidade" id="qtd_input" value="1">

                <button type="button" onclick="alterarQtd(1)" class="btn btn-link text-success">
                    <i class="bi bi-plus-circle-fill"></i>
                </button>
            </span>
        </div>

        <div class="txt-area-obs">
            OBSERVAÇÕES
            <textarea class="form-control" name="observacao"></textarea>
        </div>

        <div class="total">
            <p>Total <strong id="total"><?php echo $totalBaseF ?></strong></p>
        </div>

        <button
            type="button"
            class="btn btn-primary w-100 mg-t-2"
            onclick="abrirPopupCliente()">
            Adicionar ao carrinho
        </button>


    </form>
</div>

<?php require_once("footer.php"); ?>

<script>
    const linkVoltar = sessionStorage.getItem('back_url');
    if (linkVoltar) {
        document.getElementById('voltar-link').href = linkVoltar;
    }
</script>

<script>
    const TOTAL_BASE = <?php echo $totalBase ?>;

    function alterarQtd(delta) {
        let qtd = parseInt(document.getElementById('qtd').textContent);
        qtd = Math.max(1, qtd + delta);

        document.getElementById('qtd').textContent = qtd;
        document.getElementById('qtd_input').value = qtd;

        let total = qtd * TOTAL_BASE;
        document.getElementById('total').textContent =
            'R$ ' + total.toFixed(2).replace('.', ',');
    }

    function verificarCliente() {
        fetch('verificar-sessao-cliente.php')
            .then(res => res.json())
            .then(data => {
                if (data.cliente_identificado) {
                    document.querySelector('form').submit();
                } else {
                    abrirPopupCliente();
                }
            });
    }


    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const popup = document.getElementById('popup-cliente');

            if (popup && popup.style.visibility === 'visible') {
                fecharPopupCliente();
            }
        }
    });

    function abrirPopupCliente() {
        document.getElementById('popup-cliente').style.visibility = 'visible';
        document.getElementById('popup-cliente').style.opacity = '1';
    }

    function fecharPopupCliente() {
        document.getElementById('popup-cliente').style.visibility = 'hidden';
        document.getElementById('popup-cliente').style.opacity = '0';
    }
</script>

<!-- POPUP CLIENTE -->
<div id="popup-cliente" class="overlay-excluir">
    <div class="popup-excluir">
        <a href="javascript:void(0)" class="close-excluir" onclick="fecharPopupCliente()">&times;</a>

        <h5 class="titulo-popup mg-b-2">Identificação do Cliente</h5>

        <div class="mg-b-2">
            <label>Telefone</label>
            <input type="text" id="telefone-cli" class="form-control" placeholder="(00) 00000-0000">
        </div>

        <div class="mg-b-2">
            <label>Nome</label>
            <input type="text" id="cliente-nome" class="form-control" placeholder="Seu nome">
        </div>

        <div class="d-flex">
            <button
                type="button"
                class="btn btn-primary w-50 mg-t-2 mr-2"
                onclick="window.location.href='<?= $url_sistema ?>'">
                Comprar Mais
            </button>

            <button class="btn bck-verde w-50 mg-t-2" onclick="confirmarCliente()">
                Finalizar Pedido
            </button>
        </div>
    </div>
</div>

<script>
    function confirmarCliente() {
        const telefoneInput = document.getElementById('telefone-cli');
        const nomeInput = document.getElementById('cliente-nome');

        const telefone = telefoneInput.value.trim();
        const nome = nomeInput.value.trim();

        if (telefone === '') {
            alert('Informe o telefone');
            telefoneInput.focus();
            return;
        }

        if (nome === '') {
            alert('Informe o nome');
            nomeInput.focus();
            return;
        }

        fetch('validar-cliente.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    telefone: telefone
                })
            })
            .then(res => res.json())
            .then(data => {

                if (!data.success) {
                    alert('Erro ao validar cliente');
                    return;
                }

                if (data.existente) {
                    // ✅ Cliente identificado → finaliza carrinho
                    document.querySelector('form').submit();
                } else {
                    // ⛔ Cliente não existe → próximo passo (cadastro)
                    alert('Cliente não cadastrado. Vamos cadastrar agora.');
                    // aqui depois chamamos o popup / tela de cadastro
                    cadastrarCliente(telefone, nome);
                }

            })
            .catch(err => {
                console.error('ERRO FETCH:', err);
                alert('Erro de comunicação com o servidor');
            });
    }

    function cadastrarCliente(telefone, nome) {
        fetch('cadastrar-cliente.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    telefone: telefone,
                    nome: nome
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('form').submit();
                } else {
                    alert('Erro ao cadastrar cliente');
                }
            })
            .catch(() => {
                alert('Erro de comunicação ao cadastrar cliente');
            });
    }
</script>