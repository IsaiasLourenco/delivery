<?php
require_once("../../../conexao.php");
$tabela = 'receber';
$data_hoje = date('Y-m-d');

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$status = '%' . @$_POST['status'] . '%';

$total_pago = 0;
$total_a_pagar = 0;

$query = $pdo->query("SELECT * FROM $tabela where data_vencimento >= '$dataInicial' AND data_vencimento <= '$dataFinal' AND pago LIKE '$status' ORDER BY pago ASC, data_vencimento ASC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {

	echo <<<HTML
	<table class="table table-hover tabela-menor" id="tabela">
		<thead> 
			<tr> 
				<th class="centro">Descrição</th>	
				<th class="esc centro">Valor</th> 	
				<th class="esc centro">Vencimento</th> 	
				<th class="esc centro">Data PGTO</th> 
				<th class="esc centro">Cliente</th>	
				<th class="esc centro">Fornecedor</th>	
				<th class="esc centro">Arquivo</th>	
				<th class="centro">Ações</th>
			</tr> 
		</thead> 

	<tbody>	
HTML;

	for ($i = 0; $i < $total_reg; $i++) {
		foreach ($res[$i] as $key => $value) {
		}
		$id = $res[$i]['id'];
		$descricao = $res[$i]['descricao'];
		$tipo = $res[$i]['tipo'];
		$valor = $res[$i]['valor'];
		$data_lanc = $res[$i]['data_lancamento'];
		$data_venc = $res[$i]['data_vencimento'];
		$data_pgto = $res[$i]['data_pagamento'];
		$usuario_lanc = $res[$i]['usuario_lancou'];
		$usuario_baixa = $res[$i]['usuario_baixa'];
		$foto = $res[$i]['foto'];
		$pessoa = $res[$i]['pessoa'];
		$pago = $res[$i]['pago'];
		$produto = $res[$i]['produto'];
		$quantidade = $res[$i]['quantidade'];
		$fornecedor = $res[$i]['fornecedor'];
		$produto = $res[$i]['produto'];
		$quantidade = $res[$i]['quantidade'];

		$valorF = 'R$' . number_format($valor, 2, ',', '.');
		$data_lancF = implode('/', array_reverse(explode('-', $data_lanc)));
		$data_pgtoF = implode('/', array_reverse(explode('-', $data_pgto)));
		$data_vencF = implode('/', array_reverse(explode('-', $data_venc)));
		$whats = '';
		$text = '';

		$query2 = $pdo->query("SELECT * FROM cliente where id = '$pessoa'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if ($total_reg2 > 0) {
			$nome_cliente = $res2[0]['nome'];
			$telefone_cliente = $res2[0]['telefone'];
			$chave_pix_cliente = $res2[0]['chave_pix'];
			$tipo_chave_cliente = $res2[0]['tipo_chave'];
			$classe_whats = '';
			$whats_cliente	 = '55' . preg_replace('/[ ()-]+/', '', $telefone_cliente);
			$whats_forn = '';
		} else {
			$nome_cliente = 'Nenhum!';
			$telefone_cliente = '';
			$classe_whats = 'ocultar';
			$chave_pix_cliente = '';
			$tipo_chave_cliente = '';
		}

		if ($fornecedor != 0) {
			$query2 = $pdo->query("SELECT * FROM fornecedores WHERE id = '$fornecedor'");
			$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			$total_reg2 = @count($res2);
			if ($total_reg2 > 0) {
				$nome_forn = $res2[0]['nome'];
				$telefone_forn = $res2[0]['telefone'];
				$chave_pix_forn = $res2[0]['chave_pix'];
				$tipo_chave_forn = $res2[0]['tipo_chave'];
				$classe_whats = '';
				$whats_forn = '55' . preg_replace('/[ ()-]+/', '', $telefone_forn);
				$whats_cliente = '';

				$query3 = $pdo->query("SELECT * FROM fornecedores_produtos WHERE fornecedor_id = '$fornecedor'");
				$res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
				if (count($res3) > 0) {
					$produto_id = $res3[0]['produto_id'];
					$query_prod = $pdo->query("SELECT * FROM produtos WHERE id = '$produto_id'");
					$res_prod = $query_prod->fetchAll(PDO::FETCH_ASSOC);
					$nome_produto = $res_prod[0]['nome'];
				} else {
					$nome_produto = ''; // ← sem produto vinculado
				}
			} else {
				$nome_forn = 'Nenhum!';
				$telefone_forn = '';
				$classe_whats = 'ocultar';
				$chave_pix_forn = '';
				$tipo_chave_forn = '';
				$nome_produto = '';
			}
		} else {
			$nome_forn = 'Nenhum!';
			$telefone_forn = '';
			$classe_whats = 'ocultar';
			$chave_pix_forn = '';
			$tipo_chave_forn = '';
			$nome_produto = ''; // ← limpa produto se não houver fornecedor
		}

		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_baixa'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if ($total_reg2 > 0) {
			$nome_usuario_pgto = $res2[0]['nome'];
		} else {
			$nome_usuario_pgto = 'Nenhum!';
		}

		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_lanc'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if ($total_reg2 > 0) {
			$nome_usuario_lanc = $res2[0]['nome'];
		} else {
			$nome_usuario_lanc = 'Sem Referência!';
		}

		if ($data_pgto == '0000-00-00') {
			$classe_alerta = 'text-danger';
			$data_pgtoF = 'Pendente';
			$visivel = '';
			$total_a_pagar += $valor;
		} else {
			$classe_alerta = 'text-verde';
			$visivel = 'ocultar';
			$total_pago += $valor;
		}

		//extensão do arquivo
		$ext = pathinfo($foto, PATHINFO_EXTENSION);
		if ($ext == 'pdf') {
			$tumb_arquivo = 'pdf.png';
		} else if ($ext == 'rar' || $ext == 'zip') {
			$tumb_arquivo = 'rar.png';
		} else {
			$tumb_arquivo = $foto;
		}

		if ($data_venc < $data_hoje and $pago != 'Sim') {
			$classe_debito = 'vermelho-escuro';
		} else {
			$classe_debito = '';
		}

		if ($nome_cliente == 'Nenhum!' and $nome_forn != 'Nenhum!') {
			$whats = $whats_forn;
			$chave = 'Pix Fornecedor : Tipo ' . $tipo_chave_forn . ' - Chave ' . $chave_pix_forn;
			$text = "Olá! Estamos cobrando o recebimento do seu boleto/notaFiscal via PIX no valor de R$ {$valorF}.";
			$text .= " Chave: Tipo {$tipo_chave_forn} - {$chave_pix_forn}.";
		} else if ($nome_forn == 'Nenhum!' and $nome_cliente != 'Nenhum!') {
			$whats = $whats_cliente;
			$chave = 'Pix Cliente : Tipo ' . $tipo_chave_cliente . ' - Chave ' . $chave_pix_cliente;
			$text = "Olá! Estamos cobrando o pagamento do boleto referente à {$descricao} via PIX no valor de R$ {$valorF}.";
			$text .= " Chave: Tipo {$tipo_chave_cliente} - {$chave_pix_cliente}.";
		} else {
			$chave = 'Nenhuma!';
			$text = "Olá! O pagamento foi processado, mas não foi possível identificar o destinatário.";
			$whats = '';
			$classe_whats = 'ocultar';
		}

		if (!empty($whats)) {
			$classe_whats = '';
		} else {
			$classe_whats = 'ocultar';
		}

		$text = urlencode($text);
		$link = "https://api.whatsapp.com/send?1=pt_BR&phone={$whats}&text={$text}";

		echo <<<HTML
	<tr class="{$classe_debito}">
		<td class="centro"><i class="fa fa-square {$classe_alerta} centro"></i> {$descricao}</td>
		<td class="esc centro">{$valorF}</td>
		<td class="esc centro">{$data_vencF}</td>
		<td class="esc centro">{$data_pgtoF}</td>
		<td class="esc centro">{$nome_cliente}</td>
		<td class="esc centro">{$nome_forn}</td>
		<td class="centro">
			<a  href="images/contas/{$foto}" 
				target="_blank">
				<img src="images/contas/{$tumb_arquivo}" 
			 		width="27px" 
			 		class="mr-2">
			</a>
		</td>

		<td>
			<a href="#" onclick="editar('{$id}',
    									'{$descricao}',
    									'{$pessoa}',
    									'{$valorF}',
    									'{$data_venc}',
    									'{$data_pgto}',
    									'{$foto}',
    									'{$produto}',
    									'{$quantidade}',
    									'{$fornecedor}')" title="Editar Dados">
    			<i class="fa fa-edit text-primary"></i>
			</a>

				<a href="#" onclick="mostrar('{$descricao}', 
  											 '{$valorF}', 
  											 '{$data_lancF}', 
  											 '{$data_vencF}', 
  											 '{$data_pgtoF}', 
  											 '{$nome_usuario_lanc}', 
  											 '{$nome_usuario_pgto}', 
  											 '{$nome_cliente}', 
  											 '{$telefone_cliente}', 
  											 '{$nome_forn}', 
  											 '{$telefone_forn}', 
  											 '{$nome_produto}', 
  											 '{$quantidade}', 
  											 '{$chave}', 
  											 '{$foto}', 
  											 '{$tumb_arquivo}')" title="Ver Dados">
  					<i class="fa fa-info-circle text-secondary"></i>
				</a>

			<li class="dropdown head-dpdn2 d-il-b">
				<a  href="#" 
					class="dropdown-toggle" 
					data-toggle="dropdown" 
					aria-expanded="false">
					<i class="fa fa-trash-o text-danger"></i>
				</a>

				<ul class="dropdown-menu mg-l--150">
					<li>
						<div class="notification_desc2">
							<p>Confirmar Exclusão? 
								<a  href="#" 
									onclick="excluir('{$id}')">
									<span class="text-danger">
										Sim
									</span>
								</a>
							</p>
						</div>
					</li>										
				</ul>
			</li>

			<li class="dropdown head-dpdn2" style="display: inline-block;">
				<a  title="Baixar Conta" 
					href="#" 
					class="dropdown-toggle {$visivel}" 
					data-toggle="dropdown" 
					aria-expanded="false">
					<i class="fa fa-check-square text-verde"></i>
				</a>

				<ul class="dropdown-menu" style="margin-left:-230px;">
					<li>
						<div class="notification_desc2">
							<p>Confirmar Baixa na Conta? 
								<a href="#" onclick="baixar('{$id}')">
									<span class="text-verde {$visivel}">
										Sim
									</span>
								</a>
							</p>
						</div>
					</li>										
				</ul>
			</li>

			<a href='{$link}' target='_blank' title='Abrir Whatsapp' class='{$classe_whats}'>
    		    <i class='fa fa-whatsapp text-verde'></i>
	      	</a>
	
		</td>
	</tr>
