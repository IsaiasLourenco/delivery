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

$data_hoje = $formatter->format(new DateTime());
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
        crossorigin="anonymous">
    <link rel="stylesheet" href="http://localhost/delivery/sistema/painel/css/rel_produto.css">
</head>
<body>
    <div class="header-relatorio">
        <img src="<?php echo $url_sistema ?>img/<?php echo $logo_rel ?>" class="logo-rel">
        <div class="titulo-rel">
            <div class="nome-sistema"><?php echo $nome_sistema ?></div>
            <div class="titulo-principal">RELATÓRIO DE PRODUTOS</div>
        </div>
        <div class="data-rel"><?php echo mb_strtoupper($data_hoje) ?></div>
    </div>
    <div class="mx-2">
        <?php
        $estoque_baixo = 0;
        $query = $pdo->query("SELECT * FROM produtos ORDER BY nome asc");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = @count($res);
        if ($total_reg > 0) {
        ?>
            <table class="table table-striped borda" cellpadding="6">
                <thead>
                    <tr class="centro">
                        <th scope="col">Nome</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Valor Compra</th>
                        <th scope="col">Valor Venda</th>
                        <th scope="col">Estoque</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < $total_reg; $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }
                        $id = $res[$i]['id'];
                        $nome = $res[$i]['nome'];
                        $descricao = $res[$i]['descricao'];
                        $categoria = $res[$i]['categoria'];
                        $valor_compra = $res[$i]['valor_compra'];
                        $valor_venda = $res[$i]['valor_venda'];
                        $foto = $res[$i]['foto'];
                        $estoque = $res[$i]['estoque'];
                        $nivel_estoque = $res[$i]['nivel_estoque'];
                        $tem_estoque = $res[$i]['tem_estoque'];
                        $valor_vendaF = number_format($valor_venda, 2, ',', '.');
                        $valor_compraF = number_format($valor_compra, 2, ',', '.');
                        $query2 = $pdo->query("SELECT * FROM categorias where id = '$categoria'");
                        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                        $total_reg2 = @count($res2);
                        if ($total_reg2 > 0) {
                            $nome_cat = $res2[0]['nome'];
                        } else {
                            $nome_cat = 'Sem Referência!';
                        }
                        if ($nivel_estoque >= $estoque and $tem_estoque == 'Sim') {
                            $alerta_estoque = 'text-danger';
                            $estoque_baixo += 1;
                        } else {
                            $alerta_estoque = '';
                        }

                    ?>
                        <tr class="centro" class="<?php echo $alerta_estoque ?>">
                            <td class="centro">
                                <?php echo $nome ?>
                            </td>
                            <td class="centro"><?php echo $nome_cat ?></td>
                            <td class="centro">R$ <?php echo $valor_compraF ?></td>
                            <td class="centro">R$ <?php echo $valor_vendaF ?></td>
                            <td class="centro"><?php echo $estoque ?></td>
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
            <span class="text-danger texto-menor">PRODUTOS COM ESTOQUE BAIXO : <?php echo @$estoque_baixo ?> </span><br>
            <span class="texto-menor"> TOTAL DE PRODUTOS : <?php echo @$total_reg ?> </span>
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