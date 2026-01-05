<?php
session_start();
require_once('../conexao.php');
require_once('verificar.php');
//RECUPERANDO DADOS DO USER
$id_usuario = $_SESSION['id'];
$query = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario = $res[0]['nome'];
$email_usuario = $res[0]['email'];
$cpf_usuario = $res[0]['cpf'];
$telefone_usuario = $res[0]['telefone'];
$cep_usuario = $res[0]['cep'];
$rua_usuario = $res[0]['rua'];
$numero_usuario = $res[0]['numero'];
$bairro_usuario = $res[0]['bairro'];
$cidade_usuario = $res[0]['cidade'];
$estado_usuario = $res[0]['estado'];
$senha_usuario = $res[0]['senha'];
$nivel_usuario = $res[0]['nivel'];
$ativo_usuario = $res[0]['ativo'];
$foto_usuario = $res[0]['foto'];
$data_cad_usuario = $res[0]['data_cad'];

$queryNivel = $pdo->query("SELECT * FROM cargos WHERE id = '$nivel_usuario'");
$resNivel = $queryNivel->fetchAll(PDO::FETCH_ASSOC);
$nomeNivel = $resNivel[0]['nome'];

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
$tipo_rel = $res_sistema[0]['tipo_relatorio'];
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
$tempo_atualizacao = $res_sistema[0]['tempo_atualizacao'];

$segundos = $tempo_atualizacao * 1000;

if (@$_GET['pagina'] != "") {
    $pagina = @$_GET['pagina'];
} else {
    $pagina = 'home';
}

$data_atual = date('Y-m-d');
$mes_atual = date('m');
$ano_atual = date('Y');
$data_mes = $ano_atual . "-" . $mes_atual . "-01";
$data_ano = $ano_atual . "-01-01";
$partes_inicial = explode('-', $data_atual);
$dataDiaInicial = $partes_inicial[2];
$dataMesInicial = $partes_inicial[1];

?>
<!DOCTYPE HTML>
<html>

<head>
    <title><?php echo $nome_sistema; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- FAVICON -->
    <link rel="shortcut icon" href="../../img/<?php echo $icone_sistema; ?>" type="image/x-icon">
    <script type="application/x-javascript">
        addEventListener("load", function() {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>

    <script>
        document.addEventListener('click', () => {
            const audio = new Audio();
            audio.play().catch(() => {});
        }, {
            once: true
        });
    </script>

    <!-- js-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <!-- Bootstrap icons     -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />

    <!-- font-awesome icons CSS -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- //font-awesome icons CSS-->

    <!-- side nav css file -->
    <link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css' />
    <!-- //side nav css file -->

    <!-- side bar left customizada por Isaias -->
    <link rel="stylesheet" href="css/custom-sidebar-left.css">

    <!--webfonts-->
    <link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
    <!--//webfonts-->

    <!-- chart -->
    <script src="js/Chart.js"></script>
    <!-- //chart -->

    <!-- Metis Menu -->
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <link href="css/custom.css" rel="stylesheet">
    <!--//Metis Menu -->
    <style>
        #chartdiv {
            width: 100%;
            height: 295px;
        }
    </style>
    <!--pie-chart --><!-- index page sales reviews visitors pie chart -->
    <script src="js/pie-chart.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#demo-pie-1').pieChart({
                barColor: '#2dde98',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function(from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-2').pieChart({
                barColor: '#8e43e7',
                trackColor: '#eee',
                lineCap: 'butt',
                lineWidth: 8,
                onStep: function(from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-3').pieChart({
                barColor: '#ffc168',
                trackColor: '#eee',
                lineCap: 'square',
                lineWidth: 8,
                onStep: function(from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });


        });
    </script>
    <!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->

    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css">
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>

    <!-- SELECT2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style type="text/css">
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            padding: 6px 12px !important;
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-selection__rendered {
            line-height: 24px !important;
        }

        .select2-selection__arrow {
            height: 38px !important;
        }
    </style>
