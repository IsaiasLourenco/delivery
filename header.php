<?php 
require_once ('./sistema/conexao.php');
$query_sistema = $pdo->query("SELECT * FROM config");
$res_sistema = $query_sistema->fetchAll(PDO::FETCH_ASSOC);
$id_sistema = $res_sistema[0]['id'];
$nome_sistema = $res_sistema[0]['nome_sistema'];
$email_sistema = $res_sistema[0]['email_sistema'];
$telefone_sistema = $res_sistema[0]['telefone_sistema'];
$telefone_fixo = $res_sistema[0]['telefone_fixo'];
$cnpj_sistema = $res_sistema[0]['cnpj_sistema'];
$cep_sistema = $res_sistema[0]['cep_sistema'];
$rua_sistema = $res_sistema[0]['rua_sistema'];
$numero_sistema = $res_sistema[0]['numero_sistema'];
$bairro_sistema = $res_sistema[0]['bairro_sistema'];
$cidade_sistema = $res_sistema[0]['cidade_sistema'];
$estado_sistema = $res_sistema[0]['estado_sistema'];
$instagram = $res_sistema[0]['instagram_sistema'];
$tipo = $res_sistema[0]['tipo_relatorio'];
$cards = $res_sistema[0]['cards'];
$pedidos = $res_sistema[0]['pedidos'];
$dev = $res_sistema[0]['desenvolvedor'];
$site_dev = $res_sistema[0]['site_dev'];
$previsao_entrega = $res_sistema[0]['previsao_entrega'];
$aberto = $res_sistema[0]['estabelecimento_aberto'];
$abertura = $res_sistema[0]['abertura'];
$fechamento = $res_sistema[0]['fechamento'];
$txt_fechamento = $res_sistema[0]['texto_fechamento'];
$logo_sistema = $res_sistema[0]['logotipo'];
$icone_sistema = $res_sistema[0]['icone'];
$logo_rel = $res_sistema[0]['logo_rel'];
$url_sistema = $res_sistema[0]['url_sistema'];

// Monta endereço completo
$endereco_sistema = $rua_sistema . ', ' . $numero_sistema . ' - ' . $bairro_sistema . ' - ' . $cidade_sistema . '/' . $estado_sistema;
// Tira caracteres para whatsapp link
$telefone_url = '55'.preg_replace('/[()-]+/', '', $telefone_sistema);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Delivery Interativo Vetor256.">
    <meta name="description" content="Serviços de entrega de comida e lanches completo.">
    <meta name="author" content="Vetor256.">
    <!-- FAVICON -->
    <link rel="shortcut icon" href="<?php echo $url_sistema; ?>/img/<?php echo $icone_sistema; ?>" type="image/x-icon">

    <!-- TITLE -->
    <title><?php echo $nome_sistema; ?></title>
    <!-- BOOTSTRAP   -->
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- FIM BOOTSTRAP   -->
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">
</head>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script type="text/javascript" src="js/mascaras.js"></script>
<script type="text/javascript" src="js/buscaCepModal.js"></script>
<script type="text/javascript" src="js/validarCPF.js"></script>
<body>