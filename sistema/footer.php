    <?php
    require_once('conexao.php');
    $query_sistema = $pdo->query("SELECT * FROM config");
    $res_sistema = $query_sistema->fetchAll(PDO::FETCH_ASSOC);
    $dev = $res_sistema[0]['desenvolvedor'];
    $site_dev = $res_sistema[0]['site_dev'];
    ?>
    <footer class="rodape">
        &copy; 2025 <?php echo $dev; ?> &nbsp;
        <a href="<?php echo $site_dev;?>"
            target="_blank"
            class="link-neutro">
            Acesse → <?php echo $site_dev; ?>
        </a>
    </footer>