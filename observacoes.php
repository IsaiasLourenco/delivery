<?php
require_once("header.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sessao = $_SESSION['sessao_usuario'] ?? session_id();

/**
 * SE FOI ENVIADO O FORMULÁRIO DE FINALIZAÇÃO
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $quantidade     = (int)($_POST['quantidade'] ?? 1);
    $observacao     = trim($_POST['observacao'] ?? '');
    $produto_pai_id = (int)($_POST['produto_pai_id'] ?? 0);

    if ($produto_pai_id > 0) {

        $stmtUpdate = $pdo->prepare("
            UPDATE carrinho_temp
            SET quantidade = :quantidade,
                observacao = :observacao
            WHERE id = :id
              AND sessao = :sessao
              AND tipo = 'produto'
        ");

        $stmtUpdate->execute([
            ':quantidade' => $quantidade,
            ':observacao' => $observacao,
            ':id'         => $produto_pai_id,
            ':sessao'     => $sessao
        ]);
    }

    header("Location: carrinho.php");
    exit;
}

/**
 * BUSCA O PRODUTO PAI VIA GET
 */
$produto_pai_id = (int)($_GET['pai'] ?? 0);

if ($produto_pai_id <= 0) {
    die("Produto pai não informado.");
}

/**
 * ITENS DO PRODUTO (PAI + FILHOS)
 */

$stmtItens = $pdo->prepare("SELECT ct.id,
                                   ct.tipo,
                                   ct.id_item,
                                   ct.produto_pai_id,
                                   ct.quantidade,
                                   ct.valor_item,
                                   ct.valor_total,
                                   p.nome AS nome_produto,
                                   v.descricao AS nome_variacao,
                                   a.nome AS nome_adicional,
                                   i.nome AS nome_ingrediente
                            FROM carrinho_temp ct
                            LEFT JOIN produtos p ON p.id = ct.id_item AND ct.tipo = 'produto'
                            LEFT JOIN variacoes v ON v.id = ct.id_item AND ct.tipo = 'variacao'
                            LEFT JOIN adicionais a ON a.id = ct.id_item AND ct.tipo = 'adicional'
                            LEFT JOIN ingredientes i ON i.id = ct.id_item AND ct.tipo = 'ingrediente'
                            WHERE ct.sessao = :sessao
                            AND (ct.id = :pai OR ct.produto_pai_id = :pai)
                            ORDER BY ct.id ASC");
$stmtItens->execute([
    ':sessao' => $sessao,
    ':pai'    => $produto_pai_id
]);
$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

/**
 * TOTAL DO ITEM (PAI + FILHOS)
 */
$totalItem = 0;
foreach ($itens as $item) {
    $totalItem += (float)$item['valor_total'];
}

/**
 * TOTAL DO CARRINHO INTEIRO (TODOS OS ITENS DA SESSÃO)
 */
$stmtTotalCarrinho = $pdo->prepare("
    SELECT COALESCE(SUM(valor_total),0)
    FROM carrinho_temp
    WHERE sessao = :sessao
");
$stmtTotalCarrinho->execute([
    ':sessao' => $sessao
]);
$totalCarrinho = (float)$stmtTotalCarrinho->fetchColumn();

/**
 * TOTAL EXIBIDO = CARRINHO INTEIRO
 */
$totalExibido  = $totalCarrinho;
$totalExibidoF = 'R$ ' . number_format($totalExibido, 2, ',', '.');

// valores para o JS
$totalItemJs            = $totalItem;
$totalCarrinhoSemItemJs = $totalCarrinho - $totalItem;
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

    <?php if (!empty($itens)) : ?>
        <div class="resumo-adicionais mg-t-6">
            <ul class="list-group">
                <?php foreach ($itens as $item) : ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <?php
                            if ($item['tipo'] == 'produto') echo $item['nome_produto'];
                            elseif ($item['tipo'] == 'variacao') echo $item['nome_variacao'];
                            elseif ($item['tipo'] == 'adicional') echo '+ ' . $item['nome_adicional'];
                            elseif ($item['tipo'] == 'ingrediente') echo '- ' . $item['nome_ingrediente'];
                            ?>
                        </span>
                        <span>
                            <?php
                            if (in_array($item['tipo'], ['produto', 'adicional', 'ingrediente'])) {
                                echo 'R$ ' . number_format($item['valor_total'], 2, ',', '.');
                            }
                            ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="hidden" name="produto_pai_id" value="<?= $produto_pai_id ?>">

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
            <p>Total <strong id="total"><?= $totalExibidoF ?></strong></p>
        </div>

        <button type="button" class="btn btn-primary w-100 mg-t-2" onclick="abrirPopupCliente()">
            Adicionar ao carrinho
        </button>
    </form>
</div>

<?php require_once("footer.php"); ?>

<!-- MODAL CLIENTE COMPRAR MAIS -->
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
            <button type="button" class="btn btn-primary w-50 mg-t-2 mr-2"
                onclick="window.location.href='<?= $url_sistema ?>'">
                Comprar Mais
            </button>

            <button class="btn bck-verde w-50 mg-t-2" onclick="confirmarCliente()">
                Finalizar Pedido
            </button>
        </div>
    </div>
</div>
<!-- FIM MODAL CLIENTE COMPRAR MAIS -->

<!-- MODAL CADASTRO COMPLETO -->
<div id="modal-cadastro-completo" class="overlay-excluir" style="visibility:hidden; opacity:0;">
    <div class="popup-excluir">

        <a href="javascript:void(0)" class="close-excluir" onclick="fecharModalCadastroCompleto()">&times;</a>

        <h5 class="titulo-popup mg-b-2">Complete seu Cadastro</h5>

        <div class="mg-b-2">
            <label>Nome Completo</label>
            <input type="text" id="cad-nome" class="form-control" placeholder="Seu nome completo">
        </div>

        <div class="mg-b-2">
            <label>E-mail</label>
            <input type="email" id="email-cli" class="form-control" placeholder="email@email.com" require>
        </div>

        <div class="row">
            <div class="mg-b-2">
                <label for="cep">CEP</label>
                <input type="text" class="form-control" id="cep-cli" name="cep" placeholder="00000-000" required>
            </div>

            <div class="mg-b-2">
                <label for="rua">Rua</label>
                <input type="text" class="form-control" id="rua-cli" name="rua" placeholder="Rua / Avenida" readonly>
            </div>
        </div>

        <div class="row">
            <div class="mg-b-2">
                <label for="numero">Número</label>
                <input type="text" class="form-control" id="numero-cli" name="numero" placeholder="Número" required>
            </div>

            <div class="mg-b-2">
                <label for="bairro">Bairro</label>
                <input type="text" class="form-control" id="bairro-cli" name="bairro" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <label for="cidade">Cidade</label>
                <input type="text" class="form-control" id="cidade-cli" name="cidade" readonly>
            </div>

            <div class="col-md-2">
                <label for="estado">Estado</label>
                <input type="text" class="form-control" id="estado-cli" name="estado" readonly>
            </div>
        </div>

        <div class="mg-b-2">
            <label>CPF</label>
            <input type="text" id="cpf-cli" class="form-control" placeholder="000.000.000-00">
        </div>

        <button class="btn bck-verde w-100 mg-t-2" onclick="salvarCadastroCompleto()">
            Salvar e Continuar
        </button>

    </div>
</div>
<!-- FIM MODAL CADASTRO COMPLETO -->

<script>
    const linkVoltar = sessionStorage.getItem('back_url');
    if (linkVoltar) {
        document.getElementById('voltar-link').href = linkVoltar;
    }

    const TOTAL_ITEM_BASE = <?= json_encode($totalItemJs) ?>;
    const TOTAL_CARRINHO_SEM_ITEM = <?= json_encode($totalCarrinhoSemItemJs) ?>;

    function alterarQtd(delta) {
        let qtd = parseInt(document.getElementById('qtd').textContent);
        qtd = Math.max(1, qtd + delta);

        document.getElementById('qtd').textContent = qtd;
        document.getElementById('qtd_input').value = qtd;

        let total = TOTAL_CARRINHO_SEM_ITEM + (qtd * TOTAL_ITEM_BASE);
        document.getElementById('total').textContent =
            'R$ ' + total.toFixed(2).replace('.', ',');
    }

    function abrirPopupCliente() {
        const popup = document.getElementById('popup-cliente');
        popup.style.visibility = 'visible';
        popup.style.opacity = '1';
    }

    function fecharPopupCliente() {
        const popup = document.getElementById('popup-cliente');
        popup.style.visibility = 'hidden';
        popup.style.opacity = '0';
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const popup = document.getElementById('popup-cliente');
            if (popup && popup.style.visibility === 'visible') {
                fecharPopupCliente();
            }
        }
    });

    function verificarCliente() {
        fetch('verificar-cliente.php')
            .then(res => res.json())
            .then(data => {
                if (data.cliente_identificado) {
                    document.querySelector('form').submit();
                } else {
                    abrirPopupCliente();
                }
            });
    }

    function confirmarCliente() {
        const telefone = document.getElementById('telefone-cli').value.trim();
        const nome = document.getElementById('cliente-nome').value.trim();

        if (telefone === '') {
            alert('Informe o telefone');
            return;
        }

        fetch('validar-cliente.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'telefone=' + encodeURIComponent(telefone)
            })
            .then(res => res.json())
            .then(data => {

                if (!data.success) {
                    alert('Erro ao validar cliente');
                    return;
                }

                // CLIENTE EXISTE
                if (data.existente) {
                    document.querySelector('form').submit();
                    return;
                }

                // CLIENTE NÃO EXISTE → PRECISA DO NOME
                if (nome === '') {
                    alert('Informe o nome para cadastrar');
                    return;
                }

                // CADASTRAR NOVO CLIENTE
                fetch('cadastrar-cliente.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'telefone=' + encodeURIComponent(telefone) +
                            '&nome=' + encodeURIComponent(nome)
                    })
                    .then(res => res.json())
                    .then(data2 => {
                        if (data2.success) {
                            document.querySelector('form').submit();
                        } else {
                            alert('Erro ao cadastrar cliente');
                        }
                    });
            });
    }

    document.getElementById('telefone-cli').addEventListener('blur', function() {
        const tel = this.value.trim();
        if (tel === '') return;

        // salva telefone na sessão via backend
        fetch('salvar-telefone-temp.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'telefone=' + encodeURIComponent(tel)
        });

        fetch('buscar-cliente.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'telefone=' + encodeURIComponent(tel)
            })
            .then(res => res.json())
            .then(data => {

                if (!data.success) {
                    alert('Erro ao buscar cliente');
                    return;
                }

                if (data.existente) {
                    document.getElementById('cliente-nome').value = data.nome;
                    return;
                }

                document.getElementById('cliente-nome').value = '';
                abrirModalCadastroCompleto();
            });
    });

    function abrirModalCadastroCompleto() {
        const modal = document.getElementById('modal-cadastro-completo');
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
    }

    function fecharModalCadastroCompleto() {
        const modal = document.getElementById('modal-cadastro-completo');
        modal.style.visibility = 'hidden';
        modal.style.opacity = '0';
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const popup = document.getElementById('modal-cadastro-completo');
            if (popup && popup.style.visibility === 'visible') {
                fecharModalCadastroCompleto();
            }
        }
    });

    function salvarCadastroCompleto() {

        const nome = document.getElementById('cad-nome').value.trim();
        const email = document.getElementById('email-cli').value.trim();
        const cpf = document.getElementById('cpf-cli').value.trim();
        const cep = document.getElementById('cep-cli').value.trim();
        const rua = document.getElementById('rua-cli').value.trim();
        const numero = document.getElementById('numero-cli').value.trim();
        const bairro = document.getElementById('bairro-cli').value.trim();
        const cidade = document.getElementById('cidade-cli').value.trim();
        const estado = document.getElementById('estado-cli').value.trim();

        if (!nome || !email || !cpf || !cep || !rua || !numero || !bairro || !cidade || !estado) {
            alert('Preencha todos os campos obrigatórios');
            return;
        }

        fetch('cadastrar-cliente-completo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'nome=' + encodeURIComponent(nome) +
                    '&email=' + encodeURIComponent(email) +
                    '&cpf=' + encodeURIComponent(cpf) +
                    '&cep=' + encodeURIComponent(cep) +
                    '&rua=' + encodeURIComponent(rua) +
                    '&numero=' + encodeURIComponent(numero) +
                    '&bairro=' + encodeURIComponent(bairro) +
                    '&cidade=' + encodeURIComponent(cidade) +
                    '&estado=' + encodeURIComponent(estado)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fecharModalCadastroCompleto();
                    document.querySelector('form').submit();
                } else {
                    alert(data.mensagem || 'Erro ao salvar cadastro');
                }
            });
    }

    // Validação de CPF
    document.getElementById('cpf-cli').addEventListener('blur', function() {
        const cpf = this.value;

        if (cpf.trim() === '') return;

        if (!validarCPF(cpf)) {
            marcarErro(this, 'CPF inválido');
            alert('CPF inválido!');
            this.value = '';
            this.focus();
        }
    });

    function marcarErro(campo, mensagem) {
        campo.style.border = '2px solid red';

        let msg = document.createElement('div');
        msg.className = 'erro-campo';
        msg.style.color = 'red';
        msg.style.fontSize = '12px';
        msg.textContent = mensagem;

        campo.parentNode.appendChild(msg);

        setTimeout(() => {
            campo.style.border = '';
            msg.remove();
        }, 3000);
    }

    document.getElementById('cpf-cli').addEventListener('blur', function() {
        const cpf = this.value;

        if (cpf.trim() === '') return;

        if (!validarCPF(cpf)) {
            marcarErro(this, 'CPF inválido');
            alert('CPF inválido!');
            this.value = '';
            this.focus();
        }
    });

    function marcarErro(campo, mensagem) {
        campo.style.border = '2px solid red';

        let msg = document.createElement('div');
        msg.className = 'erro-campo';
        msg.style.color = 'red';
        msg.style.fontSize = '12px';
        msg.textContent = mensagem;

        campo.parentNode.appendChild(msg);

        setTimeout(() => {
            campo.style.border = '';
            msg.remove();
        }, 3000);
    }
</script>