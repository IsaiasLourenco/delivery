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

if (@$_GET['pagina'] != "") {
    $pagina = @$_GET['pagina'];
} else {
    $pagina = 'home';
}

?>
<!DOCTYPE HTML>
<html>

<head>
    <title><?php echo $nome_sistema; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- FAVICON -->
    <link rel="shortcut icon" href="<?php echo $url_base; ?>../../img/<?php echo $icone_sistema; ?>" type="image/x-icon">
    <!-- FAVICON -->
    <link rel="shortcut icon" href="../../img/<?php echo $icone_sistema;?>" type="image/x-icon">
    <script type="application/x-javascript">
        addEventListener("load", function() {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <!-- Bootstrap icons     -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />

    <!-- font-awesome icons CSS -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- //font-awesome icons CSS-->

    <!-- side nav css file -->
    <link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css' />
    <!-- //side nav css file -->

    <!-- side bar left customizada por Isaias -->
    <link rel="stylesheet" href="css/custom-sidebar-left.css">

    <!-- js-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>

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
                                <img src="../../img/<?php echo $logo_sistema?>" alt="Logo do sistema" class="logo"> Delivery
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
                                <a href="#">
                                    <i class="fa fa-users"></i>
                                    <span>Pessoas</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-angle-right"></i> Funcionários</a></li>
                                    <li><a href="index.php?pagina=usuarios"><i class="fa fa-angle-right"></i> Usuários</a></li>
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
                    <ul class="nofitications-dropdown">
                        <li class="dropdown head-dpdn">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope"></i><span class="badge">4</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="notification_header">
                                        <h3>You have 3 new messages</h3>
                                    </div>
                                </li>
                                <li><a href="#">
                                        <div class="user_img"><img src="images/1.jpg" alt=""></div>
                                        <div class="notification_desc">
                                            <p>Lorem ipsum dolor amet</p>
                                            <p><span>1 hour ago</span></p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </a></li>
                                <li class="odd"><a href="#">
                                        <div class="user_img"><img src="images/4.jpg" alt=""></div>
                                        <div class="notification_desc">
                                            <p>Lorem ipsum dolor amet </p>
                                            <p><span>1 hour ago</span></p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </a></li>
                                <li><a href="#">
                                        <div class="user_img"><img src="images/3.jpg" alt=""></div>
                                        <div class="notification_desc">
                                            <p>Lorem ipsum dolor amet </p>
                                            <p><span>1 hour ago</span></p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </a></li>
                                <li><a href="#">
                                        <div class="user_img"><img src="images/2.jpg" alt=""></div>
                                        <div class="notification_desc">
                                            <p>Lorem ipsum dolor amet </p>
                                            <p><span>1 hour ago</span></p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </a></li>
                                <li>
                                    <div class="notification_bottom">
                                        <a href="#">See all messages</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
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
                                        <span><?php echo $nivel_usuario; ?></span>
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
                <button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                            <input type="text" class="form-control" id="cidade-perfil" name="cidade" value="<?php echo $cidade_sistema ?>" readonly>
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
                        <div class="col-md-4">
                            <label for="tipoRel">Tipo Relatório</label>
                            <select class="form-control" name="tipoRel">
                                <option value="PDF" <?php if ($tipo_rel == 'PDF') { ?> selected <?php } ?>>PDF</option>
                                <option value="HTML" <?php if ($tipo_rel == 'HTML') { ?> selected <?php } ?>>HTML</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="cards">Cards</label>
                            <select class="form-control" name="cards">
                                <option value="Cores" <?php if ($cards == 'Cores') { ?> selected <?php } ?>>Cores</option>
                                <option value="Foto" <?php if ($cards == 'Foto') { ?> selected <?php } ?>>Foto</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="pedidos">Pedidos Whatsapp</label>
                            <select class="form-control" name="pedidos">
                                <option value="Sim" <?php if ($pedidos == 'Sim') { ?> selected <?php } ?>>Sim</option>
                                <option value="Não" <?php if ($pedidos == 'Não') { ?> selected <?php } ?>>Não</option>
                            </select>
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
                            <label for="previsao">Previsão Entrega</label>
                            <input type="text" class="form-control" id="previsao" name="previsao" value="<?php echo $previsao_entrega ?>" required>
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
                            <input type="text" class="form-control" id="abertura" name="abertura" value="<?php echo $abertura ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="fechamento">Horário Fechamento</label>
                            <input type="text" class="form-control" id="fechamento" name="fechamento" value="<?php echo $fechamento ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="txt_fechamento">Texto Fechamento</label>
                            <input type="text" class="form-control" id="txt_fechamento" name="txt_fechamento" value="<?php echo $txt_fechamento ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="logotipo">Logotipo(*.png)</label>
                            <input type="file" class="form-control" id="logotipo" name="logotipo" onchange="carregarImgLogotipo()">
                        </div>
                        <div class="col-md-2">
                            <img src="../../img/<?php echo $logo_sistema; ?>" alt="Logotipo" style="width: 80px;" id="target-logo">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="icone">Ícone(*.png)</label>
                            <input type="file" class="form-control" id="icone" name="icone" onchange="carregarImgIcone()">
                        </div>
                        <div class="col-md-2">
                            <img src="../../img/<?php echo $icone_sistema; ?>" alt="Icone" style="width: 80px;" id="target-ico">
                        </div>
                        <div class="col-md-4">
                            <label for="logo_rel">Logotipo Relatório(*.jpg)</label>
                            <input type="file" class="form-control" id="logo_rel" name="logo_rel" onchange="carregarImgLogoRel()">
                        </div>
                        <div class="col-md-2">
                            <img src="../../img/<?php echo $logo_rel; ?>" alt="Logotipo do Relatório" style="width: 80px;" id="target-logo-rel">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id_sistema ?>">
                    </div>
                    <div id="msg-perfil" class="centro"></div>
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
                <button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                        <div class="col-md-3">
                            <label for="nivel">Nível</label>
                            <input type="text" class="form-control" id="nivel-perfil" name="nivel" value="<?php echo $nivel_usuario ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label for="ativo">Ativo</label>
                            <input type="text" class="form-control" id="ativo-perfil" name="ativo" value="<?php echo $ativo_usuario ?>" required>
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
                    <div id="msg-perfil" class="centro"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal Perfil-->

<!-- BUSCA CEP -->
<script type="text/javascript" src="../../js/buscaCepModal.js"></script>
<!-- MÁSCARA -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script type="text/javascript" src="../../js/mascaras.js"></script>

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