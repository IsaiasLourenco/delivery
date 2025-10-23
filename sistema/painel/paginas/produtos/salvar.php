<?php
require_once('../../../conexao.php');
require_once('../../../../funcoes.php');
$tabela = 'produtos';
$id = $_POST['id'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$categoria = $_POST['categoria'];
$valor_compra = $_POST['valor_compra'];
$valor_compra = str_replace(['R$', '.', ','], ['', '', '.'], $valor_compra);
$valor_venda = $_POST['valor_venda'];
$valor_venda = str_replace(['R$', '.', ','], ['', '', '.'], $valor_venda);
$estoque = $_POST['estoque'];
$nivel_estoque = $_POST['nivel_estoque'];
$ativo = $_POST['ativo'];
$tem_estoque = $_POST['tem_estoque'];
$url = gerarSlug($nome);

//VALIDAR NOME
$query = $pdo->query("SELECT * FROM $tabela WHERE nome = '$nome'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 and $id != $res[0]['id']) {
    echo 'Produto já cadastrado!!';
    exit;
}

//validar troca da foto
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
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

$caminho = '../../images/produtos/' . $nome_img;

$imagem_temp = $_FILES['foto']['tmp_name'];

if (@$_FILES['foto']['name'] != "") {
    $ext = pathinfo($nome_img, PATHINFO_EXTENSION);
    if ($ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'gif') {

        //EXCLUO A FOTO ANTERIOR
        if ($foto != "sem-foto.jpg") {
            @unlink('../../images/produtos/' . $foto);
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
                                                    categoria = :categoria,
                                                    valor_compra = :valor_compra,
                                                    valor_venda = :valor_venda,
                                                    estoque = :estoque,
                                                    nivel_estoque = :nivel_estoque,
                                                    ativo = :ativo,
                                                    tem_estoque = :tem_estoque,
                                                    foto = '$foto',
                                                    url = '$url'");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET nome = :nome, 
                                                descricao = :descricao, 
                                                categoria = :categoria,
                                                valor_compra = :valor_compra,
                                                valor_venda = :valor_venda,
                                                estoque = :estoque,
                                                nivel_estoque = :nivel_estoque,
                                                ativo = :ativo,
                                                tem_estoque = :tem_estoque,
                                                foto = '$foto',
                                                url = '$url'
                                                WHERE id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":categoria", "$categoria");
$query->bindValue(":valor_compra", "$valor_compra");
$query->bindValue(":valor_venda", "$valor_venda");
$query->bindValue(":estoque", "$estoque");
$query->bindValue(":nivel_estoque", "$nivel_estoque");
$query->bindValue(":ativo", "$ativo");
$query->bindValue(":tem_estoque", "$tem_estoque");
$query->execute();
echo 'Salvo com Sucesso';
