<?php
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'vendas';

$data_hoje = date('Y-m-d');
$data_ontem = date('Y-m-d', strtotime("-1 days", strtotime($data_hoje)));

$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_inicio_mes = $ano_atual . "-" . $mes_atual . "-01";

if ($mes_atual == '4' || $mes_atual == '6' || $mes_atual == '9' || $mes_atual == '11') {
	$dia_final_mes = '30';
} else if ($mes_atual == '2') {
	if (($ano_atual % 4 == 0 && $ano_atual % 100 != 0) || ($ano_atual % 400 == 0)) {
		$dia_final_mes = '29'; // Ano bissexto
	} else {
		$dia_final_mes = '28';
	}
} else {
	$dia_final_mes = '31';
}
$data_final_mes = $ano_atual . "-" . $mes_atual . "-" . $dia_final_mes;

?>

<div class="bs-example widget-shadow pddg-15">

	<div class="row">
		<div class="col-md-5 mg-b-5">
			<div class="esquerda mg-r-10">
				<span>
					<i title="Data Inicial" class="fa fa-calendar-o texto-menor"></i>
				</span>
			</div>
			<div class="esquerda mg-r-20">
				<input type="date"
					class="form-control texto-menor"
					name="data-inicial"
					id="data-inicial-caixa"
					value="<?php echo $data_hoje ?>"
					required>
			</div>

			<div class="esquerda mg-r-10">
				<span>
					<i title="Data Final" class="fa fa-calendar-o texto-menor"></i>
				</span>
			</div>
			<div class="esquerda mg-t-30">
				<input type="date"
					class="form-control texto-menor"
					name="data-final"
					id="data-final-caixa"
					value="<?php echo $data_hoje ?>"
					required>
			</div>
		</div>

		<div class="col-md-2 centro mg-t-5">
			<div class="texto-menor">
				<a title="Venda de Ontem"
					class="text-muted"
					href="#"
					onclick="valorData('<?php echo $data_ontem ?>', '<?php echo $data_ontem ?>')">
					<span>Ontem</span>
				</a> /
				<a title="Venda de Hoje"
					class="text-muted"
					href="#"
					onclick="valorData('<?php echo $data_hoje ?>', '<?php echo $data_hoje ?>')">
					<span>Hoje</span>
				</a> /
				<a title="Venda do Mês"
					class="text-muted"
					href="#"
					onclick="valorData('<?php echo $data_inicio_mes ?>', '<?php echo $data_final_mes ?>')">
					<span>Mês</span>
				</a>
			</div>
		</div>

		<div class="col-md-3 centro mg-t-5">
			<div class="texto-menor">
				<a title="Todas as Vendas"
					class="text-muted"
					href="#"
					onclick="buscarContas('')">
					<span>Todas</span>
				</a> /
				<a title="Vendas Pendentes"
					class="text-muted"
					href="#"
					onclick="buscarContas('Cancelado')">
					<span>Canceladas</span>
				</a> /
				<a title="Vendas Pagas"
					class="text-muted"
					href="#"
					onclick="buscarContas('Finalizado')">
					<span>Pagas</span>
				</a>
			</div>
		</div>

		<input type="hidden" id="buscar-contas">

	</div>

	<hr>
	<div id="listar">

	</div>

</div>

<!-- Modal Dados-->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="cliente_dados"></span></h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row br-btt">
					<div class="col-md-6">
						<span><strong>Valor da Compra : </strong></span>
						<span id="valor_compra_dados"></span>
					</div>
					<div class="col-md-6">
						<span><strong>Valor Pago: </strong></span>
						<span id="valor_pago_dados"></span>
					</div>
				</div>
				<div class="row br-btt">
					<div class="col-md-6">
						<span><strong>Troco: </strong></span>
						<span id="troco_dados"></span>
					</div>
					<div class="col-md-6">
						<span><strong>Data: </strong></span>
						<span id="data_pgto_dados"></span>
					</div>
				</div>
				<div class="row br-btt">
					<div class="col-md-6">
						<span><strong>Hora: </strong></span>
						<span id="hora_pgto_dados"></span>
					</div>
					<div class="col-md-6">
						<span><strong>Status: </strong></span>
						<span id="status_venda_dados"></span>
					</div>
				</div>
				<div class="row br-btt">
					<div class="col-md-6">
						<span><strong>Pago: </strong></span>
						<span id="pago_dados"></span>
					</div>
					<div class="col-md-6">
						<span><strong>Observações: </strong></span>
						<span id="obs_dados"></span>
					</div>
				</div>
				<div class="row br-btt">
					<div class="col-md-6">
						<span><strong>Bairro: </strong></span>
						<span id="nome_bairro_dados"></span>
					</div>
					<div class="col-md-6">
						<span><strong>Valor Entrega: </strong></span>
						<span id="valor_entrega_dados"></span>
					</div>
				</div>
				<div class="row br-btt">
					<div class="col-md-6">
						<span><strong>Pago com: </strong></span>
						<span id="tipo_pagto_dados"></span>
					</div>
					<div class="col-md-6">
						<span><strong>Usuário Baixa: </strong></span>
						<span id="usuario_pagto_dados"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 centro">
						<a id="link_mostrar" target="_blank" title="Clique para abrir o arquivo!">
							<img width="250px" id="target_mostrar">
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Variável Página -->
<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>
<script src="js/ajax.js"></script>

