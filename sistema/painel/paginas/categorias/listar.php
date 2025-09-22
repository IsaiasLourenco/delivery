<?php
require_once("../../../conexao.php");
$tabela = 'categorias';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total > 0) {
    echo <<<HTML
    <h4 class="centro">Categorias</h4>
    <table class="table table-hover table-sm table-responsive tabela-menor" id="tabela">
        <thead>
            <tr>
                <th class="centro">Nome</th>
                <th>Cor</th>
                <th class="centro">Descrição</th>
                <th class="centro">Ativo</th>
                <th class="centro">Foto</th>
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
        $cor = $res[$i]['cor'];
        $descricao = $res[$i]['descricao'];
        $ativo = $res[$i]['ativo'];
        $foto = $res[$i]['foto'];

        $descricaoF = mb_strimwidth($descricao, 0, 15, "...");

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

echo <<<HTML
<tr  class="{$classe_linha}">
    <td class="centro">{$nome}</td>
    <td class="esc"><div class="divcor centro {$cor}"></div></td>
    <td class="esc centro">{$descricaoF}</td>
    <td class="esc centro">{$ativo}</td>
    <td class="esc centro"><img src="images/categorias/{$foto}" class="listar-foto"></td>
    <td class="centro">
                <a onclick="editar( '{$id}',
                                    '{$nome}', 
                                    '{$cor}',
                                    '{$descricao}',
                                    '{$ativo}',
                                    '{$foto}')", title="Editar Registro">
            <i class="fa fa-edit text-primary pointer"></i>
        </a>

        <li class="dropdown head-dpdn2 d-il-b">
            <a title="Excluir Registro" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-trash text-danger pointer"></i></a>
            <ul class="dropdown-menu mg-l--23">
                <li>
                    <div class="notification_desc2">
                        <p>Confirmar Exclusão?<a href="#" onclick="excluir('{$id}')"><span class="text-danger"> Sim</span></a></p>
                    </div>
                </li>
            </ul>
        </li>
        <a onclick="ativar('{$id}', 
                            '{$acao}')", title="{$titulo_link}">
            <i class="fa {$icone} text-success pointer"></i>
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
    function editar(id, nome, cor, descricao, ativo, foto) {
        $('#id-cat').val(id);
        $('#nome-cat').val(nome);
        $('#cor-cat').val(cor);
        $('#descricao-cat').val(descricao);
        $('#ativo-cat').val(ativo);

        $('#titulo_inserir').text('Editar Registro');
        $('#modalForm').modal('show');
        $('#foto-cat').val('');
        $('#target-cat').attr('src', 'images/categorias/' + foto);
    }


    function limparCampos() {
        $('#id-cat').val('');
        $('#nome-cat').val('');
        $('#cor-cat').val('');
        $('#descricao-cat').val('');
        $('#target-cat').attr('src', 'images/categorias/sem-foto.jpg')
    }
</script>