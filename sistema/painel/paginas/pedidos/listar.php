<?php
require_once("../../../conexao.php");

$tabela = 'vendas';
$data_hoje = date('Y-m-d');

$status = '%' . @$_POST['status'] . '%';
$ultimo_pedido = @$_POST['ultimo_pedido'];

$total_vendas = 0;

$queryIniciado = $pdo->query("SELECT * FROM $tabela
							  WHERE data_pagamento = curDate()
							  AND status_venda = 'Iniciado'");
$resIniciado = $queryIniciado->fetchAll(PDO::FETCH_ASSOC);
$total_iniciado = @count($resIniciado);

$queryPreparando = $pdo->query("SELECT * FROM $tabela
							  WHERE data_pagamento = curDate()
							  AND status_venda = 'Preparando'");
$resPreparando = $queryPreparando->fetchAll(PDO::FETCH_ASSOC);
$total_preparando = @count($resPreparando);

$queryEmRota = $pdo->query("SELECT * FROM $tabela
							  WHERE data_pagamento = curDate()
							  AND status_venda = 'Em Rota'");
$resEmRota = $queryEmRota->fetchAll(PDO::FETCH_ASSOC);
$total_em_rota = @count($resEmRota);

$queryId = $pdo->query("SELECT * FROM $tabela
							  WHERE data_pagamento = curDate()
							  AND status_venda != 'Finalizado'
							  AND status_venda !='Cancelado'
							  ORDER BY id DESC LIMIT 1");
$resId = $queryId->fetchAll(PDO::FETCH_ASSOC);
@$ultimo_id = $resId[0]['id'];
if ($ultimo_pedido < $ultimo_id AND $ultimo_pedido != "") {
	echo '<audio autoplay="true">
		  	<source src="../../img/audio.mp3" type="audio/mpeg"/>
		  </audio>';
}

$queryPedidos = $pdo->query("SELECT * FROM $tabela
							  WHERE data_pagamento = curDate()
							  AND status_venda != 'Finalizado'
							  AND status_venda !='Cancelado'");
$resPedidos = $queryPedidos->fetchAll(PDO::FETCH_ASSOC);
$total_pedidos = @count($resPedidos);

$query = $pdo->query("SELECT * FROM $tabela 
							   WHERE data_pagamento = curDate()
							   AND status_venda LIKE '$status'
							   AND status_venda != 'Finalizado'
							   AND status_venda != 'Cancelado'
							   ORDER BY hora_pagamento ASC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {

	$titulo_pedidos = 'Pedidos';

	if (@$_POST['status'] == 'Iniciado') {
		$titulo_pedidos = 'Pedidos Iniciados';
	} else if (@$_POST['status'] == 'Preparando') {
		$titulo_pedidos = 'Pedidos sendo Preparados';
	} else if (@$_POST['status'] == 'Em Rota') {
		$titulo_pedidos = 'Pedidos em Rota de Entrega';
	}
echo "<script>console.log('ultimo_pedido: $ultimo_pedido | ultimo_id: $ultimo_id');</script>";
	echo <<<HTML
	<h4 class="centro">{$titulo_pedidos}</h4>
	<table class="table table-hover tabela-menor" id="tabela">
	<thead> 
		<tr> 
			<th class="centro">Pedido</th>	
			<th class="centro">Cliente</th>	
			<th class="esc centro">Local Entrega</th> 	
			<th class="esc centro">Entrega</th> 	
			<th class="esc centro">Total</th> 	
			<th class="esc centro">Pago</th> 	
			<th class="esc centro">Troco</th>	
			<th class="esc centro">Forma PGTO</th> 
			<th class="esc centro">Status</th>	
			<th class="esc centro">Hora</th>
			<th class="centro">Ações</th>
		</tr> 
	</thead>

	<tbody>	
HTML;

	for ($i = 0; $i < $total_reg; $i++) {
		foreach ($res[$i] as $key => $value) {
		}
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
		$idF = str_pad($id, 2, '0', STR_PAD_LEFT);

		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$id_usuario_baixa'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if ($total_reg2 > 0) {
			$nome_usuario_pgto = $res2[0]['nome'];
		} else {
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

		if ($status_venda == 'Iniciado') {
			$classe_alerta = 'text-primary';
			$total_vendas += $valor_compra;
			$titulo_link = 'Mudar para Preparando';
			$cor_seta = 'text-preparando';
			$acao = 'Preparando';
		} else if ($status_venda == 'Preparando') {
			$classe_alerta = 'text-preparando';
			$total_vendas += 0;
			$titulo_link = 'Mudar para Em Rota';
			$cor_seta = 'text-emRota';
			$acao = 'Em Rota';
		} else {
			$classe_alerta = 'text-emRota';
			$total_vendas += $valor_compra;
			$titulo_link = 'Mudar para Finalizado';
			$cor_seta = 'text-fim';
			$acao = 'Finalizado';
		}

		if ($pago == 'Sim') {
			$classe_excluir = 'ocultar';
		} else {
			$classe_excluir = '';
		}

		if ($obs != '') {
			$classe_info = 'text-preparando';
		} else {
			$classe_info = 'text-secondary';
		}

		echo <<<HTML
<tr>
	<td class="centro">
		<i class="fa fa-square {$classe_alerta}"></i> 
		<strong>{$idF}</strong>
	</td>
	<td class="centro">{$nome_cliente}</td>
	<td class="esc centro">{$nome_bairro}</td>
	<td class="esc centro">{$valor_entregaF}</td>
	<td class="esc centro">{$valor_compraF}</td>
	<td class="esc centro">{$valor_pagoF}</td>
	<td class="esc centro">{$trocoF}</td>
	<td class="esc centro">{$tipo_pagamento}</td>
	<td class="esc centro">
		<a title="{$titulo_link}" href="#" onclick="ativar('{$id}', '{$acao}')">
			{$status_venda}
			<i class="fa-solid fa-circle-right {$cor_seta}"></i>
		</a>
	</td>
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
			<i class="fa fa-info-circle {$classe_info}"></i>
		</a>

		<li class="dropdown head-dpdn2 inl-blck">
			<a title="Cancelar Pedido" href="#" class="dropdown-toggle {$classe_excluir}" data-toggle="dropdown" aria-expanded="false">
				<i class="fa-solid fa-rectangle-xmark text-danger"></i>
			</a>

			<ul class="dropdown-menu mg-l--230">
				<li>
					<div class="notification_desc2">
						<p>Confirmar Cancelamento? 
							<a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a>
						</p>
					</div>
				</li>										
			</ul>
		</li>

		<li class="dropdown head-dpdn2 inl-blck">
			<a title="Baixar Venda" href="#" class="dropdown-toggle {$classe_excluir}" data-toggle="dropdown" aria-expanded="false">
				<i class="fa fa-check-square texto-verde"></i>
			</a>
			<ul class="dropdown-menu mg-l--230">
				<li>
					<div class="notification_desc2">
						<p>Confirmar Pagamento? 
							<a href="#" onclick="baixar('{$id}')"><span class="texto-verde">Sim</span></a>
						</p>
					</div>
				</li>										
			</ul>
		</li>

	</td>
</tr>
HTML;
	}

	$total_regF = str_pad($total_reg, 2, '0', STR_PAD_LEFT);
	$total_pedidosF = str_pad($total_pedidos, 2, '0', STR_PAD_LEFT);
	$total_iniciadoF = str_pad($total_iniciado, 2, '0', STR_PAD_LEFT);
	$total_preparandoF = str_pad($total_preparando, 2, '0', STR_PAD_LEFT);
	$total_em_rotaF = str_pad($total_em_rota, 2, '0', STR_PAD_LEFT);

	echo <<<HTML
</tbody>
<div class="centro texto-menor" id="mensagem-excluir"></div>
</table>

<br>	
<div class="right">Total de Pedidos: <span> {$total_regF}</span> </div>
<input type="hidden" id="novo_id_pedido" value="{$ultimo_id}">
HTML;
} else {
	echo '<p class="centro texto-menor">Não possui nenhum registro Cadastrado!</p>';
}

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#todos_pedidos').text("<?=$total_pedidosF?>");
		$('#iniciado').text("<?=$total_iniciadoF?>");
		$('#preparando').text("<?=$total_preparandoF?>");
		$('#em_rota').text("<?=$total_em_rotaF?>");
		$('#id_pedido').val("<?=$ultimo_id?>");
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	});
</script>

<script type="text/javascript">
	function mostrar(cliente, valor_compra, valor_pago, troco, data_pagamento, hora_pgto, status_venda, pago, obs, nome_bairro, valor_entrega,
		tipo_pagto, usuario_pagto) {

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