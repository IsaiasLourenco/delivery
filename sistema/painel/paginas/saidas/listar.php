<?php 
require_once("../../../conexao.php");
$tabela = 'saidas';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<small>
	<h4 class="centro">Saídas</h4>
	<table class="table table-hover table-responsive tabela-menor" id="tabela">
	<thead> 
	<tr> 
	<th class="centro">Produto</th>	
	<th class="centro">Quantidade</th> 	
	<th class="esc centro">Motivo</th> 	
	<th class="esc centro">Usuário Lançou</th> 
	<th class="esc centro">Data</th>	
	
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$produto = $res[$i]['produto'];	
	$quantidade = $res[$i]['quantidade'];
	$motivo = $res[$i]['motivo'];
	$usuario = $res[$i]['usuario'];
	$data_saida = $res[$i]['data_saida'];
			

		$query2 = $pdo->query("SELECT * FROM produtos where id = '$produto'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_produto = $res2[0]['nome'];
			$foto_produto = $res2[0]['foto'];
		}else{
			$nome_produto = 'Sem Referência!';
			$foto_produto = 'sem-foto.jpg';
		}


		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_usuario = $res2[0]['nome'];
			
		}else{
			$nome_usuario = 'Sem Referência!';
			
		}


		$dataF = implode('/', array_reverse(explode('-', $data_saida)));
		


echo <<<HTML
<tr class="">
<td class="centro">
<img src="images/produtos/{$foto_produto}" width="27px" class="mr-2">
{$nome_produto}
</td>
<td class="centro">{$quantidade}</td>
<td class="esc centro">{$motivo}</td>
<td class="esc centro"> {$nome_usuario}</td>
<td class="esc centro">{$dataF}</td>

</tr>
HTML;

}

echo <<<HTML
</tbody>
<div class="centro texto-menor" id="mensagem-excluir"></div>
</table>
</small>
HTML;


}else{
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