HTML;
	}
	$total_pagoF = number_format($total_pago, 2, ',', '.');
	$total_a_pagarF = number_format($total_a_pagar, 2, ',', '.');
	echo <<<HTML
	</tbody>
		<div class="centro texto-menor" id="mensagem-excluir"></div>
	</table>
<br>	

<div class="direita">Total Recebido: <span class="text-verde">R$ {$total_pagoF}</span> </div>
<div class="direita">Total à Receber: <span class="text-danger">R$ {$total_a_pagarF}</span> </div>

HTML;
} else {
	echo '<p class="texto-menor centro">Não possui nenhum registro Cadastrado!</p>';
}

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	});
</script>

<script type="text/javascript">
	function editar(id, descricao, pessoa_id, valor, data_venc, data_pgto, foto, produto_id, quantidade, fornecedor_id) {
		$('#id').val(id);
		$('#descricao').val(descricao);
		$('#pessoa').val(pessoa_id).change();
		$('#valor').val(valor);
		$('#data_venc').val(data_venc);
		$('#data_pgto').val(data_pgto);
		$('#fornecedor').val(fornecedor_id).change();
		$('#target').attr('src', 'images/contas/' + foto);

		if (fornecedor_id != "0" && fornecedor_id != "") {
			$('#bloco-produto').show();

			$.ajax({
				url: 'paginas/receber/buscar-produto.php',
				method: 'POST',
				data: {
					fornecedor: fornecedor_id
				},
				success: function(result) {
					$('#produto').html(result);
					// Aguarda 100ms pra garantir que o DOM atualizou
					setTimeout(function() {
						$('#produto').val(produto_id).change();
						$('#quantidade').val(quantidade);
					}, 100);


				}
			});
		} else {
			$('#bloco-produto').hide();
			$('#produto').val('').change();
			$('#quantidade').val('');
		}

		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
		$('#foto').val('');
	}

	function limparCampos() {
		$('#id').val('');
		$('#descricao').val('');
		$('#pessoa').val('');
		$('#valor').val('');
		$('#data_venc').val('<?= $data_hoje ?>');
		$('#data_pgto').val('');
		$('#produto').val('');
		$('#quantidade').val('');
		$('#fornecedor').val('');
		$('#foto').val('');

		$('#target').attr('src', 'images/contas/sem-foto.jpg');
	}