</head>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
            <!--left-fixed -navigation-->
            <aside class="sidebar-left custom-sidebar-left">
                <nav class="navbar navbar-inverse">
                    <div class="navbar-header">
                        <h1>
                            <a class="navbar-brand" href="index.php">
                                <img src="../../img/<?php echo $logo_sistema; ?>" alt="Logo do sistema" class="logo"> Delivery
                                <span class="dashboard_text"><?php echo $nome_sistema; ?></span>
                            </a>
                        </h1>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="sidebar-menu">
                            <li class="header">MENU NAVEGAÇÃO</li>
                            <li class="treeview">
                                <a href="index.php">
                                    <i class="fa fa-home"></i> <span>Home</span>
                                </a>
                            </li>
                            <li class="treeview">
                                <a href="index.php?pagina=pedidos">
                                    <i class="fa fa-receipt"></i> <span>Pedidos</span>
                                </a>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-users"></i>
                                    <span>Pessoas</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="index.php?pagina=funcionarios"><i class="fa fa-angle-right"></i> Funcionários</a></li>
                                    <li><a href="index.php?pagina=usuarios"><i class="fa fa-angle-right"></i> Usuários</a></li>
                                    <li><a href="index.php?pagina=clientes"><i class="fa fa-angle-right"></i> Clientes</a></li>
                                    <li><a href="index.php?pagina=fornecedores"><i class="fa fa-angle-right"></i> Fornecedores</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-plus"></i>
                                    <span>Cadastros</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="index.php?pagina=cargos"><i class="fa fa-angle-right"></i> Cargos</a></li>
                                    <li><a href="index.php?pagina=bairros"><i class="fa fa-angle-right"></i> Bairros</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-cart-shopping"></i>
                                    <span>Produtos</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="index.php?pagina=produtos"><i class="fa fa-angle-right"></i> Produtos</a></li>
                                    <li><a href="index.php?pagina=categorias"><i class="fa fa-angle-right"></i> Categorias</a></li>
                                    <li><a href="index.php?pagina=cotacao"><i class="fa fa-angle-right"></i> Cotação</a></li>
                                    <li><a href="index.php?pagina=estoque"><i class="fa fa-angle-right"></i> Estoque Baixo</a></li>
                                    <li><a href="index.php?pagina=entradas"><i class="fa fa-angle-right"></i> Entradas</a></li>
                                    <li><a href="index.php?pagina=saidas"><i class="fa fa-angle-right"></i> Saídas</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-dollar"></i>
                                    <span>Financeiro</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="index.php?pagina=pagar"><i class="fa fa-angle-right"></i> Contas à Pagar</a></li>
                                    <li><a href="index.php?pagina=receber"><i class="fa fa-angle-right"></i> Contas à Receber</a></li>
                                    <li><a href="index.php?pagina=compras"><i class="fa fa-angle-right"></i> Compras</a></li>
                                    <li><a href="index.php?pagina=vendas"><i class="fa fa-angle-right"></i> Vendas | Pedidos</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-file-pdf-o"></i>
                                    <span>Relatórios</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="rel/produtos_class.php" target="_blank"><i class="fa fa-angle-right">
                                            </i> Produtos</a>
                                    </li>
                                    <li><a href="" data-toggle="modal" data-target="#RelCon"><i class="fa fa-angle-right">
                                            </i> Contas</a>
                                    </li>
                                    <li><a href="" data-toggle="modal" data-target="#RelLucro"><i class="fa fa-angle-right">
                                            </i> Lucro</a>
                                    </li>
                                    <li><a href="" data-toggle="modal" data-target="#RelVen"><i class="fa fa-angle-right">
                                            </i> Vendas | Pedidos</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </nav>
            </aside>
        </div>
        <!--left-fixed -navigation-->

        <!-- header-starts -->
        <div class="sticky-header header-section ">
            <div class="header-left">
                <!--toggle button start-->
                <button id="showLeftPush" data-toggle="collapse" data-target=".collapse"><i class="fa fa-bars"></i></button>
                <!--toggle button end-->
                <div class="profile_details_left"><!--notifications of menu start -->
                    <div id="atualizar-pedidos">
                        <?php include('atualizar-pedidos.php'); ?>
                    </div>
                    <div class="clearfix"> </div>
                </div>
            </div>
            <div class="header-right">

                <div class="profile_details">
                    <ul>
                        <li class="dropdown profile_details_drop">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <div class="profile_img">
                                    <span class="prfil-img"><img src="images/perfil/<?php echo $foto_usuario; ?>" alt="Foto do usuário" class="img-perfil-custom"> </span>
                                    <div class="user-name invisible-in-mob">
                                        <p><?php echo $nome_usuario; ?></p>
                                        <span><?php echo $nomeNivel; ?></span>
                                    </div>
                                    <i class="fa fa-angle-down lnr"></i>
                                    <i class="fa fa-angle-up lnr"></i>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                            <ul class="dropdown-menu drp-mnu">
                                <li> <a href="#" data-toggle="modal" data-target="#modalConfig"><i class="fa fa-cog"></i> Configurações</a> </li>
                                <li> <a href="#" data-toggle="modal" data-target="#modalPerfil"><i class="fa fa-user"></i> Perfil</a> </li>
                                <li> <a href="logout.php"><i class="fa fa-sign-out"></i> Sair</a> </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="clearfix"> </div>
        </div>
        <!-- //header-ends -->
        <!-- main content start-->
        <div id="page-wrapper">
            <?php require_once('paginas/' . $pagina . '.php') ?>
        </div>
        <!--footer-->
        <div class="footer rodape">
            <?php require_once("../footer.php"); ?>
        </div>
        <!--//footer-->
    </div>

    <!-- Classie --><!-- for toggle left push menu script -->
    <script src="js/classie.js"></script>
    <script>
        var menuLeft = document.getElementById('cbp-spmenu-s1'),
            showLeftPush = document.getElementById('showLeftPush'),
            body = document.body;

        showLeftPush.onclick = function() {
            classie.toggle(this, 'active');
            classie.toggle(body, 'cbp-spmenu-push-toright');
            classie.toggle(menuLeft, 'cbp-spmenu-open');
            disableOther('showLeftPush');
        };


        function disableOther(button) {
            if (button !== 'showLeftPush') {
                classie.toggle(showLeftPush, 'disabled');
            }
        }
    </script>
    <!-- //Classie --><!-- //for toggle left push menu script -->

    <!--scrolling js-->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <!--//scrolling js-->

    <!-- side nav js -->
    <script src='js/SidebarNav.min.js' type='text/javascript'></script>
    <script>
        $('.sidebar-menu').SidebarNav()
    </script>
    <!-- //side nav js -->

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.js"> </script>
    <!-- //Bootstrap Core JavaScript -->

