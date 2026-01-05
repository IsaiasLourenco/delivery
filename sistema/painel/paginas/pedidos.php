<?php
@session_start();
require_once("verificar.php");
require_once("../conexao.php");
$pag = 'pedidos';

$query_sistema = $pdo->query("SELECT * FROM config");
$res_sistema = $query_sistema->fetchAll(PDO::FETCH_ASSOC);
$id_sistema = $res_sistema[0]['id'];
$tempo_atualizacao = $res_sistema[0]['tempo_atualizacao'];

$segundos = $tempo_atualizacao * 1000;
?>
<div class="bs-example widget-shadow pddg-15 mg-t--5">
	<div class="row">
		<div class="col-md-12 centro">
			<div class="texto-menor">
				<a title="Todos os Pedidos"
					class="text-muted"
					href="#"
					onclick="buscarContas('')">
					<span>Todos </span>
					<strong>(<span id="todos_pedidos"></span>)</strong>
				</a> |
				<a title="Pedido Iniciado"
					class="text-muted"
					href="#"
					onclick="buscarContas('Iniciado')">
					<i class="fa fa-square text-primary"></i>
					<span>Iniciado</span>
					<strong>(<span id="iniciado"></span>)</strong>
				</a> |
				<a title="Preparando Pedido"
					class="text-muted"
					href="#"
					onclick="buscarContas('Preparando')">
					<i class="fa fa-square text-preparando"></i>
					<span>Preparando</span>
					<strong>(<span id="preparando"></span>)</strong>
				</a> |
				<a title="Pedido em Rota de Entrega"
					class="text-muted"
					href="#"
					onclick="buscarContas('Em Rota')">
					<i class="fa fa-square text-emRota"></i>
					<span>Em Rota</span>
					<strong>(<span id="em_rota"></span>)</strong>
				</a>
			</div>
		</div>
		<input type="hidden" id="buscar-contas">
		<input type="hidden" id="id_pedido">
		<script>
			// Garante que o primeiro valor enviado ao listar.php não seja vazio
			$('#id_pedido').val("0");
		</script>
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
<!-- Fim Modal Dados-->

<!-- Variável Página -->
<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>
<!-- Fim Variável Página -->

<!-- JavaScript para chamar o CRUD da tabela -->
<script src="js/ajax.js"></script>
<!-- Fim JavaScript para chamar o CRUD da tabela -->

<!-- Definindo tempo para atualizar a página de pedidos -->
<script type="text/javascript">
	var seg = parseInt('<?= $segundos ?>');
	$(document).ready(function() {
		setInterval(() => {
			listar();
		}, seg);
	});
</script>
<!-- Fim Definindo tempo para atualizar a página de pedidos -->

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

<!-- Listar por datas -->
<script type="text/javascript">
	function listar() {
		var status = $('#buscar-contas').val();
		var ultimo_pedido = $('#id_pedido').val();
		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {
				status,
				ultimo_pedido
			},
			dataType: "html",
			success: function(result) {
				$("#listar").html(result);
				$('#mensagem-excluir').text('');
			}
		});
	}
</script>
<!-- Fim Listar por datas -->

<!-- Busca contas -->
<script type="text/javascript">
	function buscarContas(status) {
		$('#buscar-contas').val(status);
		listar();
	}
</script>
<!-- Fim Busca contas -->

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
<!-- Fim Baixar Pagamento -->