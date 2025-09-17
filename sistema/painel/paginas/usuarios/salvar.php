<?php
require_once('../../../conexao.php');
$tabela = 'usuarios';
$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$cep = $_POST['cep'];
$rua = $_POST['rua'];
$numero = $_POST['numero'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$senha = $_POST['senha'];
$senha_crip = md5($senha);
$conf_senha = $_POST['conf-senha'];
$nivel = $_POST['nivel'];
$ativo = $_POST['ativo'];

if ($senha != $conf_senha) {
    echo 'As senhas não se coincidem!! Elas PRECISAM ser iguais!!';
    exit;
}

//VALIDAR EMAIL
$query = $pdo->query("SELECT * FROM usuarios WHERE email = '$email'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 and $id != $res[0]['id']) {
    echo 'EMAIL já cadastrado!!';
    exit;
}

//VALIDAR CPF
$query = $pdo->query("SELECT * FROM usuarios WHERE cpf = '$cpf'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 and $id != $res[0]['id']) {
    echo 'CPF já cadastrado!!';
    exit;
}

//validar troca da foto
$query = $pdo->query("SELECT * FROM usuarios where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if ($total_reg > 0) {
    $foto = $res[0]['foto'];
} else {
    $foto = 'sem-foto.jpg';
}

//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../images/perfil/' . $nome_img;

$imagem_temp = $_FILES['foto']['tmp_name'];

if (@$_FILES['foto']['name'] != "") {
    $ext = pathinfo($nome_img, PATHINFO_EXTENSION);
    if ($ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'gif') {

        //EXCLUO A FOTO ANTERIOR
        if ($foto != "sem-foto.jpg") {
            @unlink('../../images/perfil/' . $foto);
        }

        $foto = $nome_img;

        move_uploaded_file($imagem_temp, $caminho);
    } else {
        echo 'Extensão de Imagem não permitida!';
        exit();
    }
}

if ($id == "" || $id == null) {
    // INSERT (novo registro)
    $query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, 
                                                    email = :email, 
                                                    cpf = :cpf,
                                                    telefone = :telefone,
                                                    cep = :cep,
                                                    rua = :rua,
                                                    numero = :numero,
                                                    bairro = :bairro,
                                                    cidade = :cidade,
                                                    estado = :estado,
                                                    senha = :senha,
                                                    senha_crip = '$senha_crip',
                                                    nivel = :nivel,
                                                    ativo = :ativo,
                                                    foto = '$foto',
                                                    data_cad = CURDATE()");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET nome = :nome, 
                                                    email = :email, 
                                                    cpf = :cpf,
                                                    telefone = :telefone,
                                                    cep = :cep,
                                                    rua = :rua,
                                                    numero = :numero,
                                                    bairro = :bairro,
                                                    cidade = :cidade,
                                                    estado = :estado,
                                                    senha = :senha,
                                                    senha_crip = '$senha_crip',
                                                    nivel = :nivel,
                                                    ativo = :ativo,
                                                    foto = '$foto'
                                                    WHERE id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":cep", "$cep");
$query->bindValue(":rua", "$rua");
$query->bindValue(":numero", "$numero");
$query->bindValue(":bairro", "$bairro");
$query->bindValue(":cidade", "$cidade");
$query->bindValue(":estado", "$estado");
$query->bindValue(":senha", "$senha");
$query->bindValue(":nivel", "$nivel");
$query->bindValue(":ativo", "$ativo");
$query->execute();
echo 'Salvo com Sucesso';
?>