<?php
include('../../conexao.php');
$html = file_get_contents($url_sistema."sistema/painel/rel/produtos.php");

$query = $pdo->query("SELECT * FROM config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$tipo_rel = $res[0]['tipo_relatorio'];

if ($tipo_rel != 'PDF') {
    echo $html;
    exit();
}

date_default_timezone_set('America/Sao_Paulo');

//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
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
    'produtos.pdf',
    array("Attachment"=> false)
);

?>