</script>

<script type="text/javascript">
	function mostrar(
		descricao,
		valor,
		data_lanc,
		data_venc,
		data_pgto,
		usuario_lanc,
		usuario_pgto,
		cliente,
		telefone_cliente,
		fornecedor,
		telefone_forn,
		produto,
		quantidade,
		chave_pix,
		foto,
		link
	) {
		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lanc_dados').text(data_lanc);
		$('#data_venc_dados').text(data_venc);
		$('#data_pgto_dados').text(data_pgto);
		$('#usuario_lanc_dados').text(usuario_lanc);
		$('#usuario_baixa_dados').text(usuario_pgto);
		$('#cliente_dados').text(cliente);
		$('#nome_forn_dados').text(fornecedor);
		$('#tel_forn_dados').text(telefone_forn);
		$('#produto_dados').text(produto);
		$('#quantidade_dados').text(quantidade);
		$('#chave_dados').text(chave_pix);

		$('#link_mostrar').attr('href', 'images/contas/' + link);
		$('#target_mostrar').attr('src', 'images/contas/' + foto);

		$('#modalDados').modal('show');
	}
</script>

<script type="text/javascript">
	function saida(id, nome, estoque) {

		$('#nome_saida').text(nome);
		$('#estoque_saida').val(estoque);
		$('#id_saida').val(id);

		$('#modalSaida').modal('show');
	}
</script>

<script type="text/javascript">
	function entrada(id, nome, estoque) {

		$('#nome_entrada').text(nome);
		$('#estoque_entrada').val(estoque);
		$('#id_entrada').val(id);

		$('#modalEntrada').modal('show');
	}
</script>