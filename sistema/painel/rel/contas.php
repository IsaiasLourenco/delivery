<?php
include('../../conexao.php');
$query = $pdo->query("SELECT * FROM config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$logo_rel = $res[0]['logo_rel'];
$nome_sistema = $res[0]['nome_sistema'];
$desenvolvedor = $res[0]['desenvolvedor'];
$site_dev = $res[0]['site_dev'];
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
$tabela = $_GET['tabela'];
$pago = $_GET['pago'];
$busca = $_GET['busca'];
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
if ($pago == '') {
    $acao_rel = '';
} else {
    if ($pago == 'Sim') {
        $acao_rel = ' Pagas ';
    } else {
        $acao_rel = ' Pendentes ';
    }
}
if ($tabela == 'receber') {
    $texto_tabela = ' à Receber';
    $cor_tabela = 'text-success';
    $tabela_pago = 'RECEBIDAS';
} else {
    $texto_tabela = ' à Pagar';
    $cor_tabela = 'text-danger';
    $tabela_pago = 'PAGAS';
}
$pago = '%' . $pago . '%';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Contas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
        crossorigin="anonymous">
    <link rel="stylesheet" href="http://localhost/delivery/sistema/painel/css/rel_contas.css">
</head>

<body>
    <div class="header-relatorio">
        <img src="<?php echo $url_sistema ?>img/<?php echo $logo_rel ?>" class="logo-rel">
        <div class="titulo-rel">
            <div class="nome-sistema"><?php echo $nome_sistema ?></div>
            <div class="titulo-principal">RELATÓRIO DE CONTAS <?php echo $texto_tabela ?> <?php echo $acao_rel ?></div>
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
        $query = $pdo->query("SELECT * FROM $tabela WHERE ($busca >= '$dataInicial' AND $busca <= '$dataFinal') AND pago 
                              LIKE '$pago' ORDER BY id DESC ");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = count($res);
        if ($total_reg > 0) {
        ?>
            <table class="table table-striped borda" cellpadding="6">
                <thead>
                    <tr class="centro">
                        <th scope="col">Descrição</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Vencimento</th>
                        <th scope="col">Data PGTO</th>
                        <th scope="col">Pago</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < $total_reg; $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }
                        $id = $res[$i]['id'];
                        $descricao = $res[$i]['descricao'];
                        $valor = $res[$i]['valor'];
                        $data = $res[$i]['data_lancamento'];
                        $vencimento = $res[$i]['data_vencimento'];
                        $pago = $res[$i]['pago'];
                        $data_pgto = $res[$i]['data_pagamento'];
                        if ($pago == 'Sim') {
                            $total_pago += $valor;
                            $classe_square = 'verde';
                            $ocultar_baixa = 'ocultar';
                            $imagem = 'verde.jpg';
                        } else {
                            $total_a_pagar += $valor;
                            $classe_square = 'text-danger';
                            $ocultar_baixa = '';
                            $imagem = 'vermelho.jpg';
                        }
                        $valorF = number_format($valor, 2, ',', '.');
                        $total_pagoF = number_format($total_pago, 2, ',', '.');
                        $total_a_pagarF = number_format($total_a_pagar, 2, ',', '.');
                        $dataF = implode('/', array_reverse(explode('-', $data)));
                        $vencimentoF = implode('/', array_reverse(explode('-', $vencimento)));
                        $data_pgtoF = implode('/', array_reverse(explode('-', $data_pgto)));
                        if ($data_pgtoF == '00/00/0000') {
                            $data_pgtoF = 'Pendente';
                        }
                    ?>
                        <tr class="centro">
                            <td class="centro mg-t-3">
                                <img src="<?php echo $url_sistema ?>/img/<?php echo $imagem ?>" width="11px" height="11px">
                                <?php echo $descricao ?>
                            </td>
                            <td class="esc">R$ <?php echo $valorF ?></td>
                            <td class="esc"><?php echo $vencimentoF ?></td>
                            <td class="esc"><?php echo $data_pgtoF ?></td>
                            <td class="esc"><?php echo $pago ?></td>
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
            <span class="text-danger texto-menor"> TOTAL À <?php echo mb_strtoupper($tabela) ?></small> : R$ <?php echo $total_a_pagarF ?> </span><br>
            <span class="text-success texto-menor"> TOTAL <?php echo $tabela_pago ?> : R$ <?php echo $total_pagoF ?> </span>
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