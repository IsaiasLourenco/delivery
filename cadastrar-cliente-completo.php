<?php
require_once './sistema/conexao.php';
session_start();

$tabela = 'cliente';

$nome    = trim($_POST['nome'] ?? '');
$email   = trim($_POST['email'] ?? '');
$cpf     = trim($_POST['cpf'] ?? '');
$cep     = trim($_POST['cep'] ?? '');
$rua     = trim($_POST['rua'] ?? '');
$numero  = trim($_POST['numero'] ?? '');
$bairro  = trim($_POST['bairro'] ?? '');
$cidade  = trim($_POST['cidade'] ?? '');
$estado  = trim($_POST['estado'] ?? '');

$telefone = $_SESSION['telefone_temp'] ?? '';

if ($nome === '' || $email === '' || $cpf === '' || $cep === '' || $rua === '' || $numero === '' || $bairro === '' || $cidade === '' || $estado === '' || $telefone === '') {
    echo json_encode(['success' => false, 'mensagem' => 'Campos obrigatórios não preenchidos']);
    exit;
}

// Verifica se já existe o bairro na tabela bairros
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

// Validar email
$query = $pdo->query("SELECT * FROM $tabela WHERE email = '$email'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0) {
    echo json_encode(['success' => false, 'mensagem' => 'Email já cadastrado']);
    exit;
}

// Validar CPF
$query = $pdo->query("SELECT * FROM $tabela WHERE cpf = '$cpf'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0) {
    echo json_encode(['success' => false, 'mensagem' => 'CPF já cadastrado']);
    exit;
}

// Inserir cliente
$query = $pdo->prepare("INSERT INTO $tabela SET 
    nome = :nome,
    email = :email,
    cpf = :cpf,
    telefone = :telefone,
    cep = :cep,
    rua = :rua,
    numero = :numero,
    bairro_id = '$id_bairro',
    cidade = :cidade,
    estado = :estado,
    data_cad = CURDATE()
");

$query->bindValue(":nome", $nome);
$query->bindValue(":email", $email);
$query->bindValue(":cpf", $cpf);
$query->bindValue(":telefone", $telefone);
$query->bindValue(":cep", $cep);
$query->bindValue(":rua", $rua);
$query->bindValue(":numero", $numero);
$query->bindValue(":cidade", $cidade);
$query->bindValue(":estado", $estado);
$query->execute();

$clienteId = $pdo->lastInsertId();

// Atualiza sessão
$_SESSION['cliente_id']   = $clienteId;
$_SESSION['cliente_nome'] = $nome;
$_SESSION['bairro_id']    = $id_bairro;

echo json_encode(['success' => true]);