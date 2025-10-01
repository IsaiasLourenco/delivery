<?php
@session_start();
require_once("verificar.php");
require_once("../conexao.php");
$pag = 'receber';
//verificar se ele tem a permissão de estar nessa página
// if(@$pagar == 'ocultar'){
//     echo "<script>window.location='../index.php'</script>";
//     exit();
// }
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

<a type="button" class="btn btn-dark" onclick="inserir()">Nova Conta
	<i class="fa fa-plus" aria-hidden="true"></i>
</a>
<div class="bs-example widget-shadow pddg-15">
	<div class="row">
		<div class="col-md-6 mg-b-5">
			<div class="esquerda mg-r-10">
				<span>
					<i title="Data de Vencimento Inicial" class="fa fa-calendar-o texto-menor"></i>
				</span>
			</div>
			<div class="esquerda mg-r-20">
				<input type="date"
					class="form-control texto-menor"
					name="data-inicial"
					id="data-inicial-caixa"
					value="<?php echo $data_inicio_mes ?>"
					required>
			</div>
			<div class="esquerda mg-r-10">
				<span>
					<i title="Data de Vencimento Final" class="fa fa-calendar-o texto-menor"></i>
				</span>
			</div>
			<div class="esquerda mg-r-30">
				<input type="date"
					class="form-control texto-menor"
					name="data-final"
					id="data-final-caixa"
					value="<?php echo $data_final_mes ?>"
					required>
			</div>
		</div>
		<div class="col-md-2 centro mg-t-5">
			<div class="texto-menor">
				<a title="Conta de Ontem"
					class="text-muted"
					href="#"
					onclick="valorData('<?php echo $data_ontem ?>', '<?php echo $data_ontem ?>')">
					<span>Ontem</span>
				</a> /
				<a title="Conta de Hoje"
					class="text-muted"
					href="#"
					onclick="valorData('<?php echo $data_hoje ?>', '<?php echo $data_hoje ?>')">
					<span>Hoje</span>
				</a> /
				<a title="Conta do Mês"
					class="text-muted"
					href="#"
					onclick="valorData('<?php echo $data_inicio_mes ?>', '<?php echo $data_final_mes ?>')">
					<span>Mês</span>
				</a>
			</div>
		</div>
		<div class="col-md-2 centro mg-t-5">
			<div class="texto-menor">
				<a title="Todas as Contas"
					class="text-muted"
					href="#"
					onclick="buscarContas('')">
					<span>Todas</span>
				</a> /
				<a title="Contas Pendentes"
					class="text-muted"
					href="#"
					onclick="buscarContas('Não')">
					<span>Pend</span>
				</a> /
				<a title="Contas Pagas"
					class="text-muted"
					href="#"
					onclick="buscarContas('Sim')">
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

