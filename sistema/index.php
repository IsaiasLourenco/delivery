<?php
require_once('conexao.php');
$senha = '0808';
$senha_crip = md5('$senha');
//CRIAR UM USUÁRIO ADM
$query = $pdo->query("SELECT * FROM usuarios WHERE nivel = 'Administrador' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total = count($res);
if ($total == 0) {
    $pdo->query("INSERT INTO usuarios SET   nome = 'Isaias Lourenço', 
                                            email = '$email_sistema', 
                                            cpf = '247.074.358-31', 
                                            telefone = '$telefone_sistema', 
                                            cep = '13843-184', 
                                            rua = 'Mocóca', 
                                            numero = '880', 
                                            bairro = 'Loteamento Parque Itacolomy', 
                                            cidade = 'Mogi Guaçu', 
                                            estado = 'SP', 
                                            senha ='$senha', 
                                            senha_crip = '$senha_crip', 
                                            nivel = 'Administrador', 
                                            ativo = 'Sim',
                                            foto = 'sem-foto.jpg', 
                                            data_cad = curDate()");
}

$query_config = $pdo->query("SELECT * FROM config");
$res_config = $query_config->fetchAll(PDO::FETCH_ASSOC);
$nome_sistema = $res_config[0]['nome_sistema'];
$logo_sistema = $res_config[0]['logotipo'];
$icone_sistema = $res_config[0]['icone'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nome_sistema; ?></title>
    <!-- FAVICON -->
    <link rel="shortcut icon" href="../img/<?php echo $icone_sistema;?>" type="image/x-icon">
    <!-- BOOTSTRAP   -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="../css/login.css">

<body>
    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card px-5 py-5" id="form1">
                    <div v-if="!submitted">
                        <div class="img-logo">
                            <img src="../img/<?php echo $logo_sistema;?>" alt="Logotipo do sistema" class="img-logo">
                        </div>
                        <form action="autenticar.php" method="POST">
                            <div class="forms-inputs mb-4"> <span>Email ou CPF</span>
                                <input class="form-control"
                                    type="text"
                                    name="email"
                                    required
                                    autofocus>
                            </div>
                            <div class="forms-inputs mb-4"> <span>Senha</span>
                                <input class="form-control"
                                    type="password"
                                    name="senha"
                                    required>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-dark w-100">Login</button>
                            </div>
                            <div class="mb-3 centro">
                                <a type="button"
                                    class="btn btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalRecuperarSenha">
                                    Recuperar Senha
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<!-- MODAL PARA RECUPERAR SENHA -->
<div class="modal fade" id="ModalRecuperarSenha" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Recuperar Senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-recuperar">
                    <div class="row">
                        <div class="col-8">
                            <input type="email" name="recuperar" id="recuperar" class="form-control" placeholder="Digite seu e-mail..." required>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-rec">Recuperar</button>
                        </div>
                    </div>
                    <br>
                    <div id="mensagem-recuperar" style="text-align: center;"></div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<!-- FIM MODAL PARA RECUPERAR SENHA -->

<!-- CDN JQUERY -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- AJAX RECUPERAR SENHA -->
<script type="text/javascript">
    $("#form-recuperar").submit(function() {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "recuperar-senha.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem-recuperar').text('');
                $('#mensagem-recuperar').removeClass()
                if (mensagem.trim() == "Recuperado com Sucesso") {
                    //$('#btn-fechar-rec').click();					
                    $('#recuperar').val('');
                    $('#mensagem-recuperar').addClass('text-success')
                    $('#mensagem-recuperar').text('Sua Senha foi enviada para o Email')
                } else {
                    $('#mensagem-recuperar').addClass('text-danger')
                    $('#mensagem-recuperar').text(mensagem)
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
</script>
<!-- FIM AJAX RECUPERAR SENHA -->