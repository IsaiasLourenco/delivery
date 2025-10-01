<?php
require_once("../../../conexao.php");
$tabela = 'fornecedores';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total > 0) {
    echo <<<HTML
    <h4 class="centro">Fornecedores</h4>
    <table class="table table-hover table-sm table-responsive tabela-menor" id="tabela">
        <thead>
            <tr>
                <th class="centro">Nome</th>
                <th class="esc centro">Email</th>   
                <th class="esc centro">CNPJ</th>   
                <th class="esc centro">Telefone</th>   
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
        $email = $res[$i]['email'];
        $cnpj = $res[$i]['cnpj'];
        $telefone = $res[$i]['telefone'];
        $cep = $res[$i]['cep'];
        $rua = $res[$i]['rua'];
        $numero = $res[$i]['numero'];
        $bairro = $res[$i]['bairro'];
        $cidade = $res[$i]['cidade'];
        $estado = $res[$i]['estado'];
        $data_cad = $res[$i]['data_cad'];
        $tipo_chave = $res[$i]['tipo_chave'];
        $chave_pix = $res[$i]['chave_pix'];

        $dataF = implode('/', array_reverse(explode('-', $data_cad)));

        echo <<<HTML
<tr>
    <td class="centro">{$nome}</td>
    <td class="esc centro">{$email}</td>
    <td class="esc centro">{$cnpj}</td>
    <td class="esc centro">{$telefone}</td>
    <td class="centro">
        <a onclick="editar( '{$id}',
                            '{$nome}', 
                            '{$email}',
                            '{$cnpj}', 
                            '{$telefone}',
                            '{$cep}',
                            '{$rua}',
                            '{$numero}',
                            '{$bairro}',
                            '{$cidade}',
                            '{$estado}',
                            '{$tipo_chave}',
                            '{$chave_pix}')", title="Editar Registro">
            <i class="fa fa-edit text-primary pointer"></i>
        </a>
        <li class="dropdown head-dpdn2 d-il-b">
            <a title="Excluir Registro" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-trash text-danger pointer"></i></a>
            <ul class="dropdown-menu mg-l--230">
                <li>
                    <div class="notification_desc2">
                        <p>Confirmar Exclusão?<a href="#" onclick="excluir('{$id}')"><span class="text-danger"> Sim</span></a></p>
                    </div>
                </li>
            </ul>
        </li>
        <a onclick="mostrar('{$nome}', 
                            '{$cnpj}', 
                            '{$telefone}',
                            '{$cep}',
                            '{$rua}',
                            '{$numero}',
                            '{$bairro}',
                            '{$cidade}',
                            '{$estado}',
                            '{$dataF}',
                            '{$tipo_chave}',
                            '{$chave_pix}')", title="Mostrar mais Dados">
            <i class="fa fa-info-circle text-secondary pointer"></i>
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
    function editar(id, nome, email, cnpj, telefone, cep, rua, numero, bairro, cidade, estado, tipo_chave, chave_pix) {
        $('#id').val(id);
        $('#nome-for').val(nome);
        $('#email-for').val(email);
        $('#cnpj-for').val(cnpj);
        $('#telefone-for').val(telefone);
        $('#cep-for').val(cep);
        $('#rua-for').val(rua);
        $('#numero-for').val(numero);
        $('#bairro-for').val(bairro);
        $('#cidade-for').val(cidade);
        $('#estado-for').val(estado);
        $('#tipo_chave').val(tipo_chave).change();
        $('#chave_pix').val(chave_pix);

        $('#titulo_inserir').text('Editar Registro');
        $('#modalForm').modal('show');
    }

    function limparCampos() {
        $('#id').val('');
        $('#nome-for').val('');
        $('#email-for').val('');
        $('#cnpj-for').val('');
        $('#telefone-for').val('');
        $('#cep-for').val('');
        $('#rua-for').val('');
        $('#numero-for').val('');
        $('#bairro-for').val('');
        $('#cidade-for').val('');
        $('#estado-for').val('');
        $('#chave_pix').val('');
    }

    function mostrar(nome, cnpj, telefone, cep, rua, numero, bairro, cidade, estado, dataF, tipo_chave, chave_pix) {
        $('#nome_dados-for').text(nome);
        $('#cnpj_dados-for').text(cnpj);
        $('#telefone_dados-for').text(telefone);
        $('#cep_dados-for').text(cep);
        $('#rua_dados-for').text(rua);
        $('#numero_dados-for').text(numero);
        $('#bairro_dados-for').text(bairro);
        $('#cidade_dados-for').text(cidade);
        $('#estado_dados-for').text(estado);
        $('#data_dados-for').text(dataF);
        $('#tipo_chave-for').text(tipo_chave);
        $('#chave_pix-for').text(chave_pix);

        $('#modalDados').modal('show');
    }
</script>