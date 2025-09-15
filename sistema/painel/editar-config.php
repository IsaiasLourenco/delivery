<?php
require_once('../conexao.php');
$id = $_POST['id'];
$nome = $_POST['nome_sistema'];
$email = $_POST['email_sistema'];
$telefone = $_POST['telefone_sistema'];
$telefone_fixo = $_POST['telefone_fixo'];
$cnpj = $_POST['cnpj_sistema'];
$cep = $_POST['cep-sistema'];
$rua = $_POST['rua-sistema'];
$numero = $_POST['numero-sistema'];
$bairro = $_POST['bairro-sistema'];
$cidade = $_POST['cidade-sistema'];
$estado = $_POST['estado-sistema'];
$instagram = $_POST['instagram'];
$tipo_rel = $_POST['tipoRel'];
$cards = $_POST['cards'];
$pedidos = $_POST['pedidos'];
$dev = $_POST['dev'];
$site = $_POST['site'];
$previsao = $_POST['previsao'];
$aberto = $_POST['aberto'];
$abertura = $_POST['abertura'];
$fechamento = $_POST['fechamento'];
$txt_fechamento = $_POST['txt_fechamento'];

//validar troca das fotos
$query = $pdo->query("SELECT * FROM config where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if ($total_reg > 0) {
    $logotipo = $res[0]['logotipo'];
    $icone = $res[0]['icone'];
    $logo_rel = $res[0]['logo_rel'];
} else {
    $logotipo = 'sem-foto.jpg';
    $icone = 'sem-foto.jpg';
    $logo_rel = 'sem-foto.jpg';
}


//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_logo = date('d-m-Y H:i:s') . '-' . @$_FILES['logotipo']['name'];
$nome_icone = date('d-m-Y H:i:s') . '-' . @$_FILES['icone']['name'];
$nome_logo_rel = date('d-m-Y H:i:s') . '-' . @$_FILES['logo_rel']['name'];
$nome_logo = preg_replace('/[ :]+/', '-', $nome_logo);
$nome_icone = preg_replace('/[ :]+/', '-', $nome_icone);
$nome_logo_rel = preg_replace('/[ :]+/', '-', $nome_logo_rel);

$caminho_logo = '../../img/' . $nome_logo;
$caminho_icone = '../../img/' . $nome_icone;
$caminho_logo_rel = '../../img/' . $nome_logo_rel;

$imagem_logo_temp = $_FILES['logotipo']['tmp_name'];
$imagem_icone_temp = $_FILES['icone']['tmp_name'];
$imagem_logo_rel_temp = $_FILES['logo_rel']['tmp_name'];

if (@$_FILES['logotipo']['name'] != "") {
    $ext_logo = pathinfo($nome_logo, PATHINFO_EXTENSION);
    if ($ext_logo == 'png' or $ext_logo == 'jpg' or $ext_logo == 'jpeg' or $ext_logo == 'gif') {
       //EXCLUO A FOTO ANTERIOR
        if ($logotipo != "sem-foto.jpg") {
            @unlink('../../img/' . $logotipo);
        }

        $logotipo = $nome_logo;

        move_uploaded_file($imagem_logo_temp, $caminho_logo);
    } else {
        echo 'Extensão de Imagem não permitida!';
        exit();
    }
}

if (@$_FILES['icone']['name'] != "") {
    $ext_icone = pathinfo($nome_icone, PATHINFO_EXTENSION);
    if ($ext_icone == 'png' or $ext_icone == 'jpg' or $ext_icone == 'jpeg' or $ext_icone == 'gif') {
       //EXCLUO A FOTO ANTERIOR
        if ($icone != "sem-foto.jpg") {
            @unlink('../../img/' . $icone);
        }

        $icone = $nome_icone;

        move_uploaded_file($imagem_icone_temp, $caminho_icone);
    } else {
        echo 'Extensão de Imagem não permitida!';
        exit();
    }
}

if (@$_FILES['logo_rel']['name'] != "") {
    $ext_logo_rel = pathinfo($nome_logo_rel, PATHINFO_EXTENSION);
    if ($ext_logo_rel == 'png' or $ext_logo_rel == 'jpg' or $ext_logo_rel == 'jpeg' or $ext_logo_rel == 'gif') {
       //EXCLUO A FOTO ANTERIOR
        if ($logo_rel != "sem-foto.jpg") {
            @unlink('../../img/' . $logo_rel);
        }

        $logo_rel = $nome_logo_rel;

        move_uploaded_file($imagem_logo_rel_temp, $caminho_logo_rel);
    } else {
        echo 'Extensão de Imagem não permitida!';
        exit();
    }
}

$query = $pdo->prepare("UPDATE config SET nome_sistema = :nome, 
                                            email_sistema = :email, 
                                            telefone_sistema = :telefone,
                                            telefone_fixo = :telefone_fixo,
                                            cnpj_sistema = :cnpj,
                                            cep_sistema = :cep,
                                            rua_sistema = :rua,
                                            numero_sistema = :numero,
                                            bairro_sistema = :bairro,
                                            cidade_sistema = :cidade,
                                            estado_sistema = :estado,
                                            instagram_sistema = :instagram,
                                            tipo_relatorio = :tipo_relatorio,
                                            cards = :cards,
                                            pedidos = :pedidos,
                                            desenvolvedor = :desenvolvedor,
                                            site_dev = :site_dev,
                                            previsao_entrega = :previsao_entrega,
                                            estabelecimento_aberto = :estabelecimento_aberto,
                                            abertura = :abertura,
                                            fechamento = :fechamento,
                                            texto_fechamento = :texto_fechamento,
                                            logotipo = '$logotipo',
                                            icone = '$icone',
                                            logo_rel = '$logo_rel'
                                            WHERE id = '$id'");
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone"); 
$query->bindValue(":telefone_fixo", "$telefone_fixo"); 
$query->bindValue(":cnpj", "$cnpj");
$query->bindValue(":cep", "$cep");
$query->bindValue(":rua", "$rua");
$query->bindValue(":numero", "$numero");
$query->bindValue(":bairro", "$bairro");
$query->bindValue(":cidade", "$cidade");
$query->bindValue(":estado", "$estado");
$query->bindValue(":instagram", "$instagram");
$query->bindValue(":tipo_relatorio", "$tipo_rel");
$query->bindValue(":cards", "$cards");
$query->bindValue(":pedidos", "$pedidos");
$query->bindValue(":desenvolvedor", "$dev");
$query->bindValue(":site_dev", "$site");
$query->bindValue(":previsao_entrega", "$previsao");
$query->bindValue(":estabelecimento_aberto", "$aberto");
$query->bindValue(":abertura", "$abertura");
$query->bindValue(":fechamento", "$fechamento");
$query->bindValue(":texto_fechamento", "$txt_fechamento");
$query->execute();

echo 'Editado com Sucesso';
?>