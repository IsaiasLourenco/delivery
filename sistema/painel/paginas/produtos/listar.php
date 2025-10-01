<?php
require_once("../../../conexao.php");
$tabela = 'produtos';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total > 0) {
    echo <<<HTML
    <h4 class="centro">Produtos</h4>
    <table class="table table-hover table-sm table-responsive tabela-menor" id="tabela">
        <thead>
            <tr>
                <th class="centro">Nome</th>
                <th class="esc centro">Categoria</th>
                <th class="esc centro">Valor de Compra</th>
                <th class="esc centro">Valor de Venda</th>
                <th class="esc centro">Estoque</th>
                <th class="esc centro">Foto</th>
                <th class="centro">Ações</th>
            </tr>
        </thead>
        <tbody>
HTML;

    for ($i = 0; $i < $total; $i++) {
        foreach ($res[$i] as $key => $value) {
        }
        $id = $res[$i]['id'];
        $nome = $res[$i]['nome'];
        $descricao = $res[$i]['descricao'];
        $categoria = $res[$i]['categoria'];
        $valor_compra = $res[$i]['valor_compra'];
        $valor_venda = $res[$i]['valor_venda'];
        $estoque = $res[$i]['estoque'];
        $nivel_estoque = $res[$i]['nivel_estoque'];
        $ativo = $res[$i]['ativo'];
        $tem_estoque = $res[$i]['tem_estoque'];
        $foto = $res[$i]['foto'];

        // Consulta da categoria
        $queryCategoria = $pdo->query("SELECT * FROM categorias WHERE id = '$categoria'");
        $resCategoria = $queryCategoria->fetchAll(PDO::FETCH_ASSOC);
        $nomeCategoria = $resCategoria[0]['nome'];


        if ($ativo == 'Sim') {
            $icone = 'fa-check-square';
            $titulo_link = 'Desativar';
            $acao = 'Não';
            $classe_linha = '';
        } else {
            $icone = 'fa-square-o';
            $titulo_link = 'Ativar';
            $acao = 'Sim';
            $classe_linha = 'text-muted';
        }

        // Formatar valores de compra e venda
        $valor_compra_formatado = "R$ " . number_format($valor_compra, 2, ',', '.');
        $valor_venda_formatado = "R$ " . number_format($valor_venda, 2, ',', '.');

        if ($nivel_estoque >= $estoque and $tem_estoque == 'Sim') {
            $alerta_estoque = 'text-danger';
        } else {
            $alerta_estoque = '';
        }

        $descricaoF = mb_strimwidth($descricao, 0, 15, "...");

echo <<<HTML
<tr class="{$classe_linha} {$alerta_estoque}">
    <td class="centro">{$nome}</td>
    <td class="esc centro">{$nomeCategoria}</td>
    <td class="esc centro">{$valor_compra_formatado}</td>
    <td class="esc centro">{$valor_venda_formatado}</td>
    <td class="esc centro">{$estoque}</td>
    <td class="esc centro"><img src="images/produtos/{$foto}" class="listar-foto"></td>
    <td class="centro">
        <a onclick="editar( '{$id}',
                            '{$nome}', 
                            '{$descricao}',
                            '{$categoria}', 
                            '{$valor_compra_formatado}',
                            '{$valor_venda_formatado}',
                            '{$estoque}',
                            '{$nivel_estoque}',
                            '{$ativo}',
                            '{$tem_estoque}',
                            '{$foto}')", title="Editar Registro">
            <i class="fa fa-edit text-primary pointer"></i>
        </a>
        <li class="dropdown head-dpdn2 d-il-b">
            <a title="Excluir Registro" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-trash text-danger pointer"></i></a>
            <ul class="dropdown-menu mg-l--150">
                <li>
                    <div class="notification_desc2">
                        <p>Confirmar Exclusão?<a href="#" onclick="excluir('{$id}')"><span class="text-danger"> Sim</span></a></p>
                    </div>
                </li>
            </ul>
        </li>
        <a onclick="mostrar('{$nome}', 
                            '{$descricao}', 
                            '{$estoque}',
                            '{$nivel_estoque}',
                            '{$ativo}',
                            '{$tem_estoque}',
                            '{$foto}')", title="Mostrar mais Dados">
            <i class="fa fa-info-circle text-secondary pointer"></i>
        </a>
        <a onclick="ativar('{$id}', 
                            '{$acao}')", title="{$titulo_link}">
            <i class="fa {$icone} text-verde pointer"></i>
        </a>
        <a onclick="saida('{$id}',
                          '{$nome}',
                          '{$estoque}')", title="Saída de produto">
            <i class="fa fa-sign-out text-danger pointer"></i>
        </a>
        <a onclick="entrada('{$id}',
                            '{$nome}',
                            '{$estoque}')", title="Entrada de produto">
            <i class="fa fa-sign-in text-verde pointer"></i>
        </a>
        <a onclick="variacoes('{$id}',
                              '{$nome}')", title="Variações do produto">
            <i class="fa fa-list text-primary pointer"></i>
        </a>
        <a onclick="ingredientes('{$id}',
                                 '{$nome}')", title="Ingredientes do produto">
            <i class="fa fa-cutlery text-cinza pointer"></i>
        </a>
        <a onclick="adicionais('{$id}',
                               '{$nome}')", title="Adicionais do produto">
            <i class="fa fa-plus text-verde pointer"></i>
        </a>
    </td>
