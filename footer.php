    <?php require_once('sistema/conexao.php');?>
    <footer class="rodape">
        <?php echo $endereco_sistema;?> &nbsp;
        <a href="https://api.whatsapp.com/send?phone=<?php echo $telefone_url;?>&text=Ol%C3%A1!%20Gostaria%20de%20fazer%20um%20pedido%20no%20seu%20Delivery."
            target="_blank"
            class="link-neutro">
            <i class="bi bi-whatsapp text-success"></i>&nbsp;<?php echo $telefone_sistema;?>
        </a>
    </footer>