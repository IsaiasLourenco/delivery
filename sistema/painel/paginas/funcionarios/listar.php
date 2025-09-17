<?php
require_once("../../../conexao.php");
$tabela = 'funcionarios';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total > 0) {
    echo <<<HTML
    <table class="table table-hover table-sm table-responsive tabela-menor" id="tabela">
        <thead>
            <tr>
                <th class="centro">Nome</th>
                <th class="esc centro">Email</th>
                <th class="esc centro">Ativo</th>
                <th class="esc centro">Nivel</th>
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
        $email = $res[$i]['email'];
        $cpf = $res[$i]['cpf'];
        $telefone = $res[$i]['telefone'];
        $cep = $res[$i]['cep'];
        $rua = $res[$i]['rua'];
        $numero = $res[$i]['numero'];
        $bairro = $res[$i]['bairro'];
        $cidade = $res[$i]['cidade'];
        $estado = $res[$i]['estado'];
        $nivel = $res[$i]['nivel'];
        $ativo = $res[$i]['ativo'];
        $foto = $res[$i]['foto'];
        $data_cad = $res[$i]['data_cad'];

        $queryNivel = $pdo->query("SELECT * FROM cargos WHERE id = '$nivel'");
        $resNivel = $queryNivel->fetchAll(PDO::FETCH_ASSOC);
        $nomeNivel = $resNivel[0]['nome'];

        $dataF = implode('/', array_reverse(explode('-', $data_cad)));

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
<tr class="{$classe_linha}">
    <td class="centro">{$nome}</td>
    <td class="esc centro">{$email}</td>
    <td class="esc centro">{$ativo}</td>
    <td class="esc centro">{$nomeNivel}</td>
    <td class="esc centro"><img src="images/perfil/{$foto}" class="listar-foto"></td>
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
                            '{$ativo}', 
                            '{$nivel}',
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
        <a onclick="mostrar('{$nome}', 
                            '{$cpf}', 
                            '{$telefone}',
                            '{$cep}',
                            '{$rua}',
                            '{$numero}',
                            '{$bairro}',
                            '{$cidade}',
                            '{$estado}',
                            '{$dataF}',
                            '{$foto}')", title="Mostrar mais Dados">
            <i class="fa fa-info-circle text-secondary pointer"></i>
        </a>
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
    function editar(id, nome, email, cpf, telefone, cep, rua, numero, bairro, cidade, estado, ativo, nivel, foto) {
        $('#id-funcionario').val(id);
        $('#nome-funcionario').val(nome);
        $('#email-funcionario').val(email);
        $('#cpf-funcionario').val(cpf);
        $('#telefone-funcionario').val(telefone);
        $('#cep-funcionario').val(cep);
        $('#rua-funcionario').val(rua);
        $('#numero-funcionario').val(numero);
        $('#bairro-funcionario').val(bairro);
        $('#cidade-funcionario').val(cidade);
        $('#estado-funcionario').val(estado);
        $('#ativo-funcionario').val(ativo).change();
        $('#nivel-funcionario').val(nivel).change();

        $('#titulo_inserir').text('Editar Registro');
        $('#modalForm').modal('show');
        $('#foto-funcionario').val('');
        $('#target-funcionario').attr('src', 'images/perfil/' + foto);
    }

    function limparCampos() {
        $('#id-funcionario').val('');
        $('#nome-funcionario').val('');
        $('#email-funcionario').val('');
        $('#cpf-funcionario').val('');
        $('#telefone-funcionario').val('');
        $('#cep-funcionario').val('');
        $('#rua-funcionario').val('');
        $('#numero-funcionario').val('');
        $('#bairro-funcionario').val('');
        $('#cidade-funcionario').val('');
        $('#estado-funcionario').val('');
        $('#target-funcionario').attr('src', 'images/perfil/sem-foto.jpg')
    }

    function mostrar(nome, cpf, telefone, cep, rua, numero, bairro, cidade, estado, dataF, foto) {
        $('#nome_dados').text(nome);
        $('#cpf_dados').text(cpf);
        $('#telefone_dados').text(telefone);
        $('#cep_dados').text(cep);
        $('#rua_dados').text(rua);
        $('#numero_dados').text(numero);
        $('#bairro_dados').text(bairro);
        $('#cidade_dados').text(cidade);
        $('#estado_dados').text(estado);
        $('#data_dados').text(dataF);

        $('#modalDados').modal('show');
        $('#target_mostrar').attr('src', 'images/perfil/' + foto);
    }
</script>