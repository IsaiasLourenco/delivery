<?php
include('../../conexao.php');
$query = $pdo->query("SELECT * FROM config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$logo_rel = $res[0]['logo_rel'];
$nome_sistema = $res[0]['nome_sistema'];
$desenvolvedor = $res[0]['desenvolvedor'];
$site_dev = $res[0]['site_dev'];
$url_sistema = $res[0]['url_sistema'];
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$formatter = new IntlDateFormatter(
	'pt_BR',
	IntlDateFormatter::FULL,
	IntlDateFormatter::NONE,
	'America/Sao_Paulo',
	IntlDateFormatter::GREGORIAN,
	"EEEE, dd 'de' MMMM 'de' yyyy"
);
$data_hoje = $formatter->format(new DateTime());
$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));
if ($dataInicial == $dataFinal) {
	$texto_apuracao = 'APURADO EM ' . $dataInicialF;
} else if ($dataInicial == '1980-01-01') {
	$texto_apuracao = 'APURADO EM TODO O PERÍODO';
} else {
	$texto_apuracao = 'APURAÇÃO DE ' . $dataInicialF . ' ATÉ ' . $dataFinalF;
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Relatório de Contas</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css"
		rel="stylesheet"
		integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
		crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo $url_sistema ?>sistema/painel/css/rel_lucro.css">
</head>

<body>

	<div class="header-relatorio">
		<img src="<?php echo $url_sistema ?>img/<?php echo $logo_rel ?>" class="logo-rel">
		<div class="titulo-rel">
			<div class="nome-sistema"><?php echo $nome_sistema ?></div>
			<div class="titulo-principal">Demonstrativo de Lucro</div>
		</div>
		<div class="data-rel"><?php echo mb_strtoupper($data_hoje) ?></div>
	</div>
	<div class="mx-2">
		<section class="mg-tb-5">
			<div>
				<span class="texto-cabecalho"><?php echo $texto_apuracao ?></span>
			</div>
		</section>
		<?php
		$total_vendas = 0;
		$total_receber = 0;
		$total_pagar = 0;
		$total_compras = 0;
		$total_entradas = 0;
		$total_saidas = 0;
		$saldo_total = 0;
		?>
		<table class="table table-striped borda" cellpadding="6">
			<thead>
				<tr class="centro">
					<th scope="col">Vendas</th>
					<th scope="col">Recebimentos</th>
					<th scope="col">Despesas</th>
					<th scope="col">Compras</th>
				</tr>
			</thead>
			<tbody>

				<?php
				//totalizar os vendas 
				$query = $pdo->query("SELECT * FROM vendas WHERE data_pagamento >= '$dataInicial' AND data_pagamento 
									  <= '$dataFinal' AND pago = 'Sim' AND status_venda != 'Cancelado' ORDER BY data_pagamento ASC");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				$total_reg = @count($res);
				for ($i = 0; $i < $total_reg; $i++) {
					foreach ($res[$i] as $key => $value) {
					}

					$total_vendas += $res[$i]['valor_compra'];
				}
				//totalizar contas recebidas
				$query = $pdo->query("SELECT * FROM receber where data_pagamento >= '$dataInicial' and data_pagamento 
								 	  <= '$dataFinal' and tipo = 'Conta' and pago = 'Sim' ORDER BY data_pagamento asc");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				$total_reg = @count($res);
				for ($i = 0; $i < $total_reg; $i++) {
					foreach ($res[$i] as $key => $value) {
					}

					$total_receber += $res[$i]['valor'];
				}
				//totalizar contas despesas
				$query = $pdo->query("SELECT * FROM pagar WHERE data_pagamento >= '$dataInicial' AND data_pagamento 
									  <= '$dataFinal' AND tipo = 'Conta' AND pago = 'Sim' ORDER BY data_pagamento ASC");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				$total_reg = @count($res);
				for ($i = 0; $i < $total_reg; $i++) {
					foreach ($res[$i] as $key => $value) {
					}

					$total_pagar += $res[$i]['valor'];
				}
				//totalizar contas compras
				$query = $pdo->query("SELECT * FROM pagar WHERE data_pagamento >= '$dataInicial' AND data_pagamento 
								 	  <= '$dataFinal' AND tipo = 'Compra' AND pago = 'Sim' ORDER BY data_pagamento ASC");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				$total_reg = @count($res);
				for ($i = 0; $i < $total_reg; $i++) {
					foreach ($res[$i] as $key => $value) {
					}

					$total_compras += $res[$i]['valor'];
				}

				$total_vendasF = number_format($total_vendas, 2, ',', '.');
				$total_receberF = number_format($total_receber, 2, ',', '.');
				$total_pagarF = number_format($total_pagar, 2, ',', '.');
				$total_comprasF = number_format($total_compras, 2, ',', '.');
				$total_entradas = $total_vendas + $total_receber;
				$total_saidas = $total_pagar + $total_compras;
				$total_entradasF = number_format($total_entradas, 2, ',', '.');
				$total_saidasF = number_format($total_saidas, 2, ',', '.');
				$saldo_total = $total_entradas - $total_saidas;
				$saldo_totalF = number_format($saldo_total, 2, ',', '.');
				if ($saldo_total < 0) {
					$classe_saldo = 'text-danger';
					$classe_img = 'negativo.jpg';
				} else {
					$classe_saldo = 'text-success';
					$classe_img = 'positivo.jpg';
				}
				?>
				<tr class="centro">
					<td class="text-success">R$ <?php echo $total_vendasF ?></td>
					<td class="text-success">R$ <?php echo $total_receberF ?></td>
					<td class="text-danger">R$ <?php echo $total_pagarF ?></td>
					<td class="text-danger">R$ <?php echo $total_comprasF ?></td>
				</tr>
				<tr class="centro">
					<td class="bg-positivo" colspan="2" scope="col">Total de Entradas | Ganhos</td>
					<td class="bg-negativo" colspan="2" scope="col">Total de Saídas | Despesas</td>
				</tr>

				<tr class="centro">
					<td colspan="2" class="text-success"> R$ <?php echo $total_entradasF ?></td>
					<td colspan="2" class="text-danger"> R$ <?php echo $total_saidasF ?></td>
				</tr>

			</tbody>
		</table>
	</div>
	<div class="col-md-12 p-2">
		<div class="centro mg-r-20">
			<img src="<?php echo $url_sistema ?>img/<?php echo $classe_img ?>" width="100px">
			<span class="<?php echo $classe_saldo ?>">R$ <?php echo $saldo_totalF ?></span>
		</div>
	</div>
	<div class="cabecalho brd-bttm-azul">
	</div>
	<div class="footer-relatorio">
		<div class="footer-linha"><?php echo $nome_sistema ?></div>
		<div class="footer-linha">Desenvolvido por <?php echo $desenvolvedor ?></div>
		<div class="style-qr-code">
			<img src="<?php echo $url_sistema ?>img/QRcodeLinkingToVetor256.png" width="100" alt="QR Code para vetor256.com">
			<div class="style-text-qr-code">
				Acesse o site clicando com o botão direito no link e escolhendo "abrir em nova guia" ou escaneando o QR Code<br>
				<a href="<?php echo $site_dev ?>" class="cor-estilo-link-vetor" target="_blank"><?php echo $site_dev ?></a>
			</div>
		</div>
	</div>
</body>

</html>