</body>

</html>

<!-- Modal Config-->
<div class="modal fade" id="modalConfig" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Editar Configurações</h4>
                <button id="btn-fechar-config" type="button" class="close mg-t--20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-config">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="nome_sistema">Nome do Sistema</label>
                            <input type="text" class="form-control" id="nome_sistema" name="nome_sistema" value="<?php echo $nome_sistema ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="email_sistema">E-mail do Sistema</label>
                            <input type="email" class="form-control" id="email_sistema" name="email_sistema" value="<?php echo $email_sistema ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="telefone_sistema">Telefone do Sistema</label>
                            <input type="text" class="form-control" id="telefone_sistema" name="telefone_sistema" value="<?php echo $telefone_sistema ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="cnpj_sistema">CNPJ</label>
                            <input type="text" class="form-control" id="cnpj_sistema" name="cnpj_sistema" value="<?php echo $cnpj_sistema ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="telefone_fixo">Telefone Fixo</label>
                            <input type="text" class="form-control" id="telefone_fixo" name="telefone_fixo" value="<?php echo $telefone_fixo ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="cep-sistema">CEP</label>
                            <input type="text" class="form-control" id="cep-sistema" name="cep-sistema" value="<?php echo $cep_sistema ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="rua-sistema">Rua</label>
                            <input type="text" class="form-control" id="rua-sistema" name="rua-sistema" value="<?php echo $rua_sistema ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="numero-sistema">Número</label>
                            <input type="text" class="form-control" id="numero-sistema" name="numero-sistema" value="<?php echo $numero_sistema ?>" required>
                        </div>
                        <div class="col-md-5">
                            <label for="bairro-sistema">Bairro</label>
                            <input type="text" class="form-control" id="bairro-sistema" name="bairro-sistema" value="<?php echo $bairro_sistema ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="cidade">Cidade</label>
                            <input type="text" class="form-control" id="cidade-sistema" name="cidade-sistema" value="<?php echo $cidade_sistema ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="estado-sistema">Estado</label>
                            <input type="text" class="form-control" id="estado-sistema" name="estado-sistema" value="<?php echo $estado_sistema ?>" readonly>
                        </div>
                        <div class="col-md-5">
                            <label for="instagram">Instagram</label>
                            <input type="text" class="form-control" id="instagram" name="instagram" value="<?php echo $instagram ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="tipoRel">Tipo Relatório</label>
                            <select class="form-control" name="tipoRel">
                                <option value="PDF" <?php if ($tipo_rel == 'PDF') { ?> selected <?php } ?>>PDF</option>
                                <option value="HTML" <?php if ($tipo_rel == 'HTML') { ?> selected <?php } ?>>HTML</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cards">Cards</label>
                            <select class="form-control" name="cards">
                                <option value="Cores" <?php if ($cards == 'Cores') { ?> selected <?php } ?>>Cores</option>
                                <option value="Foto" <?php if ($cards == 'Foto') { ?> selected <?php } ?>>Foto</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="pedidos">Pedidos Whatsapp</label>
                            <select class="form-control" name="pedidos">
                                <option value="Sim" <?php if ($pedidos == 'Sim') { ?> selected <?php } ?>>Sim</option>
                                <option value="Não" <?php if ($pedidos == 'Não') { ?> selected <?php } ?>>Não</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tempo" class="form-label">Atualização (segundos)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="tempo" name="tempo" value="<?php echo $tempo_atualizacao ?>" required min="1">
                                <span class="input-group-text">seg</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="dev">Desenvolvedor</label>
                            <input type="text" class="form-control" id="dev" name="dev" value="<?php echo $dev ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="site">Site</label>
                            <input type="text" class="form-control" id="site" name="site" value="<?php echo $site_dev ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="previsao" class="form-label">Previsão de Entrega (minutos)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="previsao" name="previsao" value="<?php echo $previsao_entrega ?>" required min="1">
                                <span class="input-group-text">min</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="aberto">Estabelecimento</label>
                            <select class="form-control" name="aberto">
                                <option value="aberto" <?php if ($aberto == 'aberto') { ?> selected <?php } ?>>Aberto</option>
                                <option value="fechado" <?php if ($aberto == 'fechado') { ?> selected <?php } ?>>Fechado</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="abertura">Horário Abertura</label>
                            <input type="time" class="form-control" id="abertura" name="abertura" value="<?php echo $abertura ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="fechamento">Horário Fechamento</label>
                            <input type="time" class="form-control" id="fechamento" name="fechamento"
                                value="<?php echo $fechamento ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="txt_fechamento">Texto Fechamento</label>
                            <input type="text" class="form-control" id="txt_fechamento" name="txt_fechamento"
                                value="<?php echo $txt_fechamento ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="url_sistema">URL para Relatório</label>
                            <input type="text" class="form-control" id="url_sistema" name="url_sistema"
                                value="<?php echo $url_sistema ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="logotipo">Logotipo(*.png)</label>
                            <input type="file" class="form-control" id="logotipo" name="logotipo" onchange="carregarImgLogotipo()">
                        </div>
                        <div class="col-md-2">
                            <img src="../../img/<?php echo $logo_sistema; ?>" alt="Logotipo" style="width: 80px;" id="target-logo">
                        </div>
                        <div class="col-md-4">
                            <label for="icone">Ícone(*.png)</label>
                            <input type="file" class="form-control" id="icone" name="icone" onchange="carregarImgIcone()">
                        </div>
                        <div class="col-md-2">
                            <img src="../../img/<?php echo $icone_sistema; ?>" alt="Icone" style="width: 80px;" id="target-ico">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id_sistema ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="logo_rel">Logotipo Relatório(*.jpg)</label>
                            <input type="file" class="form-control" id="logo_rel" name="logo_rel" onchange="carregarImgLogoRel()">
                        </div>
                        <div class="col-md-2">
                            <img src="../../img/<?php echo $logo_rel; ?>" alt="Logotipo do Relatório" style="width: 80px;"
                                id="target-logo-rel">
                        </div>
                    </div>
                    <div id="msg-config" class="centro"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal Config -->