<!-- Select 2 -->
<script type="text/javascript">
	$(document).ready(function() {
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>

<!-- Inserir e Editar Contas -->
<script type="text/javascript">
	$("#form").submit(function() {
		event.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: 'paginas/' + pag + "/salvar.php",
			type: 'POST',
			data: formData,
			success: function(mensagem) {
				$('#mensagem').text('');
				$('#mensagem').removeClass('text-danger text-success')
				if (mensagem.trim() == "Salvo com Sucesso") {
					$('#btn-fechar').click();
					location.reload();
				} else {
					$('#mensagem').addClass('text-danger')
					$('#mensagem').text(mensagem)
				}
			},
			cache: false,
			contentType: false,
			processData: false,
		});
	});
</script>
<!-- Fim Inserir e Editar Contas -->

<!-- Carrega Imagem -->
<script type="text/javascript">
	function carregarImg() {
		var target = document.getElementById('target');
		var file = document.querySelector("#foto").files[0];
		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);
		if (resultado[1] === 'pdf') {
			$('#target').attr('src', "images/contas/pdf.png");
			return;
		}
		if (resultado[1] === 'rar' || resultado[1] === 'zip') {
			$('#target').attr('src', "images/contas/rar.png");
			return;
		}
		var reader = new FileReader();
		reader.onloadend = function() {
			target.src = reader.result;
		};
		if (file) {
			reader.readAsDataURL(file);
		} else {
			target.src = "";
		}
	}
</script>

<!-- Datas -->
<script type="text/javascript">
	function valorData(dataInicio, dataFinal) {
		$('#data-inicial-caixa').val(dataInicio);
		$('#data-final-caixa').val(dataFinal);
		listar();
	}
</script>

<!-- Busca Datas -->
<script type="text/javascript">
	$('#data-inicial-caixa').change(function() {
		//$('#tipo-busca').val('');
		listar();
	});

	$('#data-final-caixa').change(function() {
		//$('#tipo-busca').val('');
		listar();
	});
</script>

<!-- Listar por datas -->
<script type="text/javascript">
	function listar() {
		var dataInicial = $('#data-inicial-caixa').val();
		var dataFinal = $('#data-final-caixa').val();
		var status = $('#buscar-contas').val();
		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {
				dataInicial,
				dataFinal,
				status
			},
			dataType: "html",
			success: function(result) {
				$("#listar").html(result);
				$('#mensagem-excluir').text('');
			}
		});
	}
</script>

<!-- Busca contas -->
<script type="text/javascript">
	function buscarContas(status) {
		$('#buscar-contas').val(status);
		listar();
	}
</script>

<!-- Baixar Pagamento -->
<script type="text/javascript">
	function baixar(id) {
		$.ajax({
			url: 'paginas/' + pag + "/baixar.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "text",
			success: function(mensagem) {
				if (mensagem.trim() == "Baixado com Sucesso") {
					listar();
				} else {
					$('#mensagem-excluir').addClass('text-danger')
					$('#mensagem-excluir').text(mensagem)
				}
			},
		});
	}
</script>

<!-- Função para formatar o valor para moeda brasileira -->
<script>
	function formatarMoedaInput(input) {
		let valor = input.value.replace(/\D/g, ""); // só números
		valor = (valor / 100).toFixed(2) + ""; // duas casas decimais
		valor = valor.replace(".", ","); // vírgula como separador decimal
		valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // pontos como separadores de milhar
		input.value = "R$ " + valor;
	}

	// Aplicar nos inputs
	document.getElementById("valor").addEventListener("input", function() {
		formatarMoedaInput(this);
	});
</script>
<!-- Fim Função para formatar o valor para moeda brasileira -->