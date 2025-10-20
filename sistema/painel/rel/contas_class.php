<?php
include('../../conexao.php');

$dataInicial = $_POST['dataInicial'];
$dataFinal = $_POST['dataFinal'];
$pago = urlencode($_POST['pago']);
$tabela = urlencode($_POST['tabela']);
$busca = urlencode($_POST['busca']);

$query = $pdo->query("SELECT * FROM config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$tipo_rel = $res[0]['tipo_relatorio'];
$url_sistema = $res[0]['url_sistema'];
$html = file_get_contents($url_sistema."sistema/painel/rel/contas.php?dataInicial=$dataInicial&dataFinal=$dataFinal&pago=$pago&tabela=$tabela&busca=$busca");

if ($tipo_rel != 'PDF') {
    echo $html;
    exit();
}

date_default_timezone_set('America/Sao_Paulo');

//CARREGAR DOMPDF
require_once __DIR__ . '/../../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

header("Content-Transfer-Encoding: binary");
header("Content-Type: image/png");

//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$pdf = new Dompdf($options);

//Definir o tamanho do papel e orientação da página
$pdf->setPaper('A4', 'portrait');

//Carregar o conteúdo HTML
$pdf->loadHtml($html);

//Renderizar o PDF
$pdf->render();

//Nomear o PDF gerado
$pdf->stream(
    'contas.pdf',
    array("Attachment"=> false)
);

?>