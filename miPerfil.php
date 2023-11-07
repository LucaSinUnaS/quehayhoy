<!DOCTYPE html>
<?php require_once 'connection.php';
include 'functions.php';
	$iniciado = false;
	session_start();
	//Checkear si el usuario ha iniciado sesión
  if(!isset($_SESSION['user'])){
    $iniciado = false;
    header("location: login.php");
  }
  else{
  	$typeArray = array("val","unval");
  	if(!empty($_GET['type'])){
  		if(in_array($_GET['type'], $typeArray)){
  			$typeGet = htmlspecialchars($_GET['type']);
  			if($typeGet == "val"){
  				$type = "u956478100_quehayhoy.validated_events";
  			}
  			else{
  				$type = "u956478100_quehayhoy.unvalidated_events";
  			}
  		}
  		else{
  			header("Location:index.php");
  		}
  	}
  	else{
  		header("Location:miPerfil.php?pag=1&type=val");
  	}

  	$iniciado = true;
  	$username_session = $_SESSION['user']['username'];
  	$descripcion_session = $_SESSION['user']['descripcion'];
  	$totalevents = $db->query("SELECT count(*) FROM $type WHERE username = '$username_session'")->fetchColumn();

  	if($totalevents != 0){
  		$infousers_stmt=$db->prepare("SELECT * FROM $type WHERE username = '$username_session' ORDER BY primera_fecha ASC, primera_hora ASC");

  	$infousers_stmt->execute([]);
    for($k = 0; $k < $totalevents; $k++){
    	
    	$rowEvents[$k][] = $infousers_stmt->fetch(PDO::FETCH_ASSOC);
    	//$users_id[$k] = $rowusers[];
    }
    $p = 0;
    foreach($rowEvents as $events_info){
    	$idusers[$p] = $events_info[0]['id'];
    		$nameusers[$p] = $events_info[0]['username'];
    		$scoreusers[$p] = $events_info[0]['email'];
    		$eventsID[$p] = $events_info[0]['id'];
    		$eventsPortada[$p] = $events_info[0]['portada'];
    		$eventsTitulo[$p] = $events_info[0]['titulo'];
    		$eventsDesc[$p] = $events_info[0]['descripcion'];
    		$eventsCategoria[$p] = $events_info[0]['categoria'];
    		$eventsFechas = $events_info[0]['fecha'];
    		$fechanocoma = str_replace(",", "", $eventsFechas);
     		$fechanospace= explode(' ', $fechanocoma);
     		$eventsTimestamp = str_replace("_", " ", $fechanospace[0]);
     		//echo nl2br ("\n");
     		$timestamp = strtotime($eventsTimestamp);
     		$dayNum = date('d', $timestamp);
			$day_events = GetDay($timestamp);
			$month_events = GetMonth($timestamp);
			$year_events = date('Y', $timestamp);
    		$eventsHoras = $events_info[0]['hora'];
    		$horanocoma_events = str_replace(",", "", $eventsHoras);
     		$horanospace_events= explode(' ', $horanocoma_events);
     		$eventsHora = str_replace("_", " ", $horanospace_events[0]);
     		$eventsFecha[$p] = $day_events . ", " .$dayNum . " de " . $month_events . " | " .$eventsHora. "HS.";
			//echo($cineID[$c]);
    		$eventsLugar[$p] = $events_info[0]['lugar'];
    		$eventsPrecio[$p] = $events_info[0]['precio'];
    		$p++;
    }
  	}

  	


  	if(!empty($_GET['pag'])){
    		$pagActive = $_GET['pag'];
    		for ($i=0; $i <= ($totalevents/10); $i++) { 
    			$PagsRow[$i] = ($i+1);
    		}
    		if(!in_array($pagActive, $PagsRow)){ 
				header("Location: miPerfil.php?pag=1");
				$pagActive = 1;
			}
    	}
    	else{
			header("Location: miPerfil.php?pag=1");
			$pagActive = 1;
    	}

    	$currentUrl = $_SERVER['REQUEST_URI'];

    	$getsArray = array("pag","type");
    	$parsedURL = parse_url($currentUrl, PHP_URL_QUERY);
    	if(!empty($parsedURL)){
    		$parsedURL = preg_replace('/=[\s\S]+?&/', ' ', $parsedURL);
    		$parsedURL = substr_replace($parsedURL, '', strpos($parsedURL, '='));
    		$parsedURL = explode(' ', $parsedURL);
    		for($x=0;$x<count($parsedURL);$x++){
	    		if(!in_array($parsedURL[$x], $getsArray)){
	    			header("Location:index.php");
	    		}
	    	}
    	}

    	if(isset($_REQUEST['buttonGuardado'])){
    		$descripcionAct = htmlspecialchars($_REQUEST['descripcion']);
    		if(strlen($descripcionAct) > 1000){
        	$errorMsg[1][] = "El link debe tener menos de 1000 caracteres.";
      	}
    		$paginaAct = htmlspecialchars($_REQUEST['pagina']);
    		if(strlen($paginaAct) > 300){
        	$errorMsg[12][] = "El link debe tener menos de 300 caracteres.";
      	}
    		$instagramAct = htmlspecialchars($_REQUEST['instagram']);
    		if(strlen($instagramAct) > 100){
        	$errorMsg[13][] = "El link de Instagram debe tener menos de 100 caracteres.";
      	}
    		$facebookAct = htmlspecialchars($_REQUEST['facebook']);
    		if(strlen($facebookAct) > 100){
        	$errorMsg[14][] = "El link de Facebook debe tener menos de 100 caracteres.";
      	}
          if(empty($errorMsg)){
          $fileName = $_FILES['portada']['name'];
          $fileSize = $_FILES['portada']['size'];
          $tmpName = $_FILES['portada']['tmp_name'];

          $validImageExtension = ['jpg', 'jpeg', 'png'];
          $imageExtension = explode('.',$fileName);
          $imageExtension = strtolower(end($imageExtension));
          
          if(empty($fileName)){
						$newImageName = $_SESSION['user']['foto'];
          }
          else if(!in_array($imageExtension, $validImageExtension)){
            $errorMsg[15][] = "Extension de imagen invalida";
          }
          else if($fileSize > 10000000){
             $errorMsg[15][] = "El tamaño de la imagen debe ser menor a 10MB.";
          }
          else{
            $file = $tmpName;
          $image = imagecreatefromstring(file_get_contents($file));
          ob_start();
          imagejpeg($image,NULL,100);
          $cont = ob_get_contents();
          ob_end_clean();
          imagedestroy($image);
          $content = imagecreatefromstring($cont);
          $newImageName = uniqid();
          $newImageName .= '.webp';
          $output = 'includes/uploaded/perfiles/' . $newImageName;
          imagewebp($content,$output);
          imagedestroy($content);

          }

          if(empty($errorMsg)){
          	$insert_stmt = $db->prepare("UPDATE u956478100_quehayhoy.users SET foto=:foto,descripcion=:descripcion,pagina=:pagina,instagram=:instagram,facebook=:facebook WHERE username = :username");
              if($insert_stmt->execute(
              [
                ':foto' => $newImageName,
                ':descripcion' => $descripcionAct,
                ':pagina' => $paginaAct,
                ':instagram' => $instagramAct,
                ':facebook' => $facebookAct,
                ':username' => $username_session
              ])
            )
              {
              	$_SESSION['user']['foto'] = $newImageName;
              	$_SESSION['user']['descripcion'] = $descripcionAct;
              	$_SESSION['user']['pagina'] = $paginaAct;
              	$_SESSION['user']['instagram'] = $instagramAct;
              	$_SESSION['user']['facebook'] = $facebookAct;
             header("location: miPerfil.php");
              }
          }
    	}

 }
}


 ?>
