<!DOCTYPE html>
<?php require_once 'connection.php';
include 'functions.php';
	$iniciado = false;
	session_start();
	//Checkear si el usuario ha iniciado sesión
if(!isset($_SESSION['user'])){
		$iniciado = false;
	}
	else{
		$iniciado = true;
	}

	//Conectando con la base de datos y agarrando los eventos de la tabla events

	$select_id = $db->prepare("SELECT id from u956478100_quehayhoy.validated_events");
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
		$select_stmt = $db->prepare("SELECT * from u956478100_quehayhoy.validated_events WHERE id = $pageID LIMIT 1"); 
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

     	/*echo($username . "<br>");
     	echo($email . "<br>");
     	echo($created . "<br>");
     	echo($portada . "<br>");
     	echo($titulo . "<br>");
     	echo($descripcion . "<br>");
     	echo($categoria . "<br>");
     	echo($genero . "<br>");
     	echo($fecha . "<br>");
     	echo($hora . "<br>");
     	echo($ubicacion . "<br>");
     	echo($precio . "<br>");
     	echo($link . "<br>");
     	echo($nombre . "<br>");
     	echo($mail . "<br>");
     	echo($pagina . "<br>");
     	echo($instagram . "<br>");
     	echo($facebook . "<br>");*/

     	$totalEvents = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events WHERE categoria = '$categoria'")->fetchColumn();


     	$select_events = $db->prepare("SELECT * from u956478100_quehayhoy.validated_events WHERE categoria = '$categoria'"); 
    	$select_events->execute([
              
            ]);

     	for($k = 0; $k < $totalEvents; $k++){
    	
    		$rowEvents[$k][] = $select_events->fetch(PDO::FETCH_ASSOC);
    		//$users_id[$k] = $rowusers[];
    	}	
    	$e = 0;
    	foreach($rowEvents as $rowEvents_info){
    		if($rowEvents_info[0]['id'] != $pageID){
    			$eventoID[$e] = $rowEvents_info[0]['id'];
    			$eventoPortada[$e] = $rowEvents_info[0]['portada'];
    			$eventoTitulo[$e] = $rowEvents_info[0]['titulo'];
    			$eventoDesc[$e] = $rowEvents_info[0]['descripcion'];
    			$eventoFechas = $rowEvents_info[0]['fecha'];
    			$fechanocoma = str_replace(",", "", $eventoFechas);
     			$fechanospace= explode(' ', $fechanocoma);
     			$eventoTimestamp = str_replace("_", " ", $fechanospace[0]);
     			//echo nl2br ("\n");
     			$timestamp = strtotime($eventoTimestamp);
     			$dayNum = date('d', $timestamp);
				$day = GetDay($timestamp);
				$month = GetMonth($timestamp);
				$year = date('Y', $timestamp);
    			$eventoHoras = $rowEvents_info[0]['hora'];
    			$horanocoma = str_replace(",", "", $eventoHoras);
     			$horanospace= explode(' ', $horanocoma);
     			$eventoHora = str_replace("_", " ", $horanospace[0]);
     			$eventoFecha[$e] = $day . ", " .$dayNum . " de " . $month . " | " . $eventoHora ."hs";
				//echo($cineID[$c]);
    			$eventoLugar[$e] = $rowEvents_info[0]['lugar'];
    			$eventoPrecio[$e] = $rowEvents_info[0]['precio'];
    			$e++;
    		}
    			
    	}

    	$totalcomments = $db->query("SELECT count(*) FROM u956478100_quehayhoy.comments WHERE id_event = $pageID")->fetchColumn();
    	if($totalcomments != 0){
    		$select_comments = $db->prepare("SELECT * from u956478100_quehayhoy.comments WHERE id_event = '$pageID' ORDER BY id_comment DESC"); 
    		$select_comments->execute([
              
            ]);

     		for($k = 0; $k < $totalcomments; $k++){
    	
    		$rowComments[$k][] = $select_comments->fetch(PDO::FETCH_ASSOC);
    		//$users_id[$k] = $rowusers[];
    		}	

    		$c = 0;
    		foreach($rowComments as $rowComments_info){
    			$username_comment[$c] = $rowComments_info[0]['username'];
    			$created_comment[$c] = $rowComments_info[0]['created'];
    			$foto_comment[$c] = $rowComments_info[0]['foto'];
    			$id_user[$c] = $rowComments_info[0]['id_user'];
    			$comment[$c] = $rowComments_info[0]['comment'];
    			$c++;
    		}
    	}
    		

    	if(isset($_REQUEST['publicado'])){
    		$usernamePubli = $_SESSION['user']['username'];
    		$emailPubli = $_SESSION['user']['email'];
    		$fotoPubli = $_SESSION['user']['foto'];
    		$createdPubli = new DateTime("now", new DateTimeZone('America/Montevideo'));
            $createdPubli = $createdPubli->format('Y-m-d H:i:s');
            $comment = htmlspecialchars($_REQUEST['comentario']);
            if(empty($comment)){
        		$errorMsg[0][] = "El comentario no puede estar vacío.";
      	}
      	if(strlen($comment)>1000){
      		$errorMsg[0][] = "El comentario debe que tener menos de 1000 caracteres.";
      	}
      	$ideventPubli = $pageID;
      	$idcommentPubli = $totalcomments;
      	$idUser = $_SESSION['user']['id'];
      	if(empty($errorMsg)){
      	$insert_comment = $db->prepare("INSERT INTO u956478100_quehayhoy.comments (id_comment,id_event,id_user,username,email,foto,created,comment) VALUES (:id_comment,:id_event,:id_user,:username,:email,:foto,:created,:comment)");
              if($insert_comment->execute(
              [
                ':id_comment' => $idcommentPubli,
                ':id_event' => $ideventPubli,
                ':id_user'=> $idUser,
                ':username' => $usernamePubli,
                ':email' => $emailPubli,
                ':foto' => $fotoPubli,
                ':created' => $createdPubli,
                ':comment' => $comment
              ])
            ){
              header("location: evento.php?id=".$pageID);
              }
      	}
    	}

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
		header("Location: index.php");
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
		header("Location:index.php");
	}

	$portadaSize = getimagesize("includes/uploaded/portadas/".$portada);
	
}
//Si no se ha ingreado ninguna id, volver el usuario al inicio
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
	<meta name="description" content="Página que muestra todos los detalles necesarios para ir al evento <?php echo($titulo); ?>">
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

	<title><?php echo($titulo); ?> | QueHayHoy?</title>
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
					<p style="white-space: pre-line; word-wrap:break-word;" class="mt-2 text-center mx-3"><?php echo($perfil_descripcion); ?></p>
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
					<div class="inCenter" style="position:relative;">
						<h4 class="linkBL_evento"><a class="stretched-link a_linkBL" href="<?php echo($link); ?>" target="_blank" rel="noopener noreferrer">Ir al evento</a>
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

			<div class="col-10 marginCards p-5 bgCEvento" id="divcrear">
				<h2>Crear un evento</h2>
				<h5 style="margin-bottom: 1.5em;" class="text_subtitle">¡Publica tu actividad a cientas de personas!</h5>
				<a href = "crearEvento.php" aria-label="Crear un evento" class="text-dark text-center border border-dark rounded-pill btnBuscarEventos mt-3 event_id" id = "">Crear un evento</a>
			</div>
			
			<?php 
			if($totalEvents >= 6){
				echo('
				<!--Carrusel-->
				<div class="mt-5 mx-2" id="eventos">
					<h1>Eventos similares</h1>

					<div class="carousel slide carousel_evento carousel-light carousel-transition inCenter mt-3" data-bs-ride="carousel" id="carouselEvento" data-bs-interval="5000">
						<div class="carousel-inner carousel-inner_evento carousel-inner_style">
							<div class="carousel-item active carousel-item_evento">
								<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
									<div class="card h-100">
										<img alt="Portada del evento ' . $eventoTitulo[0] . ' " src="includes/uploaded/portadas/'.$eventoPortada[0].'" alt="one" class="img-fluid imgsCard">
									<div class="card-body">
										<div style="overflow: hidden;">
											<h3 class="card-title truncateText_Carousel">'. $eventoTitulo[0] . '</h3>
											<h6 class="card-text truncateText_Carousel">' . $eventoDesc[0] . ' </h6>
											<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> '. $eventoFecha[0].'</h6>
											<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> ' . $eventoLugar[0] .'</h6>
											<h6 class="card-text"><i class="bi bi-coin"></i> $'. $eventoPrecio[0].'</h6>
										</div>
										<a href="evento.php?id='. $eventoID[0].'" class="stretched-link"></a>
										</div>
									</div>
								</div>
							</div>');
							for($i = 1; $i < $e; $i++){
								echo('
									<div class="carousel-item carousel-item_evento">
								<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
									<div class="card h-100">
										<img alt="Portada del evento'.$eventoTitulo[$i]);echo('" src="includes/uploaded/portadas/'); echo($eventoPortada[$i]); echo('" alt="one" class="img-fluid imgsCard">
									<div class="card-body">
										<div style="overflow: hidden;">
											<h3 class="card-title truncateText_Carousel">');echo($eventoTitulo[$i]);echo('</h3>
											<h6 class="card-text truncateText_Carousel">');echo($eventoDesc[$i]);echo('</h6>
											<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> ');echo($eventoFecha[$i]);echo('</h6>
											<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> ');echo($eventoLugar[$i]);echo('</h6>
											<h6 class="card-text"><i class="bi bi-coin"></i> $');echo($eventoPrecio[$i]);echo('</h6>
										</div>
										<a href="evento.php?id=');echo($eventoID[$i]);echo('" class="stretched-link"></a>
										</div>
									</div>
								</div>
							</div>
									');
								}			
							 echo('
						</div>
						<button class="carousel-control-prev buttonCarousel_L" type="button" data-bs-target="#carouselEvento" data-bs-slide="prev">
				    <span class="" aria-hidden="true"><img alt="Flecha que indica ir al anterior slide" src="includes/images/arrowPrev.png" class="iconArrow"></span>
				    <span class="visually-hidden">Previous</span>
				  </button>
				  <button class="carousel-control-next buttonCarousel_R" type="button" data-bs-target="#carouselEvento" data-bs-slide="next">
				    <span class="" aria-hidden="true"><img alt="Flecha que indica ir al siguiente slide" src="includes/images/arrowNext.png" class="iconArrow"></span>
				    <span class="visually-hidden">Next</span>
				  </button>
					</div>	
				</div>

				');
			}

			
				?>
				
				
				
				

				<div class="col-12 mt-5">
					<div class="" style="display:flex;">
						<h2 class="mx-2" style="border-bottom: 2px solid #e9ecef;">Comentarios</h2>
					</div>
					<?php 
					if($iniciado==true){
						echo('
					<form class="needs-validation" novalidate autocomplete="off" method="post" enctype="multipart/form-data">
					<h5 class="mt-5 mx-3">Publica tu comentario</h5>
					<div style="align-items:center; justify-content:center;" class="mt-3 d-block d-md-flex">
						<div style="width:80%;" class="mx-3 d-block d-md-inline-block">
							<textarea class="form-control" aria-label="Comentario" name ="comentario"></textarea>
						</div>
						<div style="" class="mx-3 d-block d-md-inline-block mt-3 mt-md-0">
							<button name="publicado">Publicar</button>
						</div>
					</div>
					</form>
							');
					}
					else{
						echo('
						<h5 class="mt-5 mx-3">¿Quieres publicar un comentario?</h5>	
						<a class="mx-3" href="login.php">Inicie sesión</a>
							');
					}
					 ?>


					 <div class="mt-5 alignCenterContainer">
					 	<h5 class="mt-3 mx-3">Últimos comentarios</h5>
					 	<!--Comentario-->
					 	<?php 
					 	if(!empty($totalcomments)){
					 		for ($i=0; $i < $totalcomments; $i++) { 
					 		echo('
					 		<div class="mt-3 mx-3 alignCenter" style="border: 2px solid #ced4da; width: 80%;">
						 	<div class="position-relative">
						 	<div style="display:inline-block;" class="ms-3 mt-3 position-relative">
						 			<img alt="Foto de perfil del usuario que hizo este comentario." src="includes/uploaded/perfiles/').$foto_comment[$i];echo('" class = "" style = "max-height:100%; max-width:100%; width:2.5rem;height:2.5rem; object-position: center; object-fit: cover; border-radius: 2.5rem;" alt="Logo de la página.">
						 			<a class = "stretched-link" href="perfil.php?id=').$id_user[$i];echo('"></a>
						 		</div>
						 		<div style="display:inline-block;" class="ms-3 mt-3 position-relative">
						 			<h6>'.$username_comment[$i].'</h6>	
						 			<a class = "stretched-link" href="perfil.php?id=').$id_user[$i];echo('"></a>
						 		</div>
						 		<div style="display:inline-block;" class="mx-1">
						 			<p><i class="bi bi-clock"></i> '.$created_comment[$i].'</p>
						 		</div>
						 	</div>
						 	<div class=" mx-3">
						 		<p>'.$comment[$i].'</p>
						 	</div>	
					 	</div>
					 			');
					 		}
					 		
					 	}
					 	else{
					 		echo('<p class = "mt-3 mx-3">No hay comentarios.</p>');
					 	}	
					 	 ?>
					 	
					 	
					 	
					 </div>
					

				</div>
		
	</div>
</div>



		

		<!--::::Pie de Pagina::::::-->
    <footer class="pie-pagina footer_text mt-5">
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
			document.getElementById('carouselEvento').classList.remove('slide');
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