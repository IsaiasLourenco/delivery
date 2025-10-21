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
$status = $_GET['status'];
$forma_pgto = $_GET['forma_pgto'];
$dataFinal = $_GET['dataFinal'];
$data_hoje = $formatter->format(new DateTime());
$dataInicial = $_GET['dataInicial'];
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));
if ($dataInicial == $dataFinal) {
    $texto_apuracao = 'APURADO EM ' . $dataInicialF;
} else if ($dataInicial == '1980-01-01') {
    $texto_apuracao = 'APURADO EM TODO O PERÍODO';
} else {
    $texto_apuracao = 'APURAÇÃO DE ' . $dataInicialF . ' ATÉ ' . $dataFinalF;
}
if ($status == '') {
    $acao_rel = '';
} else {
    if ($status == 'finalizado') {
        $acao_rel = ' Finalizadas ';
    } else {
        $acao_rel = ' Canceladas ';
    }
}
if ($forma_pgto == '') {
    $texto_tabela = ' ';
    $cor_tabela = 'text-success';
    $tabela_pago = 'RECEBIDAS';
} else {
    $texto_tabela = ' ' . $forma_pgto;
    $cor_tabela = 'text-danger';
    $tabela_pago = 'PAGAS';
}
$status = '%' . $status . '%';
$forma_pgto = '%' . $forma_pgto . '%';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
        crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $url_sistema ?>sistema/painel/css/rel_vendas.css">
</head>

<body>
    <div class="header-relatorio">
        <img src="<?php echo $url_sistema ?>img/<?php echo $logo_rel ?>" class="logo-rel">
        <div class="titulo-rel">
            <div class="nome-sistema"><?php echo $nome_sistema ?></div>
            <div class="titulo-principal">RELATÓRIO DE VENDAS <?php echo $acao_rel ?> <?php echo $texto_tabela ?></div>
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
        $total_pago = 0;
        $total_pagoF = 0;
        $total_a_pagar = 0;
        $total_a_pagarF = 0;
        $query = $pdo->query("SELECT * FROM vendas WHERE (data_pagamento >= '$dataInicial' AND data_pagamento <= '$dataFinal') 
                              AND pago  = 'Sim' AND status_venda LIKE '$status' AND tipo_pagamento LIKE '$forma_pgto' 
                              ORDER BY data_pagamento ASC, hora_pagamento ASC ");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = count($res);
        if ($total_reg > 0) {
        ?>
            <table class="table table-striped borda" cellpadding="6">
                <thead>
                    <tr class="centro">
                        <th scope="col">Pedido</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Total Pago</th>
                        <th scope="col">Troco</th>
                        <th scope="col">Forma PGTO</th>
                        <th scope="col">Data</th>
                        <th scope="col">Hora</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < $total_reg; $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }
                        $id = $res[$i]['id'];
                        $idF = str_pad($id, 2, '0', STR_PAD_LEFT);
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
                        $forma_pgto = $res[$i]['tipo_pagamento'];
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

                        if ($status_venda == 'Finalizado') {
                            $classe_alerta = 'text-verde';
                            $total_pago += $valor_compra;
                            $classe_linha = '';
                            $classe_square = 'verde';
                            $imagem = 'verde.jpg';
                        } else if ($status_venda == 'Cancelado') {
                            $classe_alerta = 'text-danger';
                            $total_a_pagar += $valor_compra;
                            $classe_linha = 'text-muted';
                            $classe_square = 'text-danger';
                            $imagem = 'vermelho.jpg';
                        } else {
                            $classe_alerta = 'text-primary';
                            $total_pago += $valor_compra;
                            $classe_linha = '';
                            $classe_square = 'verde';
                            $imagem = 'verde.jpg';
                        }                        

                        $total_pagoF = number_format($total_pago, 2, ',', '.');
                        $total_a_pagarF = number_format($total_a_pagar, 2, ',', '.');
                        
                    ?>
                        <tr class="centro">
                            <td class="centro mg-t-3">
                                <img src="<?php echo $url_sistema ?>/img/<?php echo $imagem ?>" width="11px" height="11px">
                                <strong><?php echo $idF?></strong>
                            </td>
                            <td><?php echo $nome_cliente ?></td>                            
                            <td class="esc"><?php echo $valor_compraF ?></td>
                            <td class="esc"><?php echo $valor_pagoF ?></td>
                            <td class="esc"><?php echo $trocoF ?></td>
                            <td class="esc"><?php echo $forma_pgto ?></td>
                            <td class="esc"><?php echo $data_pgtoF ?></td>
                            <td class="esc"><?php echo $hora_pgto ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else {
            echo 'Não possuem registros para serem exibidos!';
            exit();
        } ?>
    </div>
    <div class="col-md-12 p-2">
        <div class="direito mg-r-20">
            <span class="text-danger texto-menor"> VENDAS CANCELADAS: R$ <?php echo $total_a_pagarF ?> </span><br>
            <span class="text-success texto-menor"> VENDAS RECEBIDAS: R$ <?php echo $total_pagoF ?> </span>
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