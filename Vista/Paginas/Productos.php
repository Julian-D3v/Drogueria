
    <?php
 
 session_start();
 error_reporting(E_PARSE);
 
	include '../../Modelo/Conexion/configServer.php';
	include '../../Modelo/Conexion/consulSQL.php';
  ?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include '../Enlaces/link-Productos.php'; ?>
</head>

<body id="container-page-product">
    <header class="main-header">
        <div class="container">

            <div class="main-header-container">
                <h1 id="titulo" class="main-header-title">
                    Tu Drogueria
                </h1>
                <img id="icono" src="https://image.flaticon.com/icons/png/512/185/185932.png" alt="Drogueria el reloj">
              
                        
            </div>

        </div>
        <?php include '../Enlaces/navbar.php'; ?>
    </header>

  <!--TERMINA la barra de navegacion
  
    -->
  <!--Empieza la parte superior-->
  <!-- Pills navs -->
  <br>
  <br>
  





  <!-- Pills content -->
  <!--Termina la parte superior de la pagina-->
  <!--Empieza el cuerpo-->
 
<div >
	<div class="row">
		<div class="col-xs-20">
            <br><br>
            <div class="panel panel-info">
              <div class="panel-heading text-center"><h4>Productos en tienda</h4></div>
                <div>
                  <table class="table table-striped table-hover">
                      <thead >
                          <tr>
                          	  <th class="text-center">#</th>
                              <th class="text-center">Código</th>
                              <th class="text-center">Nombre</th>
                              <th class="text-center">Categoría</th>
                              <th class="text-center">Precio</th>
                              <th class="text-center">Modelo</th>
                              <th class="text-center">Marca</th>
                              <th class="text-center">Stock</th>
                              <th class="text-center">Proveedor</th>
                              <th class="text-center">Estado</th>
                              <th class="text-center">fotografia</th>
                              <th class="text-center"></th>
                              
                          </tr>
                      </thead>
                      <tbody>
                        <?php
                        	$mysqli = mysqli_connect(SERVER, USER, PASS, BD);
							mysqli_set_charset($mysqli, "utf8");

							$pagina = isset($_GET['pag']) ? (int)$_GET['pag'] : 1;
							$regpagina = 30;
							$inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

							$productos=mysqli_query($mysqli,"SELECT SQL_CALC_FOUND_ROWS * FROM producto LIMIT $inicio, $regpagina");

							$totalregistros = mysqli_query($mysqli,"SELECT FOUND_ROWS()");
							$totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

							$numeropaginas = ceil($totalregistros["FOUND_ROWS()"]/$regpagina);

							$cr=$inicio+1;
                            while($prod=mysqli_fetch_array($productos, MYSQLI_ASSOC)){
                        ?>
                        <tr>
                        	<td class="text-center"><?php echo $cr; ?></td>
                        	<td class="text-center"><?php echo $prod['CodigoProd']; ?></td>
                        	<td class="text-center"><?php echo $prod['NombreProd']; ?></td>
                        	<td class="text-center">
                        		<?php 
                        			$categ=ejecutarSQL::consultar("SELECT Nombre FROM categoria WHERE CodigoCat='".$prod['CodigoCat']."'");
                        			$datc=mysqli_fetch_array($categ, MYSQLI_ASSOC);
                        			echo $datc['Nombre'];
                        		?>
                        	</td>
                          
                          <td class="text-center"><?php echo (($prod['Precio']-($prod['Precio']*($prod['Descuento']/100)))) ?></td>
                        	<td class="text-center"><?php echo $prod['Modelo']; ?></td>
                        	<td class="text-center"><?php echo $prod['Marca']; ?></td>
                        	<td class="text-center"><?php echo $prod['Stock']; ?></td>
                        	<td class="text-center">
                        		<?php
                        			$prov=ejecutarSQL::consultar("SELECT NombreProveedor FROM proveedor WHERE NITProveedor='".$prod['NITProveedor']."'");
                        			$datp=mysqli_fetch_array($prov, MYSQLI_ASSOC);
                        			echo $datp['NombreProveedor'];
                        		?>
                        	</td>
                        	<td class="text-center">
                        		<?php echo $prod['Estado']; ?>
                        	</td>
                        
                          <td class="text-center" class="img-product"><img height="100px" src="data:image/png;base64,<?php echo base64_encode($prod['Imagen']); ?>"/></td>
                          <td class="text-center">
                        	
                          
                          <?php
                          if($_SESSION['nombreAdmin']!="" || $_SESSION['nombreUser']!=""){
                                        echo '
                                        <form action="../../Controlador/InicioSesión/Carrito.php" method="POST" class="FormCatElec" data-form="">
                                           <input type="hidden" value="'.$prod['CodigoProd'].'" name="codigo">
                                           <button class="btn btn-lg btn-raised btn-success btn-block"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp; Añadir al carrito</button>
                                        </form>
                                        <div class="ResForm"></div>';
                                    }else{
                                        echo '<p class="text-center"><small>Para agregar productos al carrito de compras debes iniciar sesion</small></p><br>';
                                        echo '<button class="btn btn-lg btn-raised btn-info btn-block" data-toggle="modal" data-target=".modal-login"><i class="fa fa-user"></i>&nbsp;&nbsp; Iniciar sesion</button>';
                                    }
                                    if($prod['Imagen']!="" && is_file("./assets/img-products/".$prod['Imagen'])){ 
                                     
                                  }
                                
                      }
                                
                                ?>
                        	</td>
                        </tr>
                        
                      </tbody>
                  </table>
                </div>
                <?php if($numeropaginas>=1): ?>
              	<div class="text-center">
                  <ul class="pagination">
                    <?php if($pagina == 1): ?>
                        <li class="disabled">
                            <a>
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="configAdmin.php?view=productlist&pag=<?php echo $pagina-1; ?>">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>


                    <?php
                        for($i=1; $i <= $numeropaginas; $i++ ){
                            if($pagina == $i){
                                echo '<li class="active"><a href="configAdmin.php?view=productlist&pag='.$i.'">'.$i.'</a></li>';
                            }else{
                                echo '<li><a href="configAdmin.php?view=productlist&pag='.$i.'">'.$i.'</a></li>';
                            }
                        }
                    ?>
                    

                    <?php if($pagina == $numeropaginas): ?>
                        <li class="disabled">
                            <a>
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="configAdmin.php?view=productlist&pag=<?php echo $pagina+1; ?>">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                  </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
	</div>
</div>
  <?php include '../Vista/Enlaces/footer.php'; ?>

  <!--Termina el pie de pagina-->
</body>

</html>