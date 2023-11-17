<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';
?>


 
            <br>
            <?php if($mensaje!=""){?>
            <div class="alert alert-success">
                 
                <?php echo $mensaje;?>
                
                <a href="mostrarPedido.php" class="badge badge-success">Ver Pedido</a>
            </div>
            
            <?php } ?>
            
            
            

            <div class="row">
                
                <?php
                    $sentencia=$pdo->prepare("SELECT * FROM tblproductos");
                    $sentencia->execute();
                    
                    $listaProductos=$sentencia->fetchAll(PDO::FETCH_ASSOC);
                    //print_r($listaProductos);
                    
                ?>
                <?php foreach($listaProductos as $producto) { ?>
                    <div class="col-3">
                    
                    <div class="card">
                        
                      
                        <img
                        title="<?php echo $producto['Nombre'];?>"
                        alt="<?php echo $producto['Nombre'];?>"

                        class="card-img-top"
                        
                        src="<?php echo $producto['Imagen'];?>"
                        
                        data-toggle="popover"
                        data-trigger="hover"
                        data-content="<?php echo $producto['Descripcion'];?>"
                        height="173px"


                    >
                    <div class="card-body">
                        <span><?php echo $producto['Nombre'];?></span>
                        <h5 class="card-title">$<?php echo $producto['Precio'];?></h5>
                        <!--<p class="card-text"><?php// echo $producto['Descripcion'];?></p>-->
                        
                        
                        <form action="" method="POST">
                            
                            <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['ID'], COD, KEY)  ;?>">    
                            <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt($producto['Nombre'], COD, KEY);?>">
                            <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt($producto['Precio'], COD, KEY);?>">
                            <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt($producto['cantidad'], COD, KEY);?>">
                              <?php $cant= $producto['cantidad'];
                                    $i=0;
                              ?>
                            <div>Cantidad : 
                                <select name="cbxCant" id="cbxCant">
                                    
                                                                      
                                    <?php while($i<=$cant){   ?>
                                        
                                         <option value="<?php echo $i?>">
                                            <?php echo $i;?>
                                        </option>
                                    
                                    <?php $i++;} ?>
                                    
				
                                </select>
                            </div>
                           <button class="btn btn-primary"
                           name="btnAccion"     
                           value="Agregar"
                           type="submit"
                           >
                            
                        Agregar al pedido     
                            
                            
                        </button>
                        </form>
                        
                        
                        
                        
                        
                    </div>
                    </div>

                    






                </div>
                
                <?php } ?>
                    
                    
                
                


            </div>


        


        <script>
            $(function(){
                $('[data-toggle="popover"]').popover()
            }
                    );
        
        </script>
        
<?php 
    include 'templates/pie.php';
?>