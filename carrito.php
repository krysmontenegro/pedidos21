<?php

include 'global/conexion.php';
//variable de sesion mantendrá información del usuario durante la navegación
session_start();
$mensaje = "";
if (isset($_POST['btnAccion'])) {
    switch ($_POST['btnAccion']) {
        case 'Agregar':
             if (is_numeric(openssl_decrypt($_POST['id'], COD, KEY))) {
                $ID = openssl_decrypt($_POST['id'], COD, KEY);
                //$mensaje.= "ID... " . $ID . "<br/>";
            } else {
               // $mensaje.= "Upss... ID Incorecto" . $ID . " <br/>";
                break;
            }
            if (is_numeric(openssl_decrypt($_POST['cantidad'], COD, KEY))) {
                $TOTAL = openssl_decrypt($_POST['cantidad'], COD, KEY);
                //$mensaje.= "ID... " . $ID . "<br/>";
            } else {
               // $mensaje.= "Upss... ID Incorecto" . $ID . " <br/>";
                break;
            }
            
            if (is_string(openssl_decrypt($_POST['nombre'], COD, KEY))) {
                $NOMBRE = openssl_decrypt($_POST['nombre'], COD, KEY);
               //$mensaje.= "Nombre... " . $NOMBRE . "<br/>";
            } else {
                $mensaje.= "Upss... nombre Incorecto" . $NOMBRE . " <br/>";
            }
            //debe tomar lo que viene del combo
            if (is_numeric(($_POST['cbxCant']))and $_POST['cbxCant']!=0) {
                $CANTIDAD = ($_POST['cbxCant']);
               // $mensaje.= "Cantidad... " . $CANTIDAD . "<br/>";
            } else {
                $mensaje.= "Upss... cantidad incorecta  de ". $NOMBRE."<br/>";
                break;
            }
            if (is_numeric(openssl_decrypt($_POST['precio'], COD, KEY))) {
                $PRECIO = openssl_decrypt($_POST['precio'], COD, KEY);
               // $mensaje.= "Precio... " . $PRECIO . "<br/>";
            } else {
                $mensaje.= "Upss... precio incorecto " . $PRECIO . " <br/>";
                break;
            }
            


            if(!isset($_SESSION['CARRITO'])){
                //Para el primer producto
                $producto=array(
                  'ID'=>$ID,
                  'NOMBRE'=>$NOMBRE,
                  'PRECIO'=>$PRECIO,
                  'CANTIDAD'=>$CANTIDAD,
                  'TOTAL'=>$TOTAL - $CANTIDAD
                    
                    
                    
                );
                $_SESSION['CARRITO'][0]=$producto;
                $mensaje="Producto agregado al pedido ...";
                
            }else{
                $idProductos= array_column($_SESSION['CARRITO'], 'ID');
                if(in_array($ID, $idProductos)){
                    echo "<script>alert ('El producto ya fue seleccionado, debe eliminarlo del pedido si desea un cambio.')</script>";
                }else{
                    
                
                
                
                //poner mas productos en el carrito de compras
                $NumeroProductos=count($_SESSION['CARRITO']);
                $producto=array(
                  'ID'=>$ID,
                  'NOMBRE'=>$NOMBRE,
                  'PRECIO'=>$PRECIO,
                  'CANTIDAD'=>$CANTIDAD,
                  'TOTAL'=>$TOTAL - $CANTIDAD
                    
                    
                    
                );
                $_SESSION['CARRITO'][$NumeroProductos]=$producto;
                $mensaje="Producto agregado al pedido ...";
                }
            }
            
            //$mensaje=print_r($_SESSION, true);
            



            break;
            
            case "Quitar":
                
           
            if (is_numeric(openssl_decrypt($_POST['id'], COD, KEY))) {
                
                $ID = openssl_decrypt($_POST['id'], COD, KEY);
                foreach ($_SESSION['CARRITO']as $indice=>$producto){
                    
                    if($producto['ID']==$ID){
                        //elimnar el registro de la variablde se sesion
                        unset($_SESSION['CARRITO'][$indice]);
                        echo "<script>alert('Se elimado el producto del pedido');</script>";
                        
                    }
                }
                
                
                
                
                
            } else {
                echo "<script>alert('No se ha podido eliminar el producto del pedido. Consulte en mostrador');</script>";
                break;
            }
                
            break;
            
            
            case "Actualizar":
            //Se actualiza el valor en la base de datos del producto seleccionado     
           
            if (is_numeric(openssl_decrypt($_POST['id'], COD, KEY))) {
                // echo "<script>alert('Se debe tomar la nueva cantidad y el id del producto para actualizar');</script>";  
                $cant = ($_POST['nCantidad']);
                $sql = "UPDATE tblproductos SET cantidad=".$cant." WHERE id=".openssl_decrypt($_POST['id'], COD, KEY);
                //echo "<script>alert('.$sql');</script>";  
                $sentencia=$pdo->prepare($sql);
                $sentencia->execute();
                
                
                
                
                
                
            } else {
                echo "<script>alert('No se ha podido actualizar la nueva cantidad del producto.');</script>";
                break;
            }
                
            break;
            
            
            
            
            
            
            
        case "proceder":
            //elementos del arreglo para agregar a alguna tabla 
            $elementosFactura = $_SESSION['CARRITO'];
            foreach ($_SESSION['CARRITO']as $indice=>$producto){
                //recoleta la información
                $nuevaCant=$producto['TOTAL'];
                $sql = "UPDATE tblproductos SET cantidad=".$nuevaCant." WHERE id=".$producto['ID'];
                $sentencia=$pdo->prepare($sql);
                $sentencia->execute();
                unset($_SESSION['CARRITO'][$indice]);
            }
            echo "<script>location.reload();</script>";
            ob_end_clean();
            ob_start();
            require('fpdf/fpdf.php');
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',7);
            //$pdf->Cell(40,10,utf8_decode('¡Hola, Mundo!'));
            $pdf->Cell(100,10, 'Comanda de Pedido',0,0, 'C');
            $pdf->Ln(10);
            $pdf->Cell(20,5,'Producto' ,1,0,'C',0 );
            $pdf->Cell(10,5,'Cant.' ,1,0,'C',0 );
            $pdf->Cell(10,5,'Precio' ,1,1,'C',0 );
            $totalPrecio=0;
            foreach ($elementosFactura as $i=>$prod){
                $pdf->Cell(20,5, utf8_decode($prod['NOMBRE']),1,0,'C',0 );
                $pdf->Cell(10,5, $prod['CANTIDAD'],1,0,'C',0 );
                $pdf->Cell(10,5, '$'.$prod['PRECIO'],1,1,'C',0 );
                $totalPrecio= $totalPrecio + ($prod['CANTIDAD']*$prod['PRECIO']);
                
                
            }
            $pdf->Cell(30,5, 'Total',1,0,'C',0 );
            $pdf->Cell(10,5, '$'. $totalPrecio,1,1,'C',0 );
            
            
            
            
            
            
            $pdf->Output('D','comanda.pdf');
            ob_end_flush();
            break;
            
            
            
        break;
            
            
    }
}

