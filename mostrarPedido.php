<?php
include 'global/config.php';
include 'carrito.php';
include 'templates/cabecera.php';
?>
<?php
 //ob_start();//llena el buffer para completar el pdf
?>
<br>
<h3>Lista del Pedido</h3>
<?php
if (!empty($_SESSION['CARRITO'])) {
    ?>
    <table class="table table-striped">
        <tbody>
            <tr>
                <?php //Cabecera ?>
                <th width="40%">Descripción</th>
                <th width="15%" class="text-center">Cantidad</th>
                <th width="20%" class="text-center">Precio</th>
                <th width="20%" class="text-center">Total</th>
                <th width="5%">--</th>
            </tr>
            <?php $total = 0;
?>
            <?php foreach ($_SESSION['CARRITO']as $indice => $producto) { ?>
            <tr>
                    <?php //Datos  ?>
                    <td width="40%"><?php echo $producto['NOMBRE'] ?></td>
                    <td width="15%" class="text-center"><?php echo $producto['CANTIDAD'] ?></td>
                    <td width="20%" class="text-center">$<?php echo $producto['PRECIO'] ?></td>
                    <td width="20%" class="text-center">$<?php echo number_format($producto['PRECIO'] * $producto['CANTIDAD'], 2); ?></td>
                    <td width="5%">
                       <form action="" method="post">
                            <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['ID'], COD, KEY)  ;?>"> 
                            <button 
                                class="btn btn-danger"
                                type="submit"
                                name="btnAccion"
                                value="Quitar"
                                >Quitar</button>
                        </form>
                    </td>
                </tr>
                <?php $total = $total + ($producto['PRECIO'] * $producto['CANTIDAD']); ?>
            <?php } ?>
            <tr>
                <td colspan="3" align="right"><h3>Total</h3></td>
                <td align="right"><h3>$<?php echo number_format($total, 2) ?></h3></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5">
                    <form action="" method="post">
                        <button class="btn btn-primary btn-lg btn-block" type="submit"
                                name="btnAccion"
                                value="proceder"
                      >
                            Realizar Pedido
                        </button>
                    </form>
                </td>
            </tr>
        </tbody>
</table>
    <?php
} else {
    ?>
    <div class="alert alert-success">
        No hay productos pedidos...
    </div>
<?php }

?>
<?php
/**
$html= ob_get_clean();//
//echo $html;
require_once 'libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf= new Dompdf();
$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled'=>true));
$dompdf->setOptions($options);
$dompdf->loadHtml("Ticket Prueba");
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream("comanda.pdf", array("Attachment"=>false));*/
?>
<?php
    //Probar con otra libreria 

?>
<?php
include 'templates/pie.php';
