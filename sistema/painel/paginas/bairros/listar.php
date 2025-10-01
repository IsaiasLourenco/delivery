<?php
require_once("../../../conexao.php");
$tabela = 'bairros';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total > 0) {
    echo <<<HTML
    <h4 class="centro">Bairros</h4>
    <table class="table table-hover table-sm table-responsive tabela-menor" id="tabela">
        <thead>
            <tr>
                <th class="centro">Nome</th>
                <th class="centro">Valor</th>
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
        $valor = $res[$i]['valor'];

        $valor_formatado = "R$ " . number_format($valor, 2, ',', '.');

        echo <<<HTML
<tr>
    <td class="centro">{$nome}</td>
    <td class="centro">{$valor_formatado}</td>
    <td class="centro">
        <a onclick="editar( '{$id}',
                            '{$nome}', 
                            '{$valor_formatado}',)", title="Editar Registro">
            <i class="fa fa-edit text-primary pointer"></i>
        </a>

        <li class="dropdown head-dpdn2 d-il-b">
            <a title="Excluir Registro" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-trash text-danger pointer"></i></a>
            <ul class="dropdown-menu mg-l--150  ">
                <li>
                    <div class="notification_desc2">
                        <p>Confirmar Exclusão?<a href="#" onclick="excluir('{$id}')"><span class="text-danger"> Sim</span></a></p>
                    </div>
                </li>
            </ul>
        </li>
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
    function editar(id, nome, valor) {
        $('#id').val(id);
        $('#nome-bairro').val(nome);
        $('#valor-bairro').val(valor);

        $('#titulo_inserir').text('Editar Registro');
        $('#modalForm').modal('show');
    }


    function limparCampos() {
        $('#id').val('');
        $('#nome-bairro').val('');
        $('#valor-bairro').val('');
    }
</script>