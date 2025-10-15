<?php 
require_once("../../../conexao.php");
$tabela = 'vendas';
$data_hoje = date('Y-m-d');

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$status = '%'.@$_POST['status'].'%';

$total_vendas = 0;

$query = $pdo->query("SELECT * FROM $tabela 
							   WHERE data_pagamento >= '$dataInicial' 
							   AND data_pagamento <= '$dataFinal' 
							   AND pago = 'Sim' 
							   AND status_venda LIKE '$status'
							   ORDER BY data_pagamento ASC,
							   hora_pagamento DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table table-hover tabela-menor" id="tabela">
	<thead> 
		<tr> 
			<th class="centro">Cliente</th>	
			<th class="esc centro">Local Entrega</th> 	
			<th class="esc centro">Valor Entrega</th> 	
			<th class="esc centro">Valor</th> 	
			<th class="esc centro">Total Pago</th> 	
			<th class="esc centro">Troco</th>	
			<th class="esc centro">Forma PGTO</th> 
			<th class="esc centro">Data</th>	
			<th class="esc centro">Hora</th>
			<th class="centro">Ações</th>
		</tr> 
	</thead>

	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];	
	$id_cliente = $res[$i]['cliente'];
	$valor_compra = $res[$i]['valor_compra'];
	$valor_pago = $res[$i]['valor_pago'];
	$troco = $res[$i]['troco'];
	$data_pgto = $res[$i]['data_pagamento'];
	$hora_pgto = $res[$i]['hora_pagamento'];
	$status_venda = $res[$i]['status_venda'];
	$pago = $res[$i]['pago'];
	$obs = $res[$i]['obs'];
	$id_valor_entrega = $res[$i]['valor_entrega'];
	$tipo_pagamento = $res[$i]['tipo_pagamento'];
	$id_usuario_baixa = $res[$i]['usuario_baixa'];

	$queryValorEntrega = $pdo->query("SELECT * FROM bairros WHERE id = '$id_valor_entrega'");
	$resValorEntrega = $queryValorEntrega->fetchAll(PDO::FETCH_ASSOC);
	$totalValorEntrega = count($resValorEntrega);
	if ($totalValorEntrega > 0) {
		$nome_bairro = $resValorEntrega[0]['nome'];
		$valor_entrega = $resValorEntrega[0]['valor'];
	} else {
		$nome_bairro = 'Nenhum';
		$valor_entrega = 0;
	}
	
	$valor_compraF = 'R$ ' . number_format($valor_compra, 2, ',', '.');
	$valor_pagoF = 'R$ ' . number_format($valor_pago, 2, ',', '.');
	$trocoF = 'R$ ' . number_format($troco, 2, ',', '.');
	$valor_entregaF = 'R$ ' . number_format($valor_entrega, 2, ',', '.');
	$data_pgtoF = implode('/', array_reverse(explode('-', $data_pgto)));

	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$id_usuario_baixa'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_usuario_pgto = $res2[0]['nome'];
	}else{
		$nome_usuario_pgto = 'Nenhum!';
	}

	$queryCliente = $pdo->query("SELECT * FROM cliente WHERE id  = '$id_cliente'");
	$resCliente = $queryCliente->fetchAll(PDO::FETCH_ASSOC);
	$totalCliente = count($resCliente);
	if ($resCliente > 0) {
		$nome_cliente = $resCliente[0]['nome'];
	} else {
		$nome_cliente = 'Nenhum';
	}

	if ($status_venda == 'Finalizado') {
		$classe_alerta = 'text-verde';
		$total_vendas += $valor_compra;
		$classe_linha = '';
	} else if ($status_venda == 'Cancelado') {
		$classe_alerta = 'text-danger';
		$total_vendas += 0;
		$classe_linha = 'text-muted';
	} else {
		$classe_alerta = 'text-primary';
		$total_vendas += $valor_compra;
		$classe_linha = '';
	}

echo <<<HTML
<tr class="{$classe_linha}">
	<td class="centro"><i class="fa fa-square {$classe_alerta}"></i> {$nome_cliente}</td>
	<td class="esc centro">{$nome_bairro}</td>
	<td class="esc centro">{$valor_entregaF}</td>
	<td class="esc centro">{$valor_compraF}</td>
	<td class="esc centro">{$valor_pagoF}</td>
	<td class="esc centro">{$trocoF}</td>
	<td class="esc centro">{$tipo_pagamento}</td>
	<td class="esc centro">{$data_pgto}</td>
	<td class="esc centro">{$hora_pgto}</td>
	<td class="centro">
		
		<a href="#" onclick="mostrar('{$nome_cliente}', 
									 '{$valor_compraF}', 
									 '{$valor_pagoF}', 
									 '{$trocoF}', 
									 '{$data_pgtoF}', 
									 '{$hora_pgto}', 
									 '{$status_venda}', 
									 '{$pago}', 
									 '{$obs}', 
									 '{$nome_bairro}', 
									 '{$valor_entregaF}', 
									 '{$tipo_pagamento}', 
									 '{$nome_usuario_pgto}')" title="Ver Dados">
			<i class="fa fa-info-circle text-secondary"></i>
		</a>

		<li class="dropdown head-dpdn2" style="display: inline-block;">
			<a title="Cancelar Pedido" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<i class="fa fa-trash-o text-danger"></i>
			</a>

			<ul class="dropdown-menu" style="margin-left:-230px;">
				<li>
					<div class="notification_desc2">
						<p>Confirmar Cancelamento? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
					</div>
				</li>										
			</ul>
		</li>

	</td>
</tr>
HTML;

}

$total_vendasF = number_format($total_vendas, 2, ',', '.');

echo <<<HTML
</tbody>
<div class="centro texto-menor" id="mensagem-excluir"></div>
</table>

<br>	
<div class="right">Total Vendido: <span class="text-verde">R$ {$total_vendasF}</span> </div>

HTML;

}else{
	echo '<p class="centro texto-menor">Não possui nenhum registro Cadastrado!</p>';
}

?>

<script type="text/javascript">
	$(document).ready( function () {
    $('#tabela').DataTable({
    		"ordering": false,
			"stateSave": true
    	});
    $('#tabela_filter label input').focus();
} );
</script>

<script type="text/javascript">
	function mostrar(cliente, valor_compra, valor_pago, troco, data_pagamento, hora_pgto, status_venda, pago, obs, nome_bairro, valor_entrega, 
					 tipo_pagto, usuario_pagto){

		$('#cliente_dados').text(cliente);
		$('#valor_compra_dados').text(valor_compra);
		$('#valor_pago_dados').text(valor_pago);
		$('#troco_dados').text(troco);
		$('#data_pgto_dados').text(data_pagamento);
		$('#hora_pgto_dados').text(hora_pgto);
		$('#status_venda_dados').text(status_venda);
		$('#pago_dados').text(pago);
		$('#obs_dados').text(obs);
		$('#nome_bairro_dados').text(nome_bairro);
		$('#valor_entrega_dados').text(valor_entrega);
		$('#tipo_pagto_dados').text(tipo_pagto);
		$('#usuario_pagto_dados').text(usuario_pagto);

		$('#modalDados').modal('show');
	}
</script>