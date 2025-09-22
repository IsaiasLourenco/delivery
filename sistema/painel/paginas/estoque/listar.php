<?php 
require_once("../../../conexao.php");
$tabela = 'produtos';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<h4 class="centro">Estoque Baixo</h4>
	<table class="table table-hover table-responsive tabela-menor" id="tabela">
	<thead> 
	<tr> 
	<th class="centro">Nome</th>	
	<th class="esc centro">Categoria</th> 	
	<th class="esc centro">Valor Compra</th> 	
	<th class="esc centro">Valor Venda</th> 
	<th class="esc centro">Estoque</th>	
	<th class="centro">Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];	
	$descricao = $res[$i]['descricao'];
	$categoria = $res[$i]['categoria'];
	$valor_compra = $res[$i]['valor_compra'];
	$valor_venda = $res[$i]['valor_venda'];
	$estoque = $res[$i]['estoque'];
	$foto = $res[$i]['foto'];
	$nivel_estoque = $res[$i]['nivel_estoque'];
	$ativo = $res[$i]['ativo'];
	$tem_estoque = $res[$i]['tem_estoque'];

	$valor_vendaF = number_format($valor_venda, 2, ',', '.');
	$valor_compraF = number_format($valor_compra, 2, ',', '.');

		$query2 = $pdo->query("SELECT * FROM categorias where id = '$categoria'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_cat = $res2[0]['nome'];
		}else{
			$nome_cat = 'Sem Referência!';
		}

		if($nivel_estoque >= $estoque and $tem_estoque == 'Sim'){
		
echo <<<HTML
<tr>
<td class="centro">
<img src="images/produtos/{$foto}" width="27px" class="mr-2">
{$nome}
</td>
<td class="esc centro">{$nome_cat}</td>
<td class="esc centro">R$ {$valor_compraF}</td>
<td class="esc centro">R$ {$valor_vendaF}</td>
<td class="esc centro">{$estoque}</td>

<td class="centro">
		<a href="#" onclick="mostrar('{$nome}', 
									 '{$nome_cat}', 
									 '{$descricao}', 
									 '{$valor_compraF}',  
									 '{$valor_vendaF}', 
									 '{$estoque}', 
									 '{$foto}', 
									 '{$nivel_estoque}')" title="Ver Dados">
			<i class="fa fa-info-circle text-secondary"></i>
		</a>
		</td>
</tr>
HTML;

}

}

echo <<<HTML
</tbody>
<div class="centro texto-menor" id="mensagem-excluir"></div>
</table>
HTML;


} else {
	echo '<p class="texto-menor">Não possui nenhum registro Cadastrado!</p>';
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
	function editar(id, nome, categoria, descricao, valor_compra, valor_venda, foto, nivel_estoque){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#valor_venda').val(valor_venda);
		$('#valor_compra').val(valor_compra);
		$('#categoria').val(categoria).change();
		$('#descricao').val(descricao);
		$('#nivel_estoque').val(nivel_estoque);
						
		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');

		$('#target').attr('src','images/produtos/' + foto);
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#valor_compra').val('');
		$('#valor_venda').val('');
		$('#descricao').val('');		
		$('#foto').val('');
		$('#target').attr('src','images/produtos/sem-foto.jpg');
	}
</script>



<script type="text/javascript">
	function mostrar(nome, categoria, descricao, valor_compra, valor_venda, estoque, foto, nivel_estoque){

		$('#nome_dados').text(nome);
		$('#valor_compra_dados').text(valor_compra);
		$('#categoria_dados').text(categoria);
		$('#valor_venda_dados').text(valor_venda);
		$('#descricao_dados').text(descricao);
		$('#estoque_dados').text(estoque);
		$('#nivel_estoque_dados').text(nivel_estoque);
		
		$('#target_mostrar').attr('src','images/produtos/' + foto);

		$('#modalDados').modal('show');
	}
</script>




<script type="text/javascript">
	function saida(id, nome, estoque){

		$('#nome_saida').text(nome);
		$('#estoque_saida').val(estoque);
		$('#id_saida').val(id);		

		$('#modalSaida').modal('show');
	}
</script>


<script type="text/javascript">
	function entrada(id, nome, estoque){

		$('#nome_entrada').text(nome);
		$('#estoque_entrada').val(estoque);
		$('#id_entrada').val(id);		

		$('#modalEntrada').modal('show');
	}
</script>