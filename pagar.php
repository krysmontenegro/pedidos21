<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';

?>
<?php 
//cada elemento se descuenta de la base de datos 
//y quitar del carrito 
$elementosFactura = $_SESSION['CARRITO'];
foreach ($_SESSION['CARRITO']as $indice=>$producto){
    //recoleta la información
    $nuevaCant=$producto['TOTAL'];
    $sql = "UPDATE tblproductos SET cantidad=".$nuevaCant." WHERE id=".$producto['ID'];
    $sentencia=$pdo->prepare($sql);
    $sentencia->execute();
    unset($_SESSION['CARRITO'][$indice]);
}
?>
<?php
ob_end_clean();
ob_start();
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,utf8_decode('¡Hola, Mundo!'));
$pdf->Output('D','comanda.pdf');
ob_end_flush();

echo "<br>";?>

<?php
          include 'templates/pie.php';
?>