<html lang="es">
<head>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
	<link href="https://api.fontshare.com/v2/css?f[]=cabinet-grotesk@500&display=swap" rel="stylesheet">
	<link href="https://api.fontshare.com/v2/css?f[]=supreme@400&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@40,700,0,200" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="includes/css/style_perfil.css">
	<link rel="stylesheet" type="text/css" href="includes/css/style_footer.css">
	<meta name="description" content="Perfil del usuario que inició sesión donde se muestra su descripción, foto de perfil, links de contacto y los eventos que publicó.">
    <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png" sizes="16x16" />

	<title>Perfil | QueHayHoy?</title>
</head>
<body id="bodyID">
<nav class = "navbar navbar-expand-lg fixed-top navbar_size border-bottom border-dark navbar_scrolled border-3" id = "nav_id">
		<div class = "container-fluid" >
			<div class = "align-items-center">
				<a class="navbar-brand" href="index.php" aria-label="Inicio">
      				<img src="includes/images/logoPag_2.png" alt="Logo de la página" class="d-inline-block align-middle img_logoNav ps-5">
    			</a>
			</div>
			

			<button class="navbar-toggler navbar-dark bg-black" type = "button" id="btn_toclick" data-bs-toggle = "collapse" data-bs-target = "#navbar_menu" aria-label="Botón que abre el resto del menú">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class = "collapse navbar-collapse fontNav" id = "navbar_menu">
				<ul class="navbar-nav mx-auto">
					<li class = "navbar-item underlineNav">
						<a href = "verEventos.php" class="nav-link text-center text-dark mx-3 nav-text-shadow" aria-label="Ver Eventos">Eventos</a>
					</li>
					<li class = "navbar-item underlineNav">
						<a href = "index.php#Noticias" class="nav-link text-center text-dark mx-3 nav-text-shadow" aria-label="Noticias">Noticias</a>
					</li>
				</ul>
				<?php 
					if($iniciado == true){
						echo('
							<ul class="navbar-nav pe-5" id="crearEventoID">
							<li>
						<a href = "crearEvento.php" class="nav-link text-dark text-center border border-dark rounded-pill me-5 btn_color_1 event_id" id = "" aria-label="Crear un evento">Crear un evento</a>
						</li>
					<div class="dropdown mx-auto">
					  <button class="btn btn-link dropdown-toggle nav-link text-center mx-auto text-dark text-nowrap" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Botón que abre el resto del menú">
					    Mi cuenta
					  </button>
					  <ul class="dropdown-menu navbar_scrolled" aria-labelledby="dropdownMenuButton1" id = "dropdown_id">
					    <li><a class="dropdown-item text-center text-dark" href="miPerfil.php" aria-label="Ir al perfil">Perfil</a></li>
					    '); if ($_SESSION['user']['admin'] == 1) {
					    	echo('<li><a class="dropdown-item text-center text-dark" href="validation.php" aria-label="Validar eventos">Validar eventos</a></li>');
					    }
					    echo('
					    <li><a class="dropdown-item text-center text-dark" href="logout.php" aria-label="Cerrar sesión">Salir</a></li>
					  </ul>
					</div>
				</ul>');
					}
					else{
						echo(
							'
							<ul class="navbar-nav pe-5" id="crearEventoID">
							<li>
						<a href = "crearEvento.php" class="nav-link text-dark text-center border border-dark rounded-pill me-5 btn_color_1 event_id" id = "" aria-label="Crear un evento">Crear un evento</a>
						</li>
								<li class="navbar-item">
									<a href = "login.php" class="nav-link text-dark text-center" aria-label="Iniciar sesión">Iniciar sesión</a>
								</li>
							</ul>
							'
						);
					}
				 ?>
				
			</div>
		</div>
	</nav>
	<div class="navbar_size">
		
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-4">
				<form class="row g-3 mt-3 needs-validation" novalidate autocomplete="off" method="post" enctype="multipart/form-data">
				<div class="mt-3 alignCenterContainer" style="border: 2px solid #ced4da;">
				<div class="alignCenter alignCenterContainer" style="max-width: 100%; max-height:100%;">
					<div class="alignCenter mt-2" style="display: inline-block; width: 128px;height: 128px; max-width: 100%; max-height: 100%;" class="">
						<label for="input_file" id="drop_area" class="fullSize">
                <input type="file" name="portada" accept="image/*" class="fullSize" id="input_file" hidden>
                <div id="img_view" class="fullSize imgPerfil" style="background-image: url('includes/uploaded/perfiles/<?php echo($_SESSION['user']['foto']); ?>');">
                  <div id="textImgRemove" class="fullSize">
                  <!--<img src="includes/uploaded/portadas/<?php echo($_SESSION['user']['foto']); ?>" class="img-fluid p-2 fullSize imgPerfil" style="object-position: center; object-fit: cover; border-radius: 4rem;">--> 
                   <!--<img src="includes/images/demo.jpg" class="img-fluid p-2 fullSize imgPerfil" style="object-position: center; object-fit: cover; border-radius: 4rem;"> -->
                    <?php 
                  if(isset($errorMsg[15])){
                    foreach($errorMsg[15] as $emailErrors){
                      echo "<p class = 'small text-danger'>".$emailErrors."</p>";
                    }
                  }
                 ?>
                  </div>
                    
                </div>
              </label> 
					</div>
					<div class="alignCenter" style="display: inline-block; max-height:100%;max-width: 100%;">
						<h4 class="mt-2"><?php echo($username_session); ?></h4>
					</div>
				</div>
				
				<div class="text-center">
					<div class="input-group my-2">
          <textarea class="form-control" aria-label="Descripción de tú evento..." name ="descripcion" maxlength="1000"><?php echo($_SESSION['user']['descripcion']);?></textarea>
        </div>
        <?php 
            if(isset($errorMsg[1])){
              foreach($errorMsg[1] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
          ?>
				</div>
				
			</div>

			<div class="mt-3">
          <h2>Links de contacto</h2>

            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">Página web</span>
                <input type="text" class="form-control" placeholder="Página" aria-label="Pagina" aria-describedby="basic-addon1" name="pagina" value = "<?php echo($_SESSION['user']['pagina']); ?>">
            </div>
            <?php 
                if(isset($errorMsg[12])){
                  foreach($errorMsg[12] as $emailErrors){
                     echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
                  }
                }
              ?>
            <div class="input-group mt-3">
                <span class="input-group-text" id="basic-addon1">Instagram</span>
                <input type="text" class="form-control" placeholder="Instagram" aria-label="Instagram" aria-describedby="basic-addon1" name="instagram" value = "<?php echo($_SESSION['user']['instagram']); ?>">

                <span class="input-group-text" id="basic-addon1">Facebook</span>
                <input type="text" class="form-control" placeholder="Facebook" aria-label="Facebook" aria-describedby="basic-addon1" name="facebook" value = "<?php echo($_SESSION['user']['facebook']); ?>">

            </div>
                <?php 
                if(isset($errorMsg[13])){
                  foreach($errorMsg[13] as $emailErrors){
                     echo "<br><p class = 'small text-danger text-center'>".$emailErrors."</p>";
                  }
                }
              ?>        
                <?php 
                if(isset($errorMsg[14])){
                  foreach($errorMsg[14] as $emailErrors){
                     echo "<br><p class = 'small text-danger text-center'>".$emailErrors."</p>";
                  }
                }
              ?>
			</div>
			<div class="col-12 mb-3" style="display: flex; align-items:center; justify-content:center;">
              <button class="btn btn-primary" type="submit" name="buttonGuardado">GUARDAR</button>
           </div>
         </form>
			</div>
			<div class="col-12 col-md-8">
				<!-- Empieza publicacion -->
				<h1 class="text-center mt-2">Eventos publicados:</h1>
				<div class="text-center">
					<div class="d-inline-block mx-3">
					<h4 class="linksFiltros"><a class="<?php 
					if($typeGet == "val"){
							echo("linksSelected");
						}
						else{
							echo("linksUnselected");
						}
					 ?>" href="miPerfil.php?pag=1&type=val">Eventos validados</a></h4>
				</div>
				<div class="d-inline-block mx-3">
					<h4 class="linksFiltros"><a class="<?php 
					if($typeGet == "val"){
							echo("linksUnselected");
						}
						else{
							echo("linksSelected");
						}
					 ?>" href="miPerfil.php?pag=1&type=unval">Eventos a la espera de ser validados</a></h4>				
				</div>
				</div>
				
			<?php 
				if(($totalevents/$pagActive) > 10){
					for($i = ($pagActive-1)*10; $i < 10*$pagActive; $i++){
					echo('
						<div class = "container-fluid my-3">
						<div class = "row no-gutters bgPost border-4 border-dark">
							<div class="col-12 bg_'.str_replace(' ', '_', $eventsCategoria[$i]));echo(' border-4 border-dark border-bottom" style="border-top-left-radius: 0.6rem; border-top-right-radius: 0.6rem;">
								<div class="">
									<h1 class="text-center">');echo($eventsCategoria[$i]);echo('</h1>
								</div>
								
							</div>
							<div class="container">
								<div class="row inCenter" id="rowEvent">
									<div class="col-md-2 col-12 p-0 m-0 position-relative" >
										<img src="includes/uploaded/portadas/'); echo($eventsPortada[$i]); echo('" class="heightImgEvent" style=" width: 100%; height:10px;object-position: center; object-fit: cover; border-bottom-left-radius: 0.6rem;" alt="Portada del evento '.$eventsTitulo[$i]);echo('">
										');
										if($type=="u956478100_quehayhoy.validated_events"){
											echo('<a href="evento.php?id=');echo($eventsID[$i]); echo('" class="stretched-link"></a>');
										}
										echo('	
									</div>
									<div class="col-12 col-md-10 position-relative heightInfoEvent">
										<h2 class="text-dark align-self-top">'); echo($eventsTitulo[$i]);echo('</h2>
										<div style="overflow: hidden;">
											<h5 class="truncateText text_subtitle">'); echo($eventsDesc[$i]); echo('</h5>
										</div>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-calendar-event"></i> '); echo($eventsFecha[$i]);echo('</h5>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-geo-alt-fill"></i> '); echo($eventsLugar[$i]); echo('</h6>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-coin"></i> $');echo($eventsPrecio[$i]);echo('</h6>
										');
										if($type=="u956478100_quehayhoy.validated_events"){
											echo('<a href="evento.php?id=');echo($eventsID[$i]); echo('" class="stretched-link"></a>');
										}
										echo('					
									</div>	
								</div>

							</div>

							
						</div>
						
					</div>
						');	
					}	
				}
				else if($totalevents == 0){
					echo('

						<h2 class = "inCenter mt-5">No hay eventos con estos filtros.</h2>
						'
					);
				}
				else{
					for($i = ($pagActive-1)*10; $i < $totalevents; $i++){
					echo('
						<div class = "container-fluid my-3">
						<div class = "row no-gutters bgPost border-4 border-dark ">
							<div class="col-12 bg_'.str_replace(' ', '_', $eventsCategoria[$i]));echo(' border-4 border-dark border-bottom" style="border-top-left-radius: 0.6rem; border-top-right-radius: 0.6rem;">
								<h1 class="text-center">');echo($eventsCategoria[$i]);echo('</h1>
							</div>
							<div class="container">
								<div class="row inCenter" id="rowEvent">
									<div class="col-md-2 col-12 p-0 m-0 position-relative" >
										<img src="includes/uploaded/portadas/'); echo($eventsPortada[$i]); echo('" class="heightImgEvent" style=" width: 100%; height:10px;object-position: center; object-fit: cover; border-bottom-left-radius: 0.6rem;" alt="Portada del evento '.$eventsTitulo[$i]);echo('">
										');
										if($type=="u956478100_quehayhoy.validated_events"){
											echo('<a href="evento.php?id=');echo($eventsID[$i]); echo('" class="stretched-link"></a>');
										}
										echo('	
									</div>
									<div class="col-12 col-md-10 position-relative heightInfoEvent">
										<h2 class="text-dark align-self-top">'); echo($eventsTitulo[$i]);echo('</h2>
										<div style="overflow: hidden;">
											<h5 class="truncateText text_subtitle">'); echo($eventsDesc[$i]); echo('</h5>
										</div>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-calendar-event"></i> '); echo($eventsFecha[$i]);echo('</h5>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-geo-alt-fill"></i> '); echo($eventsLugar[$i]); echo('</h6>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-coin"></i> $');echo($eventsPrecio[$i]);echo('</h6>
										');
										if($type=="u956478100_quehayhoy.validated_events"){
											echo('<a href="evento.php?id=');echo($eventsID[$i]); echo('" class="stretched-link"></a>');
										}
										echo('				
									</div>	
								</div>

							</div>

							
						</div>
						
					</div>
						');	
					}	
				}

					
			
			
			 ?>
		
			<!-- Termina publicacion -->

			 <div>
				<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center pagination-lg">
				    <li class="page-item <?php 
				    if(!in_array(($pagActive-1), $PagsRow) || !isset($eventsCategoria[(($pagActive-1)-1)*10])){
						echo("disabled");
				      }
				     ?>">
				      <a class="page-link" href="<?php 
				      $newUrlPagPrev = str_replace("pag=".$pagActive,"pag=".($pagActive-1),$currentUrl);
				      echo($newUrlPagPrev);
				      	 ?>" aria-label="Previous">
				        <span aria-hidden="true">&laquo;</span>
				      </a>
				    </li>
				    <?php 
				    for($i=0;$i<$totalevents/10;$i++){
				    	?>
				    	<li class="page-item <?php 
				    	if(!isset($eventsCategoria[(($i+1)-1)*10])){
				    		echo("disabled");
				    	}
				     ?>" id="<?php echo("pag".($i+1)); ?>"><a type="submit" href="<?php 
				     	$newUrlPag = str_replace("pag=".$pagActive,"pag=".($i+1),$currentUrl);
				     	echo($newUrlPag);
				     ?>" class="page-link"><?php echo($i+1); ?></a></li>
				     <?php
				    }?>

				    <li class="page-item <?php 
				    if(!in_array(($pagActive+1), $PagsRow) || !isset($eventsCategoria[(($pagActive+1)-1)*10])){
						echo("disabled");
				      }
				     ?>">
				      <a class="page-link" href="
				      <?php 
				      $newUrlPagNext = str_replace("pag=".$pagActive,"pag=".($pagActive+1),$currentUrl);
				      echo($newUrlPagNext);
				      	 ?>" aria-label="Next">
				        <span aria-hidden="true">&raquo;</span>
				      </a>
				    </li>
				  </ul>
				</nav>
			
		</div>
			</div>
		</div>
	</div>

		

		<!--::::Pie de Pagina::::::-->
    <footer class="pie-pagina footer_text">
        <div class="grupo-1">
            <div class="box">
                <figure>
                    <a href="#" aria-label="Ir a inicio">
                        <img src="includes/images/logo_fondo.jpeg" alt="Logo de QueHayHoy?">
                    </a>
                </figure>
            </div>
            <div class="box">
                <h2>SOBRE NOSOTROS</h2>
                <p>¡Hola! Somos Luca y Sibi, estudiantes de secundaria que se propusieron realizar esta página para las olimpiadas de programación del Plan Ceibal.</p>
                <p>Si querés saber más de nosotros, ¡visita nuestras redes!</p>
            </div>
            <div class="box">
                <h2>SIGUENOS</h2>
                    <a href="https://www.instagram.com/ztech_uy/" aria-label="Ir al Instagram de ZTech" class="bi bi-instagram linkIG"></a>

                 <h2 class="mt-5">CONTACTO</h2>
                <div class="red-social">
                    <p class="bi bi-envelope-at"> uyztech@gmail.com</p>
                </div>
            </div>
        </div>
        <div class="grupo-2">
            <small>&copy; 2023 <b>ZTech</b> - Todos los Derechos Reservados.</small>
        </div>
    </footer>
	


<script type="text/javascript" src="includes/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
var submitButton = document.getElementById('btn_toclick');
	let buttonClicked = false;

	submitButton.addEventListener('click', function handleClick() {
		if (buttonClicked == false) {
  			//console.log('Submit button is clicked');
  			buttonClicked = true;
  			document.getElementById('nav_id').style.height="100%";
  			document.querySelectorAll('.event_id').forEach(el=>el.style.transition="0");
  			document.querySelectorAll('.event_id').forEach(el=>el.classList.remove('me-5'));
  			document.getElementById("crearEventoID").classList.remove('pe-5');
  			document.getElementById("bodyID").style.height="100%";
  			document.getElementById("bodyID").style.overflowY="hidden";
 		}
 		else{
			//console.log('Already clicked');
			buttonClicked = false;
			document.getElementById('nav_id').style.height="15vh";
			document.querySelectorAll('.event_id').forEach(el=>el.classList.add('me-5'));
			document.querySelectorAll('.event_id').forEach(el=>el.style.transition="0.3s");
			document.getElementById("bodyID").style.height="auto";
  			document.getElementById("bodyID").style.overflowY="visible";
 		}
 	
});

	//Imagen e informacion de los eventos de la misma altura

	var items_showEvents = document.querySelectorAll('.heightImgEvent'); //Selecciono todos los divs con la clase heightImgEvent
	items_showEvents.forEach((e)=>{ //Voy por cada uno
		var f = e.parentElement.nextElementSibling; //selecciono el div que contiene la informacion (el hermano del padre de la iamgen)
			var styleHeight = getComputedStyle(f).height; //Guardo la altura de ese div
			//console.log('style Height : ', styleHeight);
			e.style.height = styleHeight; //Igualo la altura del texto con el de la imagen
	})


	//Paginacion
 window.onbeforeunload = null;

	<?php 

	for($i=0;$i<$totalevents/10;$i++){
		if($i+1 == $pagActive){
			echo("document.getElementById('pag".($i+1)."').classList.add('active');");
		}
		else{
			echo("document.getElementById('pag".($i+1)."').classList.remove('active');");
		}
	}
	 ?>


	 //Descripcion text area auto resize

	 const tx = document.getElementsByTagName("textarea");
for (let i = 0; i < tx.length; i++) {
  tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;overflow-y:hidden;");
  tx[i].addEventListener("input", OnInput, false);
}

function OnInput() {
  this.style.height = 0;
  this.style.height = (this.scrollHeight) + "px";
}



const dropArea = document.getElementById("drop_area");
const inputFile = document.getElementById("input_file");
const imgView = document.getElementById("img_view"); 
const imgText = document.getElementById("textImgRemove");

inputFile.addEventListener("change", uploadImage);
function uploadImage(){
  inputFile.files[0];
  let imgLink = URL.createObjectURL(inputFile.files[0]);
  imgView.style.backgroundImage = `url(${imgLink})`;
  imgText.style.visibility = "hidden";
}

dropArea.addEventListener("dragover", function(e){
  e.preventDefault();
});
dropArea.addEventListener("drop", function(e){
  e.preventDefault();
  inputFile.files = e.dataTransfer.files;
  uploadImage();
});

</script>
</body>
</html>