<!-- Modal Perfil-->
<div class="modal fade" id="modalPerfil" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Alterar Dados</h4>
                <button id="btn-fechar-perfil" type="button" class="close mg-t--20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-perfil">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome-perfil" name="nome" value="<?php echo $nome_usuario ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" id="email-perfil" name="email" value="<?php echo $email_usuario ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="cpf">CPF</label>
                            <input type="text" class="form-control" id="cpf-perfil" name="cpf" value="<?php echo $cpf_usuario ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefone">Telefone</label>
                            <input type="text" class="form-control" id="telefone-perfil" name="telefone" value="<?php echo $telefone_usuario ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="cep">CEP</label>
                            <input type="text" class="form-control" id="cep-perfil" name="cep" value="<?php echo $cep_usuario ?>" required>
                        </div>
                        <div class="col-md-5">
                            <label for="rua">Rua</label>
                            <input type="text" class="form-control" id="rua-perfil" name="rua" value="<?php echo $rua_usuario ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="numero">Número</label>
                            <input type="text" class="form-control" id="numero-perfil" name="numero" value="<?php echo $numero_usuario ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="bairro">Bairro</label>
                            <input type="text" class="form-control" id="bairro-perfil" name="bairro" value="<?php echo $bairro_usuario ?>" readonly>
                        </div>
                        <div class="col-md-5">
                            <label for="cidade">Cidade</label>
                            <input type="text" class="form-control" id="cidade-perfil" name="cidade" value="<?php echo $cidade_usuario ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="estado">Estado</label>
                            <input type="text" class="form-control" id="estado-perfil" name="estado" value="<?php echo $estado_usuario ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="senha">Senha</label>
                            <input type="password" class="form-control" id="senha-perfil" name="senha" value="<?php echo $senha_usuario ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="conf-senha">Confirmar Senha</label>
                            <input type="password" class="form-control" id="conf-senha-perfil" name="conf-senha">
                        </div>
                        <div class="col-md-5">
                            <label for="nivel">Nível</label>
                            <?php if ($nomeNivel == 'Administrador') { ?>
                                <select class="form-control" name="nivel" id="nivel">
                                    <?php
                                    $query = $pdo->query("SELECT * FROM cargos ORDER BY nome asc");
                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $total_reg = @count($res);

                                    if ($total_reg > 0) {
                                        for ($i = 0; $i < $total_reg; $i++) {
                                            echo '<option value="' . $res[$i]['id'] . '">' . $res[$i]['nome'] . '</option>';
                                        }
                                    } else {
                                        echo '<option value="0">Cadastre um Cargo</option>';
                                    }
                                    ?>
                                </select>
                            <?php } else { ?>
                                <input type="text" class="form-control" id="nivel-perfil"
                                    name="nivel" value="<?= $nomeNivel ?>" readonly>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="ativo">Ativo</label>
                            <select class="form-control" name="ativo" id="ativo">
                                <option value="Sim" selected>Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="foto">Foto</label>
                            <input type="file" class="form-control" id="foto-perfil" name="foto" onchange="carregarImgPerfil()">
                        </div>
                        <div class="col-md-6">
                            <img src="./images/perfil/<?php echo $foto_usuario ?>" alt="Foto do usuário" style="width: 80px;" id="target-usu">
                        </div>
                        <input type="hidden" name="id-usuario" value="<?php echo $id_usuario ?>">
                    </div>
                </div>
                <div id="msg-perfil" class="centro"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal Perfil-->

<!-- Modal Relatório de Contas -->
<div class="modal fade" id="RelCon" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title texto-menor-bold">Relatório de Contas
                    <span class="texto-menor">(
                        <a href="#" onclick="datas('1980-01-01', 'tudo-Con', 'Con')">
                            <span class="cor-rel" id="tudo-Con">Tudo</span>
                        </a> |
                        <a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Con', 'Con')">
                            <span id="hoje-Con">Hoje</span>
                        </a> |
                        <a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Con', 'Con')">
                            <span class="cor-rel" id="mes-Con">Mês</span>
                        </a> |
                        <a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Con', 'Con')">
                            <span class="cor-rel" id="ano-Con">Ano</span>
                        </a>
                        )</span>
                </h4>
                <button type="button" class="close mg-t--20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="rel/contas_class.php" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Con" value="<?php echo date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Con" value="<?php echo date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pago</label>
                                <select class="form-control" name="pago">
                                    <option value="">Todas</option>
                                    <option value="Sim">Somente Pagas</option>
                                    <option value="Não">Pendentes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pagar | Receber</label>
                                <select class="form-control" name="tabela">
                                    <option value="pagar">Contas à Pagar</option>
                                    <option value="receber">Contas à Receber</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Consultar Por</label>
                                <select class="form-control" name="busca">
                                    <option value="data_vencimento">Data de Vencimento</option>
                                    <option value="data_pagamento">Data de Pagamento</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal Relatório de Contas -->

<!-- Modal Relatório de Lucro -->
<div class="modal fade" id="RelLucro" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title texto-menor-bold">Demonstrativo de Lucro
                    <span class="texto-menor">(
                        <a href="#" onclick="datas('1980-01-01', 'tudo-Luc', 'Luc')">
                            <span class="cor-rel" id="tudo-Luc">Tudo</span>
                        </a> |
                        <a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Luc', 'Luc')">
                            <span id="hoje-Luc">Hoje</span>
                        </a> |
                        <a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Luc', 'Luc')">
                            <span class="cor-rel" id="mes-Luc">Mês</span>
                        </a> |
                        <a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Luc', 'Luc')">
                            <span class="cor-rel" id="ano-Luc">Ano</span>
                        </a>
                        )</span>
                </h4>
                <button type="button" class="close mg-t--20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="rel/lucro_class.php" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input type="date" class="form-control"
                                    name="dataInicial"
                                    id="dataInicialRel-Luc"
                                    value="<?php echo date('Y-m-d') ?>"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input type="date"
                                    class="form-control"
                                    name="dataFinal"
                                    id="dataFinalRel-Luc"
                                    value="<?php echo date('Y-m-d') ?>"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal Relatório de Lucro -->

<!-- Modal Relatório de Vendas -->
<div class="modal fade" id="RelVen" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title texto-menor-bold">Relatório de Vendas
                    <span class="texto-menor">(
                        <a href="#" onclick="datas('1980-01-01', 'tudo-Ven', 'Ven')">
                            <span class="cor-rel" id="tudo-Ven">Tudo</span>
                        </a> /
                        <a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Ven', 'Ven')">
                            <span id="hoje-Ven">Hoje</span>
                        </a> /
                        <a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Con', 'Ven')">
                            <span class="cor-rel" id="mes-Ven">Mês</span>
                        </a> /
                        <a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Ven', 'Ven')">
                            <span class="cor-rel" id="ano-Ven">Ano</span>
                        </a>
                        )</span>
                </h4>
                <button type="button" class="close mg-t--20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="rel/vendas_class.php" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Ven" value="<?php echo date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Ven" value="<?php echo date('Y-m-d') ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">Todas</option>
                                    <option value="finalizado">Finalizadas</option>
                                    <option value="cancelado">Canceladas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Forma PGTO</label>
                                <select class="form-control" name="forma_pgto">
                                    <option value="">Todas</option>
                                    <option value="dinheiro">Dinheiro</option>
                                    <option value="pix">PIX</option>
                                    <option value="credito">Cartão de Crédito</option>
                                    <option value="debito">Cartão de Débito</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal Relatório de Vendas -->

<!-- BUSCA CEP -->
<script type="text/javascript" src="../../js/buscaCepModal.js"></script>
<!-- MÁSCARA -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script type="text/javascript" src="../../js/mascaras.js"></script>
<!-- VALIDAR CPF -->
<script type="text/javascript" src="../../js/validarCPF.js"></script>
<!-- VALIDAR CNPJ -->
<script type="text/javascript" src="../../js/validarCNPJ.js"></script>
<!-- SCRIPT TROCA FOTO -->
<script type="text/javascript">
    function carregarImgPerfil() {
        var target = document.getElementById('target-usu');
        var file = document.querySelector("#foto-perfil").files[0];
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
<!-- FIM SCRIPT TROCA FOTO -->

<!-- SCRIPT TROCA LOGO -->
<script type="text/javascript">
    function carregarImgLogotipo() {
        var target = document.getElementById('target-logo');
        var file = document.querySelector("#logotipo").files[0];
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
<!-- FIM SCRIPT TROCA LOGO -->

<!-- SCRIPT TROCA ÍCONE -->
<script type="text/javascript">
    function carregarImgIcone() {
        var target = document.getElementById('target-ico');
        var file = document.querySelector("#icone").files[0];
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
<!-- FIM SCRIPT TROCA ÍCONE -->

<!-- SCRIPT TROCA LOGO REL -->
<script type="text/javascript">
    function carregarImgLogoRel() {
        var target = document.getElementById('target-logo-rel');
        var file = document.querySelector("#logo_rel").files[0];
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
<!-- FIM SCRIPT TROCA LOGO REL -->

<!-- AJAX SALVA EDITA USUARIO -->
<script type="text/javascript">
    $("#form-perfil").submit(function() {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "editar-perfil.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#msg-perfil').text('');
                $('#msg-perfil').removeClass('text-danger text-success')
                if (mensagem.trim() == "Editado com Sucesso") {
                    $('#btn-fechar-perfil').click();
                    location.reload();
                } else {
                    $('#msg-perfil').addClass('text-danger')
                    $('#msg-perfil').text(mensagem)
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
</script>
<!-- FIM AJAX SALVA EDITA USUARIO -->

<!-- AJAX SALVA EDITA CONFIGURAÇÕES -->
<script type="text/javascript">
    $("#form-config").submit(function() {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "editar-config.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#msg-config').text('');
                $('#msg-config').removeClass('text-danger text-success')
                if (mensagem.trim() == "Editado com Sucesso") {
                    $('#btn-fechar-config').click();
                    location.reload();
                } else {
                    $('#msg-config').addClass('text-danger')
                    $('#msg-config').text(mensagem)
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
</script>
<!-- FIM AJAX SALVA EDITA CONFIGURAÇÕES -->

<!-- SCRIPT DATAS PARA OS RELATÓRIOS -->
<script type="text/javascript">
    function datas(data, id, campo) {
        var data_atual = "<?= $data_atual ?>";
        var separarData = data_atual.split("-");
        var mes = separarData[1];
        var ano = separarData[0];
        var separarId = id.split("-");
        if (separarId[0] == 'tudo') {
            data_atual = '2100-12-31';
        }
        if (separarId[0] == 'ano') {
            data_atual = ano + '-12-31';
        }
        if (separarId[0] == 'mes') {
            if (mes == 4 || mes == 6 || mes == 9 || mes == 11) {
                data_atual = ano + '-' + mes + '-30';
            } else if (mes == 2) {
                // Verifica se o ano é bissexto
                if ((ano % 4 == 0 && ano % 100 != 0) || (ano % 400 == 0)) {
                    data_atual = ano + '-' + mes + '-29';
                } else {
                    data_atual = ano + '-' + mes + '-28';
                }
            } else {
                data_atual = ano + '-' + mes + '-31';
            }
        }
        $('#dataInicialRel-' + campo).val(data);
        $('#dataFinalRel-' + campo).val(data_atual);
        document.getElementById('hoje-' + campo).style.color = "#000";
        document.getElementById('mes-' + campo).style.color = "#000";
        document.getElementById(id).style.color = "blue";
        document.getElementById('tudo-' + campo).style.color = "#000";
        document.getElementById('ano-' + campo).style.color = "#000";
        document.getElementById(id).style.color = "blue";
    }
</script>
<!-- FIM SCRIPT DATAS PARA OS RELATÓRIOS -->

<!-- ATUALIZAR PEDIDOS -->
<script>
    var seg = parseInt('<?= $segundos ?>');

    function atualizarNotificacoesPedidos() {
        $.ajax({
            url: 'atualizar-pedidos.php',
            method: 'POST',
            success: function(data) {
                $('#atualizar-pedidos').html(data);
            }
        });
    }

    // Atualiza a cada 10 segundos
    atualizarNotificacoesPedidos();
    setInterval(atualizarNotificacoesPedidos, seg);
</script>
<!-- FIM ATUALIZAR PEDIDOS -->