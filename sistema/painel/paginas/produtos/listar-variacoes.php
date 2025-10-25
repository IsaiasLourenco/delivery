<?php
require_once("../../../conexao.php");
$tabela = 'variacoes';
$id_produto = $_POST['id'];
$query = $pdo->query("SELECT * FROM $tabela WHERE produto = '$id_produto' ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total > 0) {
    echo <<<HTML
    <h4 class="centro mg-b-20">Variações do Produto</h4>
    <table class="table table-hover table-sm table-responsive tabela-menor" id="tabela-var">
        <thead>
            <tr>
                <th class="esc centro">Sigla</th>
                <th class="centro">Nome</th>
                <th class="esc centro">Valor</th>
                <th class="centro">Ações</th>
            </tr>
        </thead>
        <tbody>
HTML;

    for ($i = 0; $i < $total; $i++) {
        foreach ($res[$i] as $key => $value) {
        }
        $idVar = $res[$i]['id'];
        $nome = $res[$i]['nome'];
        $sigla = $res[$i]['sigla'];
        $descricao = $res[$i]['descricao'];
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

        // Formatar valores
        $valor_formatado = "R$ " . number_format($valor, 2, ',', '.');

        $descricaoF = mb_strimwidth($descricao, 0, 35, "...");

        echo <<<HTML
<tr class="{$classe_linha}">
    <td class="esc centro">{$sigla}</td>
    <td class="centro">{$descricaoF}</td>
    <td class="esc centro">{$valor_formatado}</td>
    <td class="centro">
        <a onclick="editarVar( '{$idVar}',
                                '{$nome}', 
                                '{$sigla}', 
                                '{$descricao}',
                                '{$valor_formatado}',
                                '{$ativo}')", title="Editar Registro">
            <i class="fa fa-edit text-primary pointer"></i>
        </a>
        <li class="dropdown head-dpdn2 d-il-b">
            <a title="Excluir Registro" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-trash text-danger pointer"></i></a>
            <ul class="dropdown-menu mg-l--23">
                <li>
                    <div class="notification_desc2">
                        <p>Confirmar Exclusão?<a href="#" onclick="excluirVar('{$idVar}')"><span class="text-danger"> Sim</span></a></p>
                    </div>
                </li>
            </ul>
        </li>
        <a onclick="ativarVar('{$idVar}', 
                            '{$acao}')", title="{$titulo_link}">
            <i class="fa {$icone} text-verde pointer"></i>
        </a>
    </td>
</tr>
HTML;
    }

    echo <<<HTML
        </tbody>
            <div class="centro texto-menor" id="mensagem-excluir-var"></div>
    </table>    
HTML;
} else {
    echo "<p class='centro texto-menor'>Não possui variações cadastradas!</p>";
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabela-var').DataTable({
            "ordering": false,
            "stateSave": true,
        });
        $('#tabela_filter label input').focus();
    });
</script>

<script type="text/javascript">
    function editarVar(idVar, nome, sigla, descricao, valor, ativo) {
        $('#id_variacao').val(idVar); // ID da variação
        $('#nome_var').val(nome);
        $('#sigla').val(sigla);
        $('#descricao_var').val(descricao);
        $('#valor_var').val(valor);
        $('#btn-salvar-var').text('Editar');
        $('#btn-novo-var').show();
    }
</script>