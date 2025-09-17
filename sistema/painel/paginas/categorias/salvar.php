<?php
require_once('../../../conexao.php');
$tabela = 'categorias';
$id = $_POST['id'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$cor = $_POST['cor'];
$ativo = $_POST['ativo'];

//Validar Nome
$foto = 'sem-foto.jpg';
$query = $pdo->query("SELECT * FROM $tabela WHERE nome = '$nome'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 AND $id != $res[0]['id']) {
    echo "Categoria já cadastrada!!";
    exit;
}

//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../images/categorias/' . $nome_img;

$imagem_temp = $_FILES['foto']['tmp_name'];

if (@$_FILES['foto']['name'] != "") {
    $ext = pathinfo($nome_img, PATHINFO_EXTENSION);
    if ($ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'gif') {

        //EXCLUO A FOTO ANTERIOR
        if ($foto != "sem-foto.jpg") {
            @unlink('../../images/categorias/' . $foto);
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
                                                    descricao = :descricao,
                                                    foto = :foto,
                                                    cor = :cor,
                                                    ativo = :ativo");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET  nome = :nome,
                                                descricao = :descricao,
                                                foto = :foto,
                                                cor = :cor,
                                                ativo = :ativo
                                                WHERE id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":foto", "$foto");
$query->bindValue(":cor", "$cor");
$query->bindValue(":ativo", "$ativo");
$query->execute();
echo 'Salvo com Sucesso';
?>