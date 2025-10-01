<?php
require_once('../../../conexao.php');
$tabela = 'fornecedores';
$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$cnpj = $_POST['cnpj'];
$telefone = $_POST['telefone'];
$cep = $_POST['cep'];
$rua = $_POST['rua'];
$numero = $_POST['numero'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$tipo_chave = $_POST['tipo_chave'];
$chave_pix = $_POST['chave_pix'];

//VALIDAR EMAIL
$query = $pdo->query("SELECT * FROM $tabela WHERE email = '$email'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 and $id != $res[0]['id']) {
    echo 'EMAIL já cadastrado!!';
    exit;
}

//VALIDAR CNPJ
$query = $pdo->query("SELECT * FROM $tabela WHERE cnpj = '$cnpj'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 and $id != $res[0]['id']) {
    echo 'CNPJ já cadastrado!!';
    exit;
}

if ($id == "" || $id == null) {
    // INSERT (novo registro)
    $query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, 
                                                    email = :email, 
                                                    cnpj = :cnpj,
                                                    telefone = :telefone,
                                                    cep = :cep,
                                                    rua = :rua,
                                                    numero = :numero,
                                                    bairro = :bairro,
                                                    cidade = :cidade,
                                                    estado = :estado,
                                                    data_cad = CURDATE(),
                                                    tipo_chave = :tipo_chave,
                                                    chave_pix = :chave_pix");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET nome = :nome, 
                                               email = :email, 
                                               cnpj = :cnpj,
                                               telefone = :telefone,
                                               cep = :cep,
                                               rua = :rua,
                                               numero = :numero,
                                               bairro = :bairro,
                                               cidade = :cidade,
                                               estado = :estado,
                                               tipo_chave = :tipo_chave,
                                               chave_pix = :chave_pix
                                               WHERE id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":cnpj", "$cnpj");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":cep", "$cep");
$query->bindValue(":rua", "$rua");
$query->bindValue(":numero", "$numero");
$query->bindValue(":bairro", "$bairro");
$query->bindValue(":cidade", "$cidade");
$query->bindValue(":estado", "$estado");
$query->bindValue(":tipo_chave", "$tipo_chave");
$query->bindValue(":chave_pix", "$chave_pix");
$query->execute();
echo 'Salvo com Sucesso';
