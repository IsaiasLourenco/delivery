<?php 
require_once("../../../conexao.php");
$tabela = 'usuarios';

$query = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total > 0) {
echo <<<HTML
    <table class="table table-hover table-sm table-responsive tabela-menor" id="tabela">
        <thead>
            <tr>
                <th class="centro">Nome</th>
                <th class="esc centro">Email</th>
                <th class="esc centro">Senha</th>
                <th class="esc centro">Nivel</th>
                <th class="esc centro">Foto</th>
                <th class="centro">Ações</th>
                <tr>
        </thead>
        <tbody>
HTML;

for ($i = 0; $i < $total; $i++) {
    foreach ($res[$i] as $key => $value) {}
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
        $senha = $res[$i]['senha'];
        $nivel = $res[$i]['nivel'];
        $ativo = $res[$i]['ativo'];
        $foto = $res[$i]['foto'];
        $data_cad = $res[$i]['data_cad'];

        if ($nivel == 'Administrador') {
            $senha = '*********';
        }
echo <<<HTML
<tr>
    <td class="centro">{$nome}</td>
    <td class="esc centro">{$email}</td>
    <td class="esc centro">{$senha}</td>
    <td class="esc centro">{$nivel}</td>
    <td class="esc centro"><img src="  images/perfil/{$foto}" class="listar-foto"></td>
    <td class="centro">
        <a onclick="editar('{$id}', '{$email}', '{$senha}', '{$nivel}', '{$foto}', '{$telefone}')", title="Editar Registro"><i class="fa fa-edit text-primary pointer"></i></a>
        <li class="dropdown head-dpdn2 d-il-b">
            <a title="Apagar Registro" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-trash text-danger pointer"></i></a>
            <ul class="dropdown-menu mg-l--23">
                <li>
                    <div class="notification_desc2">
                        <p>Confirmar Exclusão?<a href="#" onclick="apagar('{$id}')"><span class="text-danger"> Sim</span></a></p>
                    </div>
                </li>
            </ul>
        </li>
        <a onclick="info('{$id}', '{$email}', '{$senha}', '{$nivel}', '{$foto}', '{$telefone}')", title="Mostrar Mais"><i class="fa fa-info-circle text-primary pointer"></i></a>
        <a onclick="check('{$id}', '{$email}', '{$senha}', '{$nivel}', '{$foto}', '{$telefone}')", title="Desativar"><i class="fa fa-check-square text-success pointer"></i></a>
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