<?php
require_once('../conexao.php');
require_once('verificar.php');
?>
<ul class="nofitications-dropdown">
    <?php
    $query = $pdo->query("SELECT * FROM vendas WHERE data_pagamento = curDate() 
                                                  AND status_venda = 'Iniciado'");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = count($res);
    if ($total_reg > 1) {
        $texto_pedidos = 'Você tem ' . str_pad($total_reg, 2, '0', STR_PAD_LEFT) . ' novos pedidos!';
    } else if ($total_reg == 1) {
        $texto_pedidos = 'Você tem ' . str_pad($total_reg, 2, '0', STR_PAD_LEFT) . ' novo pedido!';
    } else {
        $texto_pedidos = 'Você NÃO tem novos pedidos!';
    }
    ?>
    <li class="dropdown head-dpdn">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-receipt branco"></i><span class="badge"><?php echo $total_reg ?></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <div class="notification_header">
                    <h3><?php echo $texto_pedidos; ?></h3>
                </div>
            </li>
            <?php
            for ($i = 0; $i < $total_reg; $i++) {
                foreach ($res[$i] as $key => $value) {
                }
                $idPedido = $res[$i]['id'];
                $cliente = $res[$i]['cliente'];
                $valor = $res[$i]['valor_compra'];
                $valorF = 'R$ ' . number_format($valor, 2, ',', '.');
                $queryCliente = $pdo->query("SELECT * FROM cliente WHERE id = '$cliente'");
                $resCliente = $queryCliente->fetchAll(PDO::FETCH_ASSOC);
                $totalClientes = count($resCliente);
                if ($totalClientes > 0) {
                    $nomeCliente = $resCliente[0]['nome'];
                } else {
                    $nomeCliente = 'Nenhum!';
                }
            ?>
                <li class="linha">
                    <a href="#">
                        <div class="user_img"><img src="images/" alt=""></div>
                        <div class="notification_desc">
                            <p>
                                <strong>
                                    Pedido:
                                </strong>
                                <?php echo str_pad($idPedido, 2, '0', STR_PAD_LEFT) ?>
                            </p>
                            <p>
                                <span>
                                    <strong>
                                        Cliente:
                                    </strong>
                                    <?php echo $nomeCliente ?>
                                </span>
                            </p>
                            <p>
                                <span>
                                    <strong>
                                        Valor:
                                    </strong>
                                    <span class="fonte-verde">
                                        <?php echo $valorF ?>
                                    </span>
                                </span>
                            </p>
                        </div>
                        <div class="clearfix"></div>
                    </a>
                </li>
            <?php } ?>
            <li>
                <div class="notification_bottom">
                    <a href="index.php?pagina=pedidos">Ir para os Pedidos</a>
                </div>
            </li>
        </ul>
    </li>
</ul>