<?php
require_once("../../../conexao.php");
$tabela = 'cliente';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total > 0) {
    echo <<<HTML
    <h4 class="centro">Clientes</h4>
    <table class="table table-hover table-sm table-responsive tabela-menor" id="tabela">
        <thead>
            <tr>
                <th class="centro">Nome</th>
                <th class="esc centro">Email</th>   
                <th class="esc centro">CPF</th>   
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
        $cpf = $res[$i]['cpf'];
        $telefone = $res[$i]['telefone'];
        $cep = $res[$i]['cep'];
        $rua = $res[$i]['rua'];
        $numero = $res[$i]['numero'];
        $bairro = $res[$i]['bairro'];
        $cidade = $res[$i]['cidade'];
        $estado = $res[$i]['estado'];
        $data_cad = $res[$i]['data_cad'];

        $dataF = implode('/', array_reverse(explode('-', $data_cad)));

        echo <<<HTML
<tr>
    <td class="centro">{$nome}</td>
    <td class="esc centro">{$email}</td>
    <td class="esc centro">{$cpf}</td>
    <td class="esc centro">{$telefone}</td>
    <td class="centro">
        <a onclick="editar( '{$id}',
                            '{$nome}', 
                            '{$email}',
                            '{$cpf}', 
                            '{$telefone}',
                            '{$cep}',
                            '{$rua}',
                            '{$numero}',
                            '{$bairro}',
                            '{$cidade}',
                            '{$estado}',
                            '{$senha}')", title="Editar Registro">
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
        <a onclick="mostrar('{$nome}', 
                            '{$cpf}', 
                            '{$telefone}',
                            '{$cep}',
                            '{$rua}',
                            '{$numero}',
                            '{$bairro}',
                            '{$cidade}',
                            '{$estado}',
                            '{$dataF}')", title="Mostrar mais Dados">
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
    function editar(id, nome, email, cpf, telefone, cep, rua, numero, bairro, cidade, estado) {
        $('#id').val(id);
        $('#nome-cli').val(nome);
        $('#email-cli').val(email);
        $('#cpf-cli').val(cpf);
        $('#telefone-cli').val(telefone);
        $('#cep-cli').val(cep);
        $('#rua-cli').val(rua);
        $('#numero-cli').val(numero);
        $('#bairro-cli').val(bairro);
        $('#cidade-cli').val(cidade);
        $('#estado-cli').val(estado);

        $('#titulo_inserir').text('Editar Registro');
        $('#modalForm').modal('show');
    }

    function limparCampos() {
        $('#id').val('');
        $('#nome-cli').val('');
        $('#email-cli').val('');
        $('#cpf-cli').val('');
        $('#telefone-cli').val('');
        $('#cep-cli').val('');
        $('#rua-cli').val('');
        $('#numero-cli').val('');
        $('#bairro-cli').val('');
        $('#cidade-cli').val('');
        $('#estado-cli').val('');
    }

    function mostrar(nome, cpf, telefone, cep, rua, numero, bairro, cidade, estado, dataF) {
        $('#nome_dados-cli').text(nome);
        $('#cpf_dados-cli').text(cpf);
        $('#telefone_dados-cli').text(telefone);
        $('#cep_dados-cli').text(cep);
        $('#rua_dados-cli').text(rua);
        $('#numero_dados-cli').text(numero);
        $('#bairro_dados-cli').text(bairro);
        $('#cidade_dados-cli').text(cidade);
        $('#estado_dados-cli').text(estado);
        $('#data_dados-cli').text(dataF);

        $('#modalDados').modal('show');
    }
</script>