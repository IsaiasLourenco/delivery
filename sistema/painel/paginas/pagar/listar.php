<?php
require_once("../../../conexao.php");
$tabela = 'pagar';
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
	<h4 class="centro">Contas à Pagar</h4>
	<table class="table table-hover tabela-menor" id="tabela">
		<thead> 
			<tr> 
				<th class="centro">Descrição</th>	
				<th class="esc centro">Valor</th> 	
				<th class="esc centro">Vencimento</th> 	
				<th class="esc centro">Data PGTO</th> 
				<th class="esc centro">Funcionário</th>
				<th class="esc centri">Fornecedor</th>	
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
		$funcionario = $res[$i]['funcionario'];
		$cliente = $res[$i]['cliente'];

		$valorF = 'R$' . number_format($valor, 2, ',', '.');
		$data_lancF = implode('/', array_reverse(explode('-', $data_lanc)));
		$data_pgtoF = implode('/', array_reverse(explode('-', $data_pgto)));
		$data_vencF = implode('/', array_reverse(explode('-', $data_venc)));
		$whats = '';
		$text = '';

		$query2 = $pdo->query("SELECT * FROM fornecedores where id = '$pessoa'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if ($total_reg2 > 0) {
			$nome_pessoa = $res2[0]['nome'];
			$telefone_forn = $res2[0]['telefone'];
			$chave_pix_forn = $res2[0]['chave_pix'];
			$tipo_chave_forn = $res2[0]['tipo_chave'];
			$classe_whats = '';
			$whats_forn	 = '55' . preg_replace('/[ ()-]+/', '', $telefone_forn);
			$whats_func = '';
		} else {
			$nome_pessoa = 'Nenhum!';
			$telefone_pessoa = '';
			$classe_whats = 'ocultar';
			$chave_pix_forn = '';
			$tipo_chave_forn = '';
		}

		$query2 = $pdo->query("SELECT * FROM funcionarios where id = '$funcionario'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if ($total_reg2 > 0) {
			$nome_func = $res2[0]['nome'];
			$telefone_func = $res2[0]['telefone'];
			$chave_pix_func = $res2[0]['chave_pix'];
			$tipo_chave_func = $res2[0]['tipo_chave'];
			$classe_whats = '';
			$whats_forn = '';
			$whats_func = '55' . preg_replace('/[ ()-]+/', '', $telefone_func);
		} else {
			$nome_func = 'Nenhum!';
			$telefone_func = '';
			$classe_whats = 'ocultar';
			$chave_pix_func = '';
			$tipo_chave_func = '';
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

		if ($nome_pessoa == 'Nenhum!' and $nome_func != 'Nenhum!') {
			$whats = $whats_func;
			$chave = 'Pix Funcionário : Tipo ' . $tipo_chave_func . ' - Chave ' . $chave_pix_func;
			$text = "Olá! Estamos fazendo o pagamento do seu salário via PIX no valor de R$ {$valorF}.";
			$text .= " Chave: Tipo {$tipo_chave_func} - {$chave_pix_func}.";
		} else if ($nome_func == 'Nenhum!' and $nome_pessoa != 'Nenhum!') {
			$whats = $whats_forn;
			$chave = 'Pix Fornecedor : Tipo ' . $tipo_chave_forn . ' - Chave ' . $chave_pix_forn;
			$text = "Olá! Estamos fazendo o pagamento do boleto referente à {$descricao} via PIX no valor de R$ {$valorF}.";
			$text .= " Chave: Tipo {$tipo_chave_forn} - {$chave_pix_forn}.";
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
		<td><i class="fa fa-square {$classe_alerta} centro"></i> {$descricao}</td>
		<td class="esc centro">{$valorF}</td>
		<td class="esc centro">{$data_vencF}</td>
		<td class="esc centro">{$data_pgtoF}</td>
		<td class="esc centro">{$nome_func}</td>
		<td class="esc centro">{$nome_pessoa}</td>
		<td class="centro">
			<a  href="images/contas/{$foto}" 
				target="_blank">
				<img src="images/contas/{$tumb_arquivo}" 
			 		width="27px" 
			 		class="mr-2">
			</a>
		</td>

		<td>
			<a  href="#" 
				onclick="editar('{$id}',
								'{$descricao}', 
								'{$pessoa}', 
								'{$valorF}', 
								'{$data_venc}', 
								'{$data_pgto}', 
								'{$tumb_arquivo}', 
								'{$funcionario}')" title="Editar Dados">
				<i class="fa fa-edit text-primary"></i>
			</a>

			<a  href="#" 
				onclick="mostrar('{$descricao}', 
						 		'{$valorF}', 
						 		'{$data_lancF}', 
						 		'{$data_vencF}',  
						 		'{$data_pgtoF}', 
						 		'{$nome_usuario_lanc}', 
						 		'{$nome_usuario_pgto}', 
						 		'{$tumb_arquivo}', 
						 '{$nome_pessoa}', 
						 '{$foto}', 
						 '{$nome_func}', 
						 '{$telefone_func}', 
						 '{$chave}')" title="Ver Dados">
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
    		    <i class='fa-brands fa-whatsapp text-verde'></i>
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

<div class="direita">Total Pago: <span class="text-verde">R$ {$total_pagoF}</span> </div>
<div class="direita">Total à Pagar: <span class="text-danger">R$ {$total_a_pagarF}</span> </div>

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
	function editar(id, descricao, pessoa, valor, data_venc, data_pgto, foto, func) {
		$('#id').val(id);
		$('#descricao').val(descricao);
		$('#pessoa').val(pessoa).change();
		$('#valor').val(valor);
		$('#data_venc').val(data_venc);
		$('#data_pgto').val(data_pgto);
		$('#funcionario').val(func).change();

		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
		$('#foto').val('');
		$('#target').attr('src', 'images/contas/' + foto);
	}

	function limparCampos() {
		$('#id').val('');
		$('#descricao').val('');
		$('#valor').val('');
		$('#data_pgto').val('');
		$('#data_venc').val('<?= $data_hoje ?>');
		$('#foto').val('');

		$('#target').attr('src', 'images/contas/sem-foto.jpg');
	}
</script>

<script type="text/javascript">
	function mostrar(descricao, valor, data_lanc, data_venc, data_pgto, usuario_lanc, usuario_pgto, foto, pessoa, link, nome_func, tel_func, chave) {

		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lanc_dados').text(data_lanc);
		$('#data_venc_dados').text(data_venc);
		$('#data_pgto_dados').text(data_pgto);
		$('#usuario_lanc_dados').text(usuario_lanc);
		$('#usuario_baixa_dados').text(usuario_pgto);
		$('#pessoa_dados').text(pessoa);
		$('#nome_func_dados').text(nome_func);
		$('#tel_func_dados').text(tel_func);
		$('#chave_dados').text(chave);

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