<!-- Modal Inserir-->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close mg-t--20" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="descricao">Descrição</label>
								<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição da Conta">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="pessoa">Cliente</label>
								<select class="form-control sel2 wd-100" id="pessoa" name="pessoa">
									<?php
									$query = $pdo->query("SELECT * FROM cliente ORDER BY nome ASC");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									echo '<option value="0">Selecione um Cliente</option>';
									if ($total_reg > 0) {
										for ($i = 0; $i < $total_reg; $i++) {
											foreach ($res[$i] as $key => $value) {
											}
											echo '<option value="' . $res[$i]['id'] . '">' . $res[$i]['nome'] . '</option>';
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="fornecedor">Fornecedor</label>
								<select class="form-control sel2 wd-100" id="fornecedor" name="fornecedor">
									<?php
									$query = $pdo->query("SELECT * FROM fornecedores ORDER BY nome ASC");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									echo '<option value="0">Selecione um Fornecedor</option>';
									if ($total_reg > 0) {
										for ($i = 0; $i < $total_reg; $i++) {
											foreach ($res[$i] as $key => $value) {
											}
											echo '<option value="' . $res[$i]['id'] . '">' . $res[$i]['nome'] . '</option>';
										}
									}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="row" id="bloco-produto" style="display: none;">
						<div class="col-md-8">
							<div class="form-group">
								<label for="produto">Produto</label>
								<select class="form-control sel2 wd-100" id="produto" name="produto">
									<option value="">Selecione um Produto</option>
									<!-- Produtos serão carregados via AJAX -->
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="quantidade">Quantidade</label>
								<input type="number" class="form-control" id="quantidade" name="quantidade" min="1" placeholder="Qtd">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="valor">Valor</label>
								<input type="text" class="form-control" id="valor" name="valor" placeholder="Valor" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="vencimento">Vencimento</label>
								<input type="date" class="form-control" id="data_venc" name="data_venc" value="<?php echo $data_hoje ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="data_pgto">Pago Em</label>
								<input type="date" class="form-control" id="data_pgto" name="data_pgto">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Arquivo</label>
								<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
							</div>
						</div>
						<div class="col-md-4">
							<div id="divImg">
								<img src="images/contas/sem-foto.jpg" width="80px" id="target">
							</div>
						</div>
					</div>
					<input type="hidden" name="id" id="id">
					<br>
					<div id="mensagem" class="centro texto-menor"></div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>

		</div>
	</div>
</div>
<!-- Fim Modal Inserir-->

<!-- Modal Dados-->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="nome_dados"></span></h4>
				<button id="btn-fechar-perfil" type="button" class="close mg-t--20" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">
						<span><b>Valor : </b></span>
						<span id="valor_dados"></span>
					</div>
					<div class="col-md-6">
						<span><b>Data Lançamento: </b></span>
						<span id="data_lanc_dados"></span>
					</div>
				</div>
				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">
						<span><b>Data Vencimento: </b></span>
						<span id="data_venc_dados"></span>
					</div>
					<div class="col-md-6">
						<span><b>Data PGTO: </b></span>
						<span id="data_pgto_dados"></span>
					</div>
				</div>
				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">
						<span><b>Usuário Lanc: </b></span>
						<span id="usuario_lanc_dados"></span>
					</div>
					<div class="col-md-6">
						<span><b>Usuário Baixa: </b></span>
						<span id="usuario_baixa_dados"></span>
					</div>
				</div>
				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">
						<span><b>Cliente: </b></span>
						<span id="cliente_dados"></span>
					</div>
					<div class="col-md-6">
						<span><b>Fornecedor: </b></span>
						<span id="nome_forn_dados"></span>
					</div>
				</div>
				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">
						<span><b>Produto: </b></span>
						<span id="produto_dados"></span>
					</div>
					<div class="col-md-6">
						<span><b>Quantidade: </b></span>
						<span id="quantidade_dados"></span>
					</div>
				</div>
				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-12">
						<span><b>Chave Pix: </b></span>
						<span id="chave_dados"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 centro">
						<a id="link_mostrar" target="_blank" title="Clique para abrir o arquivo!">
							<img width="150px" id="target_mostrar">
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Fim Modal Dados-->

<!-- Variável Página -->
<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>
<!-- JavaScript para chamar o CRUD da tabela -->
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

<!-- Carregar Imagem -->
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

<!-- valor Data -->
<script type="text/javascript">
	function valorData(dataInicio, dataFinal) {
		$('#data-inicial-caixa').val(dataInicio);
		$('#data-final-caixa').val(dataFinal);
		listar();
	}
</script>

<!-- Muda data inicial e final -->
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

<!-- função listar -->
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

<!-- Buscar Contas -->
<script type="text/javascript">
	function buscarContas(status) {
		$('#buscar-contas').val(status);
		listar();
	}
</script>

<!-- Dar baixa em contas -->
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

<!-- Produto e Quantidade em caso de recebimento do fornecedor -->
<script>
	$(document).ready(function() {
		$('#fornecedor').change(function() {
			let idFornecedor = $(this).val();

			if (idFornecedor != "0" && idFornecedor != "") {
				$('#bloco-produto').fadeIn();

				// Carregar produtos via AJAX
				$.ajax({
					url: 'paginas/' + pag + "/buscar-produto.php",
					type: 'POST',
					data: {
						fornecedor: idFornecedor
					},
					success: function(data) {
						$('#produto').html(data);
						$('#produto').select2('destroy').select2({
							dropdownParent: $('#modalForm')
						});
					}
				});
			} else {
				$('#bloco-produto').fadeOut();
				$('#produto').html('<option value="">Selecione um Produto</option>');
				$('#quantidade').val('');
			}
		});
	});
</script>
<!-- Fim Produto e Quantidade em caso de recebimento do fornecedor -->