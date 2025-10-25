<?php
require_once("../../../conexao.php");
$tabela = 'ingredientes';
$id_produto = $_POST['id'];
$query = $pdo->query("SELECT * FROM $tabela WHERE produto = '$id_produto' ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total > 0) {
    echo <<<HTML
    <h4 class="centro mg-b-20">Ingredientes do Produto</h4>
    <table class="table table-hover table-sm table-responsive tabela-menor" id="tabela-ing">
        <thead>
            <tr>
                <th class="centro">Nome</th>
                <th class="esc centro">Valor</th>
                <th class="esc centro">Ativo</th>
                <th class="centro">Ações</th>
            </tr>
        </thead>
        <tbody>
HTML;

    for ($i = 0; $i < $total; $i++) {
        foreach ($res[$i] as $key => $value) {
        }
        $idIng = $res[$i]['id'];
        $produto = $res[$i]['produto'];
        $nome = $res[$i]['nome'];
        $valor = $res[$i]['valor'];
        $ativo = $res[$i]['ativo'];

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

        $valor_formatado = "R$ " . number_format($valor, 2, ',', '.');

        echo <<<HTML
<tr class="{$classe_linha}">
    <td class="esc centro">{$nome}</td>
    <td class="esc centro">{$valor_formatado}</td>
    <td class="centro">{$ativo}</td>
    <td class="centro">
        <a onclick="editarIng( '{$idIng}',
                                '{$produto}', 
                                '{$nome}', 
                                '{$valor_formatado}', 
                                '{$ativo}')", title="Editar Registro">
            <i class="fa fa-edit text-primary pointer"></i>
        </a>
        <li class="dropdown head-dpdn2 d-il-b">
            <a title="Excluir Registro" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-trash text-danger pointer"></i></a>
            <ul class="dropdown-menu mg-l--23">
                <li>
                    <div class="notification_desc2">
                        <p>Confirmar Exclusão?<a href="#" onclick="excluirIng('{$idIng}')"><span class="text-danger"> Sim</span></a></p>
                    </div>
                </li>
            </ul>
        </li>
        <a onclick="ativarIng('{$idIng}', 
                              '{$acao}')", title="{$titulo_link}">
            <i class="fa {$icone} text-verde pointer"></i>
        </a>
    </td>
</tr>
HTML;
    }

    echo <<<HTML
        </tbody>
            <div class="centro texto-menor" id="mensagem-excluir-ing"></div>
    </table>    
HTML;
} else {
    echo "<p class='centro texto-menor'>Não possui ingredientes cadastrados!</p>";
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabela-ing').DataTable({
            "ordering": false,
            "stateSave": true,
        });
        $('#tabela_filter label input').focus();
    });
</script>

<script type="text/javascript">
    function editarIng(idIng, produto, nome, valor, ativo) {
        $('#id_ingrediente').val(idIng); // ID do ingrediente
        $('#id_ing').val(produto); // ID do produto
        $('#nome_ing').val(nome);
        $('#valor_ing').val(valor);
        $('#ativo_ing').val(ativo);
        $('#btn-salvar-ing').text('Editar');
        $('#btn-novo-ing').show();
    }
</script>