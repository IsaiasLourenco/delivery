<?php
require_once('../../../conexao.php');
$tabela = 'cargos';
$id = $_POST['id'];
$nome = $_POST['nome'];

//Validar Nome
$query = $pdo->query("SELECT * FROM $tabela WHERE nome = '$nome'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 AND $id != $res[0]['id']) {
    echo "Cargo já cadastrado!!";
    exit;
}

if ($id == "" || $id == null) {
    // INSERT (novo registro)
    $query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET nome = :nome
                                                    WHERE id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->execute();
echo 'Salvo com Sucesso';
?>