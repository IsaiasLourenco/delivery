<?php
require_once("../../../conexao.php");
$tabela = 'fornecedores_produtos';

$query = $pdo->query("SELECT fp.*, f.nome AS fornecedor, p.nome AS produto 
                      FROM $tabela fp 
                      JOIN fornecedores f ON fp.fornecedor_id = f.id 
                      JOIN produtos p ON fp.produto_id = p.id 
                      ORDER BY fp.id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);

if ($total > 0) {
    echo <<<HTML
    <h4 class="centro">Vínculos Produto x Fornecedor</h4>
    <table class="table table-hover table-sm table-responsive tabela-menor" id="tabela">
        <thead>
            <tr>
                <th class="centro">Produto</th>
                <th class="centro">Fornecedor</th>
                <th class="centro">Valor Compra</th>
                <th class="centro">Prazo</th>
                <th class="centro">Principal</th>
                <th class="centro">Ações</th>
            </tr>
        </thead>
        <tbody>
HTML;

    foreach ($res as $row) {
        $id = $row['id'];
        $produto = $row['produto'];
        $fornecedor = $row['fornecedor'];
        $valor = "R$ " . number_format($row['valor_compra'], 2, ',', '.');
        $prazo = $row['prazo_entrega'];
        $principal = $row['principal'] == 1 ? 'Sim' : 'Não';

        echo <<<HTML
<tr>
    <td class="centro">{$produto}</td>
    <td class="centro">{$fornecedor}</td>
    <td class="centro">{$valor}</td>
    <td class="centro">{$prazo}</td>
    <td class="centro">{$principal}</td>
    <td class="centro">
        <a onclick="editar('{$id}', 
                           '{$row['fornecedor_id']}', 
                           '{$row['produto_id']}', 
                           '{$valor}', 
                           '{$prazo}', 
                           '{$row['principal']}', 
                           '{$row['observacoes']}')" title="Editar">
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
    echo "Sem vínculos cadastrados!";
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