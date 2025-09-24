<?php
require_once('../../../conexao.php');
$tabela = 'cliente';
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

//Verifica se já existe o bairro na tabela bairros
$queryBairro = $pdo->query("SELECT * FROM bairros WHERE nome = '$bairro'");
$resBairro = $queryBairro->fetchAll(PDO::FETCH_ASSOC);
if (count($resBairro) > 0) {
    $id_bairro = $resBairro[0]['id'];
} else {
    $queryBairro = $pdo->prepare("INSERT INTO bairros SET nome = :bairro");
    $queryBairro->bindValue(":bairro", $bairro);
    $queryBairro->execute();
    $id_bairro = $pdo->lastInsertId();
}

//VALIDAR EMAIL
$query = $pdo->query("SELECT * FROM $tabela WHERE email = '$email'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 and $id != $res[0]['id']) {
    echo 'EMAIL já cadastrado!!';
    exit;
}

//VALIDAR CPF
$query = $pdo->query("SELECT * FROM $tabela WHERE cpf = '$cpf'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 and $id != $res[0]['id']) {
    echo 'CPF já cadastrado!!';
    exit;
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
                                                    bairro_id = '$id_bairro',
                                                    cidade = :cidade,
                                                    estado = :estado,
                                                    data_cad = CURDATE()");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET nome = :nome, 
                                               email = :email, 
                                               cpf = :cpf,
                                               telefone = :telefone,
                                               cep = :cep,
                                               rua = :rua,
                                               numero = :numero,
                                               bairro_id = '$id_bairro',
                                               cidade = :cidade,
                                               estado = :estado
                                               WHERE id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":cep", "$cep");
$query->bindValue(":rua", "$rua");
$query->bindValue(":numero", "$numero");
$query->bindValue(":cidade", "$cidade");
$query->bindValue(":estado", "$estado");
$query->execute();
echo 'Salvo com Sucesso';
