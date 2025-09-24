<?php
require_once('../../../conexao.php');
$tabela = 'bairros';
$id = $_POST['id'];
$nome = $_POST['nome'];
$valor = $_POST['valor'];
$valor = str_replace(['R$', '.', ','], ['', '', '.'], $valor);

//Validar Nome
$query = $pdo->query("SELECT * FROM $tabela WHERE nome = '$nome'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 AND $id != $res[0]['id']) {
    echo "Bairro já cadastrado!!";
    exit;
}

if ($id == "" || $id == null) {
    // INSERT (novo registro)
    $query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, valor = :valor");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET nome = :nome, valor = :valor
                                                    WHERE id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":valor", "$valor");
$query->execute();
echo 'Salvo com Sucesso';
?>