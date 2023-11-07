<!DOCTYPE html>
<?php require_once 'connection.php';
include 'functions.php';
	$iniciado = false;
	session_start();
	//Checkear si el usuario ha iniciado sesión
  if(!isset($_SESSION['user'])){
    $iniciado = false;
    //header("location: login.php");
  }
  else{
  	$iniciado = true;
  }

  $select_id = $db->prepare("SELECT id from u956478100_quehayhoy.users");
	$select_id->execute([
              
            ]);
	$rowID = $select_id->fetchAll(PDO::FETCH_ASSOC);

	$exists = false; //Con esta variable se checkea si la id en la url existe, de caso que sea falso, mandar al usuario al inicio

  if(!empty($_GET['id'])){
  	if(isset($_SESSION['user'])&&htmlspecialchars($_GET['id'])==htmlspecialchars($_SESSION['user']['id'])){
  		header("Location:miPerfil.php");
  	}
  	$pageID = htmlspecialchars($_GET['id']);
	foreach($rowID as $IDsrow){
    // Si la id ingresada del evento existe, marcar la variable como cierto y continuar con el código
		if(in_array($pageID, $IDsrow)){ 
			//header("Location: index.php");
			$exists = true;
			
		}
	}
	if($exists != true){
		header("Location: index.php");
	}  	

	$user_perfil = $db->prepare("SELECT * FROM u956478100_quehayhoy.users WHERE id = '$pageID' LIMIT 1");
	$user_perfil->execute([]);
	$row_perfil = $user_perfil->fetch(PDO::FETCH_ASSOC);

	$username_perfil = $row_perfil['username'];
  $descripcion_perfil = $row_perfil['descripcion'];
  $foto_perfil = $row_perfil['foto'];
  $pagina_perfil = $row_perfil['pagina'];
  $instagram_perfil = $row_perfil['instagram'];
  $facebook_perfil = $row_perfil['facebook'];

  	$totalevents = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events WHERE username = '$username_perfil'")->fetchColumn();

  	if($totalevents != 0){
  		$infousers_stmt=$db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events WHERE username = '$username_perfil' ORDER BY primera_fecha ASC, primera_hora ASC");

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
				header("Location: perfil.php?pag=1&id=".$pageID);
				$pagActive = 1;
			}
    	}
    	else{
			header("Location: perfil.php?pag=1&id=".$pageID);
			$pagActive = 1;
    	}

    	$currentUrl = $_SERVER['REQUEST_URI'];

    	$getsArray = array("pag","id");
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

}
else{
	header("Location: index.php");
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
	<meta name="description" content="Perfil de <?php echo($username_perfil); ?> donde se muestra su descripción, foto de perfil, links de contacto y los eventos que publicó.">
    <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png" sizes="16x16" />

	<title><?php echo($username_perfil); ?> | QueHayHoy?</title>
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
				<div class="mt-3 alignCenterContainer" style="border: 2px solid #ced4da;">
				<div class="alignCenter alignCenterContainer" style="max-width: 100%; max-height:100%;">
					<div class="alignCenter mt-2" style="display: inline-block; width: 128px;height: 128px; max-width: 100%; max-height: 100%;" class="">
                <div id="img_view" class="fullSize imgOtroPerfil" style="background-image: url('includes/uploaded/perfiles/<?php echo($foto_perfil); ?>');">
                  <div id="textImgRemove" class="fullSize">
                  <!--<img src="includes/uploaded/portadas/<?php echo($_SESSION['user']['foto']); ?>" class="img-fluid p-2 fullSize imgPerfil" style="object-position: center; object-fit: cover; border-radius: 4rem;">--> 
                   <!--<img src="includes/images/demo.jpg" class="img-fluid p-2 fullSize imgPerfil" style="object-position: center; object-fit: cover; border-radius: 4rem;"> -->
                  </div>
                    
                </div>
					</div>
					<div class="alignCenter" style="display: inline-block; max-height:100%;max-width: 100%;">
						<h4 class="mt-2"><?php echo($username_perfil); ?></h4>
					</div>
				</div>
				
				<div class="text-center my-2">
          <p><?php echo($descripcion_perfil); ?></p>
				</div>
				
			</div>

			<div class="mt-3">

            <div class="mt-3 alignCenterContainer text-center">
			<?php 
			if(!empty($pagina_perfil)){
				echo('
					<div>
						<a href="');echo($pagina_perfil);echo('" target="_blank" rel="noopener noreferrer">¡VISITA NUESTRA PÁGINA WEB!</a>
					</div>
					');
					}
			 ?>

				<div class="mt-3" style="display:flex; justify-content: space-evenly;">
					<?php 
					if(!empty($instagram_perfil)){
						echo('
						<div class="alignCenter" style="display:inline-block;">
							<a class="linkIG" href="');echo($instagram_perfil);echo('" target="_blank" rel="noopener noreferrer"" style="font-size: 50px;"><i class="bi bi-instagram"></i></a>
						</div>
							');
					}
					if(!empty($facebook_perfil)){
						echo('
						<div class="alignCenter" style="display:inline-block;">
							<a class="linkFB" href="');echo($facebook_perfil);echo('" target="_blank" rel="noopener noreferrer"" style="font-size: 50px;"><i class="bi bi-facebook" style=""></i></a>
						</div>
							');
					}
					 ?>
					
						
				</div>
				
			</div>
			</div>
			</div>
			
			<div class="col-12 col-md-8">
				<h1 class="text-center mt-2">Eventos publicados:</h1>
				<!-- Empieza publicacion -->
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
										<a href="evento.php?id=');echo($eventsID[$i]);echo('" class="stretched-link"></a>	
									</div>
									<div class="col-12 col-md-10 position-relative heightInfoEvent">
										<h2 class="text-dark align-self-top">'); echo($eventsTitulo[$i]);echo('</h2>
										<div style="overflow: hidden;">
											<h5 class="truncateText text_subtitle">'); echo($eventsDesc[$i]); echo('</h5>
										</div>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-calendar-event"></i> '); echo($eventsFecha[$i]);echo('</h5>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-geo-alt-fill"></i> '); echo($eventsLugar[$i]); echo('</h6>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-coin"></i> $');echo($eventsPrecio[$i]);echo('</h6>
										<a href="evento.php?id=');echo($eventsID[$i]); echo('" class="stretched-link"></a>					
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
										<a href="evento.php?id=');echo($eventsID[$i]);echo('" class="stretched-link"></a>	
									</div>
									<div class="col-12 col-md-10 position-relative heightInfoEvent">
										<h2 class="text-dark align-self-top">'); echo($eventsTitulo[$i]);echo('</h2>
										<div style="overflow: hidden;">
											<h5 class="truncateText text_subtitle">'); echo($eventsDesc[$i]); echo('</h5>
										</div>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-calendar-event"></i> '); echo($eventsFecha[$i]);echo('</h5>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-geo-alt-fill"></i> '); echo($eventsLugar[$i]); echo('</h6>
										<h5 class="align-self-bottom text_subtitle"><i class="bi bi-coin"></i> $');echo($eventsPrecio[$i]);echo('</h6>
										<a href="evento.php?id=');echo($eventsID[$i]); echo('" class="stretched-link"></a>					
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
  			console.log('Submit button is clicked');
  			buttonClicked = true;
  			document.getElementById('nav_id').style.height="100%";
  			document.querySelectorAll('.event_id').forEach(el=>el.style.transition="0");
  			document.querySelectorAll('.event_id').forEach(el=>el.classList.remove('me-5'));
  			document.getElementById("crearEventoID").classList.remove('pe-5');
  			document.getElementById("bodyID").style.height="100%";
  			document.getElementById("bodyID").style.overflowY="hidden";
 		}
 		else{
			console.log('Already clicked');
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


</script>
</body>
</html>