</tr>
HTML;
    }

echo <<<HTML
        </tbody>
            <div class="centro texto-menor" id="mensagem-excluir"></div>
    </table>    
HTML;
} else {
    echo "Sem registros cadastrados!";
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabela').DataTable({
            "ordering": false,
            "stateSave": true,
        });
        $('#tabela_filter label input').focus();
    });
</script>

<script type="text/javascript">
    function editar(id, nome, descricao, categoria, valor_compra, valor_venda, estoque, nivel_estoque, ativo, tem_estoque, foto) {
        $('#id-produto').val(id);
        $('#nome-produto').val(nome);
        $('#descricao-produto').val(descricao);
        $('#categoria-produto').val(categoria);
        $('#valor_compra-produto').val(valor_compra);
        $('#valor_venda-produto').val(valor_venda);
        $('#estoque-produto').val(estoque);
        $('#nivel_estoque-produto').val(nivel_estoque);
        $('#ativo-produto').val(ativo).change();
        $('#tem_estoque-produto').val(tem_estoque).change();

        $('#titulo_inserir').text('Editar Registro');
        $('#modalForm').modal('show');
        $('#foto-produto').val('');
        $('#target-produto').attr('src', 'images/produtos/' + foto);
    }

    function limparCampos() {
        $('#id-produto').val('');
        $('#nome-produto').val('');
        $('#descricao-produto').val('');
        $('#categoria-produto').val('');
        $('#valor_compra-produto').val('');
        $('#valor_venda-produto').val('');
        $('#nivel_estoque-produto').val('');
        $('#target-produto').attr('src', 'images/produtos/sem-foto.jpg')
    }

    function mostrar(nome, descricao, estoque, nivel_estoque, ativo, tem_estoque, foto) {
        $('#nome_dados-produto').text(nome);
        $('#descricao_dados-produto').text(descricao);
        $('#estoque_dados-produto').text(estoque);
        $('#nivel_estoque_dados-produto').text(nivel_estoque);
        $('#ativo_dados-produto').text(ativo).change();
        $('#tem_estoque_dados-produto').text(tem_estoque).change();

        $('#modalDados').modal('show');
        $('#target_mostrar-produto').attr('src', 'images/produtos/' + foto);
    }
</script>

<!-- Saida de produtos -->
<script type="text/javascript">
    function saida(id, nome, estoque) {
        $('#id_saida').val(id);
        $('#nome_saida').text(nome);
        $('#estoque_saida').val(estoque);

        $('#modalSaida').modal('show');
    }
</script>
<!-- Fim Saida de produtos -->

<!-- Entrada de produtos -->
<script type="text/javascript">
    function entrada(id, nome, estoque) {
        $('#id_entrada').val(id);
        $('#nome_entrada').text(nome);
        $('#estoque_entrada').val(estoque);

        $('#modalEntrada').modal('show');
    }
</script>
<!-- Fim Entrada de produtos -->

<!-- Variações de produtos -->
<script type="text/javascript">
    function variacoes(id, nome) {
        $('#id_var').val(id);
        $('#titulo_nome_var').text(nome);
        listarVariacoes(id);
        $('#modalVariacoes').modal('show');
        limparCamposVar()
    }
</script>
<!-- Fim Variações de produtos -->

<!-- Ingredientes de produtos -->
<script type="text/javascript">
    function ingredientes(id, nome) {
        $('#id_ing').val(id);
        $('#titulo_nome_ing').text(nome);
        listarIngredientes(id);
        $('#modalIngredientes').modal('show');
        limparCamposIng()
    }
</script>
<!-- Fim Ingredientes de produtos -->

<!-- Adicionais de produtos -->
<script type="text/javascript">
    function adicionais(id, nome, valor) {
        $('#id_ad').val(id);
        $('#titulo_nome_ad').text(nome);
        $('#valor_ad').text(valor);
        listarAdicionais(id);
        $('#modalAdicionais').modal('show');
        limparCamposAd()
    }
</script>
<!-- Fim Adicionais de produtos -->