<!DOCTYPE html>
<?php require_once 'connection.php';
include 'functions.php';
	$iniciado = false;
	session_start();
//Checkear si el usuario ha iniciado sesión
if(!isset($_SESSION['user']) || $_SESSION['user']['admin'] != 1){
		$iniciado = false;
		header("Location:index.php");
	}
	else{
		$iniciado = true;
	}

	//Conectando con la base de datos y agarrando los eventos de la tabla events

	$select_id = $db->prepare("SELECT id from u956478100_quehayhoy.unvalidated_events");
	$select_id->execute([
              
            ]);
	$rowID = $select_id->fetchAll(PDO::FETCH_ASSOC);


	$exists = false; //Con esta variable se checkea si la id en la url existe, de caso que sea falso, mandar al usuario al inicio.

if(!empty(htmlspecialchars($_GET['id']))){
	$pageID = htmlspecialchars($_GET['id']);
	foreach($rowID as $IDsrow){
    // Si la id ingresada del evento existe, marcar la variable como cierto y continuar con el código
		if(in_array($pageID, $IDsrow)){ 
			//header("Location: index.php");
			$exists = true;
		}
	}
	if($exists == true){
    // Seleccionar de la base de datos aquel evento que corresponda con su id
		$select_stmt = $db->prepare("SELECT * from u956478100_quehayhoy.unvalidated_events WHERE id = $pageID LIMIT 1"); 
    	$select_stmt->execute([
              
            ]);
     	$row = $select_stmt->fetch(PDO::FETCH_ASSOC);

      //Crear variables para cada atributo guardado

     	$username = $row['username'];
     	$email = $row['email'];
     	$created = $row['created'];
     	$portada = $row['portada'];
     	$titulo = $row['titulo'];
     	$descripcion = $row['descripcion'];
     	$categoria = $row['categoria'];
     	$generos = $row['generos'];
     	$fecha = $row['fecha'];
     	$primera_fecha = $row['primera_fecha'];
     	$primera_hora = $row['primera_hora'];
     	$hora = $row['hora'];
      $lugar = $row['lugar'];
     	$ubicacion = $row['ubicacion'];
     	$departamento = $row['departamento'];
     	$precio = $row['precio'];
     	$link = $row['link'];
     	$nombre = $row['nombre'];
     	$mail = $row['mail'];
     	$telefono = $row['telefono'];

     	$fechanocoma_publi = str_replace(",", "", $fecha);
     	$fechanospace_publi = explode(' ', $fechanocoma_publi);
     	$f = 0;
     	foreach($fechanospace_publi as $newfechanospace){
     		$eventoTimestamp_publi = str_replace("_", " ", $newfechanospace);
     		$timestamp_publi = strtotime($eventoTimestamp_publi);
     		$dayNum_publi = date('d', $timestamp_publi);
     		$day_publi = GetDay($timestamp_publi);
		$month_publi = GetMonth($timestamp_publi);
		$year_publi = date('Y', $timestamp_publi);
		$horanocoma_publi = str_replace(",", "", $hora);
		$horanospace_publi= explode(' ', $horanocoma_publi);
		$eventoHora_publi[$f] = str_replace("_", " ", $horanospace_publi[$f]);
		$fechaPublicacion[$f] = $day_publi . ", " .$dayNum_publi . " de " . $month_publi . " | " . $eventoHora_publi[$f] ."HS.";
		$f++;
     	}
     			
     			//echo nl2br ("\n");	
     			
     			

      //Como el usuario puede guardar varios generos a la hora de crear un evento, se guardan con coma y espacio (ejemplo: genero1, genero2, genero3). Las siguientes dos lineas reemplazan la coma por un caracter vacio, y la segunda linea de código elimina los espacios y guarda en un array cada palabra. Se puede ver un ejemplo visual en el evento de la id 2 (evento.php?id=2)

      $generonocoma = str_replace(",", "", $generos);
      $generonospace= explode(' ', $generonocoma); 
    		

    	$perfil_stmt = $db->prepare("SELECT * from u956478100_quehayhoy.users WHERE username = :username LIMIT 1"); 
    	$perfil_stmt->execute([
              'username' => $username
            ]);
     	$row_perfil = $perfil_stmt->fetch(PDO::FETCH_ASSOC);

     	$perfil_foto = $row_perfil['foto'];
     	$perfil_descripcion = $row_perfil['descripcion'];
     	$perfil_pagina = $row_perfil['pagina'];
     	$perfil_instagram = $row_perfil['instagram'];
     	$perfil_facebook = $row_perfil['facebook'];
    	$perfilID = $row_perfil['id'];

    	if(str_contains($perfil_instagram, "https://") == false && !empty($perfil_instagram)){
     	 	$perfil_instagram = "https://".$perfil_instagram;
     	 }

     	 if(str_contains($perfil_pagina, "https://") == false && !empty($perfil_pagina)){
     	 	$perfil_pagina = "https://".$perfil_pagina;
     	 }

     	 if(str_contains($perfil_facebook, "https://") == false && !empty($perfil_facebook)){
     	 	$perfil_facebook = "https://".$perfil_facebook;
     	 }

     	 if(str_contains($link, "https://") == false && !empty($link)){
     	 	$link = "https://".$link;
     	 }

	}
	else{
		header("Location: validation.php");
	}

	$currentUrl = $_SERVER['REQUEST_URI'];
	$parsedURL = parse_url($currentUrl, PHP_URL_QUERY);
	if(!empty($parsedURL)){
		$parsedURL = preg_replace('/=[\s\S]+?&/', ' ', $parsedURL);
		$parsedURL = substr_replace($parsedURL, '', strpos($parsedURL, '='));
		$parsedURL = explode(' ', $parsedURL);
		for($x=0;$x<count($parsedURL);$x++){
	    		if(!str_contains($parsedURL[$x], "id")){
	    			header("Location:index.php");
	    		}
	    	}
	}
	else{
		header("Location:validation.php");
	}

	$portadaSize = getimagesize("includes/uploaded/portadas/".$portada);

		if(isset($_REQUEST['buttonAceptado'])){
	$insert_stmt_aceptado = $db->prepare("INSERT INTO u956478100_quehayhoy.validated_events (username,email,created,portada,titulo,descripcion,categoria,generos,fecha,primera_fecha,hora,primera_hora,lugar,ubicacion,departamento,precio,link,nombre,telefono) VALUES (:username,:email,:created,:portada,:titulo,:descripcion,:categoria,:generos,:fecha,:primera_fecha,:hora,:primera_hora,:lugar,:ubicacion,:departamento,:precio,:link,:nombre,:telefono)");
    $insert_stmt_aceptado->execute(
              [
                ':username' => $username,
                ':email' => $email,
                ':created' => $created,
                ':portada' => $portada,
                ':titulo' => $titulo,
                ':descripcion' => $descripcion,
                ':categoria' => $categoria,
                ':generos' => $generos,
                ':fecha' => $fecha,
                ':primera_fecha' => $primera_fecha,
                ':hora' => $hora,
                ':primera_hora' => $primera_hora,
                ':lugar' => $lugar,
                ':ubicacion' => $ubicacion,
                ':departamento' => $departamento,
                ':precio' => $precio,
                ':link' => $link,
                ':nombre' => $nombre,
                ':telefono' => $telefono
              ]);

              $insert_stmt_delete = $db->prepare("DELETE FROM u956478100_quehayhoy.unvalidated_events WHERE id=$pageID;");
			if($insert_stmt_delete->execute([])){
				header("location: validation.php");
			}
}

if(isset($_REQUEST['buttonRechazado'])){
	$insert_stmt_rechazado = $db->prepare("DELETE FROM u956478100_quehayhoy.unvalidated_events WHERE id=$pageID");
    if($insert_stmt_rechazado->execute()){
    	header("Location: validation.php");
	}
}
	
}
//Si no se ha ingreado ninguna id, volver el usuario al inicio
else{
	header("Location: validation.php");
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
	<meta name="description" content="Página sólo permitida para los administradores del sitio. Aquí se puede verificar si los eventos subidos por los usuarios son aptos para el sitio web o descartados.">
    <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png" sizes="16x16" />
	<link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="includes/css/style_evento.css">
	<link rel="stylesheet" type="text/css" href="includes/css/style_footer.css">
	<link rel="stylesheet" type="text/css" href="includes/css/css-aspect-ratio.css">
	<link rel="stylesheet" type="text/css" href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.18.0/maps/maps.css"/>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.18.0/maps/maps-web.min.js"></script>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.18.0/services/services-web.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://api.tomtom.com/maps-sdk-for-web/cdn/plugins/SearchBox/3.1.12/SearchBox.css">

	<title>Validar evento: <?php echo($titulo); ?> | QueHayHoy?</title>
</head>
<body id="bodyID">
	<div class="bgFirstScreen">
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
	
</div>

<div class="" style="">
	<div class="mt-3 text-center" style="position: fixed;
      bottom: 20px;
      right: 180px;
      z-index: 5;">
				<div class="" style="display: flex; justify-content:center;">
					<h5 class="linkCol_precio"><i class="bi bi-coin"></i> <?php echo($precio); ?></h5>
				</div>
	</div>

	<div class="mt-3 text-center" style="position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 5;">
				<div class="" style="display: flex; justify-content:center;">
					<h4 class="linkBL_evento"><a class="stretched-link a_linkBL" href="<?php echo($link); ?>" target="_blank" rel="noopener noreferrer">Ir al evento</a>
				</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row" style="justify-content:space-evenly;";>
		<div class="col-12 col-md-8 mt-5" style="display:flex; flex-direction: column;">
			<h1 class="alignCenter text-center"><?php echo($titulo); ?></h1>

			<div class="mt-3 alignCenter position-relative">
				<div style="display: inline-block; width: 64px;height: 64px;" class="">
					<img alt="Foto de perfil del usuario que creó este evento." src="includes/uploaded/perfiles/<?php echo($perfil_foto); ?>" class="img-fluid" style="object-position: center; object-fit: cover; width: 64px;height: 64px; border-radius: 50%;">
				</div>
				<div style="display: inline-block;">
					<h6 class="ms-3 text-center"><?php echo($username); ?></h6>
				</div>
				<a href="perfil.php?id=<?php echo($perfilID); ?>" class="stretched-link"></a>
			</div>

			<div class="mt-3" style="display: flex; flex-direction: column;">
				<div class="" style="min-width: 100%; display: flex; flex-direction: column;">
					<div class="alignCenter portadaContainer aspect-ratio" style="max-width:100%;max-height:50rem;width:40rem;--aspect-ratio-w: <?php echo($portadaSize[0]); ?>; --aspect-ratio-h: <?php echo($portadaSize[1]); ?>;">
						<img alt="Portada del evento <?php echo($titulo); ?>" class="portadaChild" src="includes/uploaded/portadas/<?php echo($portada); ?>" class="alignCenter p-2" style="">	
					</div>
					
				</div>
				
				<div class="mt-5 mx-5" style="max-width: 100%; max-height: 50%;">
					<h2>Descripción</h3>
					<p style="white-space: pre-line; word-wrap:break-word;" class="mt-3"><?php echo($descripcion); ?></p>
					<div class="mt-3 text-center" style="display:flex; justify-content: space-evenly; border-top: 2px solid #e9ecef;">
						<div class="mt-3" style="display:inline-block;">
							<h6>Categoría:</h6>
							<h6><?php echo($categoria); ?></h6>
						</div>
						<?php if(!empty($generos)){
							echo('
						<div class="mt-3" style="display: inline-block;">
							<h6>Género(s):</h6>
							<h6>');
					              //Para el caso de que el genero marcado sea más de una palabra (por ejemplo ciencia ficcion), si se guardara el valor "Ciencia Ficción", a la hora de separar las palabras para los generos se guardarian como dos distintos: Ciencia y Ficcion. Es por esto que se guarda con un "_", y a la hora de mostrarlo en pantalla, quitar el guion y reemplazarlo por un espacio.
					              if(0 == count($generonospace)-1){
					                    //echo("Sin géneros.");
					              }
					              else{
					                for($i=0; $i < (count($generonospace)-1); $i++){
					                  //Estos condicionales checkean si es el ultimo genero de la lista ya que por cada genero se lo separa por un guion, excepto para el ultimo genero ya que no habria nada a su derecha
					                  if($i == (count($generonospace)-2)){
					                    $genero = str_replace("_", " ", $generonospace[$i]);
					                    echo($genero);
					                  }else{
					                    $genero = str_replace("_", " ", $generonospace[$i]) . " - ";
					                    echo($genero);
					                  }
					                  }  
					              }echo('
					               
					              </h6>
						</div>
								');
						} ?>
						
					</div>
				</div>
			</div>

			
			
		</div>
		<div class="col-12 col-md-4 mt-5">
			<div style="border: 2px solid #ced4da; max-height: 200px; overflow: auto;">
				<div style="display:flex; justify-content:center">
					<h2 class="text-center mt-3" style="border-bottom: 2px solid #e9ecef;">Fechas</h2>
				</div>
				
				<div class="mt-3" style="">
					<?php 
					for ($i=0; $i < ($f-1); $i++) { 
						echo('
							<div style="display:flex; justify-content:center">
								<p class="" style="border-bottom: 2px solid #f8f9fa;">');echo($fechaPublicacion[$i].'</p>
							</div>
							');
					}
					 ?>
					
				</div>				
			</div>

			<div class="mt-3 text-center">
				<div class="" style="display: flex; justify-content:center;">
					<h5 class="linkCol_precio"><i class="bi bi-coin"></i> <?php echo($precio); ?></h5>
				</div>
			</div>

			<div class="mt-3 alignCenterContainer" style="border: 2px solid #ced4da;">
				<div class="position-relative alignCenter alignCenterContainer">
					<div class="alignCenter mt-2" style="display: inline-block; width: 128px;height: 128px;" class="">
						<img alt="Foto de perfil del usuario que creó este evento." src="includes/uploaded/perfiles/<?php echo($perfil_foto); ?>" class="img-fluid" style="object-position: center; object-fit: cover; width: 128px;height: 128px; border-radius: 4rem;">
					</div>
					<div class="alignCenter" style="display: inline-block;">
						<h4 class="mt-2"><?php echo($username); ?></h4>
					</div>
					<a href="perfil.php?id=<?php echo($perfilID); ?>" class="stretched-link"></a>
				</div>
				
				<div class="text-center">
					<p><?php echo($perfil_descripcion); ?></p>
				</div>
				
			</div>
			<div class="mt-3 alignCenterContainer text-center">
			<?php 
			if(!empty($perfil_pagina)){
				echo('
					<div>
						<h4 class="linksFiltros"><a class="linksFiltros" href="');echo($perfil_pagina);echo('" target="_blank" rel="noopener noreferrer">¡VISITA NUESTRA PÁGINA WEB!</a></h4>
					</div>
					');
					}
			 ?>

				<div class="mt-3" style="display:flex; justify-content: space-evenly;">
					<?php 
					if(!empty($perfil_instagram)){
						echo('
						<div class="alignCenter" style="display:inline-block;">
							<a class="linkIG_Evento" href="');echo($perfil_instagram);echo('" target="_blank" rel="noopener noreferrer"" style="font-size: 50px;"><i class="bi bi-instagram"></i></a>
						</div>
							');
					}
					if(!empty($perfil_facebook)){
						echo('
						<div class="alignCenter" style="display:inline-block;">
							<a class="linkFB" href="');echo($perfil_facebook);echo('" target="_blank" rel="noopener noreferrer"" style="font-size: 50px;"><i class="bi bi-facebook" style=""></i></a>
						</div>
							');
					}
					 ?>
					
						
				</div>
				
			</div>


		</div>

		<div class="mt-5 text-center">
				<div class="" style="display: flex; justify-content:center;">
					<div class="linkCol_evento inCenter" style="position:relative;">
						<h4 class=""><a class="stretched-link a_linkBL" href="<?php echo($link); ?>" target="_blank" rel="noopener noreferrer">Ir al evento</a></h4>
					</div>
					
				</div>
			</div>

		<div class="col-12 mt-5" style="border-top: 2px solid #e9ecef;">
				<div class="mt-3 mx-3">
					<h2>Ubicación</h2>
					<h5 class="mt-3"><?php echo($lugar); ?></h5>
					<p><?php echo($ubicacion.", ".$departamento); ?></p>
				</div>	
		</div>
			<div class="container-fluid">
				<div class="row inCenter">
					<div class="col-12" style="height: 75vh; width: 80vw;">			
					    <div class="inCenter" style="height: 100%; width: 100%;">
					        <div id="map" class="borderBoxes" style="height: 90%; width: 90%;"></div> 
					    </div>
					</div>
				</div>
			</div>
		
	</div>
</div>


<h1>Información sobre el creador de la página</h1>
     <table class="table table-striped">
     	<tr>
     		<th>Nombre</th>
        <th>Username</th>
     		<th>Mail</th>
     		<th>Telefono</th>
     	</tr>
     	<tr>
     		<td><?php echo($nombre); ?></td>
        <td><?php echo($username); ?></td>
     		<td><?php echo($email); ?></td>
     		<td><?php echo($telefono); ?></td>
     		
     	</tr>
     </table>



<div class="container-fluid py-5 bg-dark border border-top border-dark border-3">
  	<div class="row ">
  		<div class="col-12 inCenter">
  			<form method="POST">
  				<button class="btn btn-danger mx-5" type="submit" name="buttonRechazado">RECHAZAR</button>
  				<button class="btn btn-success mx-5" type="submit" name="buttonAceptado">ACEPTAR</button>
  			</form>
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

let width = screen.width;
	if(width < 350){
document.getElementById("divcrear").classList.remove('p-5');
  		document.getElementById("divcrear").classList.add('py-5');
  		document.getElementById("divcrear").classList.add('text-center');
	}
	  		

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

 //Mapa

  var ll = new tt.LngLat(0, 0);
  var position = "<?php echo ($ubicacion); ?>";
  var departamento = "<?php echo ($departamento); ?>";

  if(position.toLowerCase() == "jose ellauri 350" || position.toLowerCase() == "josé ellauri 350"){
    position = "Punta Carretas Shopping";
  }

    //Conexion con el API de TomTom (el servicio de mapas), de idioma español, con un predeterminado zoom, y de centro ll (depende del evento, y desactivar que cuando se haga scroll con el mouse sobre el mapa se haga zoom, ya que molestaria al usuario que quisiera navegar por la pagina)
      var map = tt.map({
        key: "ctntQqc8yTWnCNRW3iySYkxDAIrVejsM",
        container: "map",
        language: "es-419",
        center: ll,
        zoom: "14",
      });
      map.scrollZoom.disable();
      //Funcion que toma coordenadas de longitud y latitud y el mapa va hacia esa direccion
      var moveMap = function(lnglat){
        map.flyTo({
          center:lnglat,
          zoom: "14",
          curve: "0",
          speed: "0"
        })
      }
      //Funcion para manejar los resultados dados por la funcion de busqueda más abajo
      var handleResults = function(result){
        //Si existe algun resultado de tal ubicacion del evento, proseguir con el código
        if(result.results){
          //Marcando el diseño del icono con sus parametros
          let mapIcon = document.createElement('div');
          mapIcon.className = 'markerIcon';
          /*
          [MARCADO COMO CODIGO YA QUE POR AHORA SE DECIDIO NO USARLO]
          Todo lo de abajo marca los parametros del texto del nombre del lugar al lado del icono del marker (Por ejemplo que diga Teatro Solis al lado).

          let popupIcon = document.createElement('div');
          popupIcon.className = 'popupStyle';
          popupIcon.id = 'popupStyleID';
          //popupIcon.innerHTML = '<h6><?php echo($lugar); ?></h6>'
          popupIcon.style.pointerEvents = "none";*/
          //var markerHeight = 18, markerRadius = 10, linearOffset = 25;
          //Marcando posicion del lugar
          /*var popupOffsets = {
           'top': [0, 0],
            'top-left': [0,0],
            'top-right': [0,0],
            'bottom': [0, -markerHeight],
            'bottom-left': [linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
            'bottom-right': [-linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
            'left': [markerRadius, (markerHeight - markerRadius) * -1],
            'right': [-markerRadius, (markerHeight - markerRadius) * -1]
            };*/
          //var textLoc = "<?php echo($ubicacion); ?>";


            //De todos los resultados similares posibles que haya salido de la busqueda, agarrar al más parecido y llamar a la funcion moveMap dandole ese resultado.
          moveMap(result.results[0].position)

          //Determinar el texto y clases
          /*var popup = new tt.Popup({
            closeButton: false,
            closeOnClick: false,
            offset: popupOffsets,
            anchor: 'left'
          }).setLngLat(result.results[0].position).setDOMContent(popupIcon).addTo(map);
          document.getElementById('popupStyleID').parentNode.style.backgroundColor = "transparent";
          document.getElementById('popupStyleID').parentNode.classList.add('inCenter');
          document.getElementById('popupStyleID').classList.add('inCenter');
          document.getElementById('popupStyleID').parentNode.style.boxShadow = "none";
          document.getElementById('popupStyleID').parentNode.previousSibling.style.display = "none";*/

          //Añadir el icono al mapa
          var marker = new tt.Marker({
            element: mapIcon
          })
          .setLngLat(result.results[0].position)
          .addTo(map);
          
        }
        
      }
      //Se busca la posicion del evento y se le agrega la palabra Uruguay para que sea más preciso a la hora de buscar las coordenadas del lugar. Luego se le manda a la funcion handleResults para trabajar con los resultados
      var search = new tt.services.fuzzySearch({
          key: "ctntQqc8yTWnCNRW3iySYkxDAIrVejsM",
          query: position + ", "+departamento+", Uruguay",

        }).then(handleResults)

	//Carousel
	let slideEvents = 4;

	if(width >= 768 && width < 1400){
			slideEvents = 4;
		}
		else if(width >= 1400){
			slideEvents = 6;
		}
		else{
			slideEvents = 1;
		}
	
	var items_events = document.querySelectorAll('.carousel_evento .carousel-item_evento');
	items_events.forEach((e)=>{
		let next_evento = e.nextElementSibling;
		for(var i =0; i < slideEvents; i++){
			if(!next_evento){
				next_evento = items_events[0];
			}
			let cloneChild_evento = next_evento.cloneNode(true);
			e.appendChild(cloneChild_evento.children[0]);
			next_evento = next_evento.nextElementSibling;
		}
	})

	
</script>
</body>
</html>