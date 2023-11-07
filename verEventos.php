<!DOCTYPE html>
<?php require_once 'connection.php';
include 'functions.php';
	$iniciado = false;
	session_start();
	if(!isset($_SESSION['user'])){
		$iniciado = false;
	}
	else{
		$iniciado = true;
	}


	/*$infousers_stmt_Events= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events WHERE precio<=$precioMax ORDER BY primera_fecha $tiempoPag, primera_hora $tiempoPag");
			$totalusers_Allevents = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events WHERE precio<=$precioMax")->fetchColumn();


	$totalusers = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events")->fetchColumn();
	$totalusers_Allevents = $totalusers;*/

	$tiempoArray = array("ASC","DESC");
	$tiempoPag = "ASC";

	$precioArray = array("ASC", "DESC", "gratis");
	$precioActive = "ASC";

	$where = '';
	$order_by = '';
	$precio_min = '';
	$precio_max = '';

	if(isset($_GET['tiempo'])){
		if(isset($_GET['creacion']) || isset($_GET['precio'])){
			header("Location:verEventos.php");
		}
		else{
			if(in_array($_GET['tiempo'], $tiempoArray)){
				$order_by = 'ORDER BY primera_fecha ' . htmlspecialchars($_GET['tiempo']) . ', primera_hora ' . htmlspecialchars($_GET['tiempo']);
			}
			else{
				header("Location:verEventos.php");
			}
		}
		
	}

	else if(isset($_GET['precio'])){
		if(isset($_GET['creacion']) || isset($_GET['tiempo'])){
			header("Location:verEventos.php");
		}
		else{
			if(in_array($_GET['precio'], $precioArray)){
				$precioActive = htmlspecialchars($_GET['precio']);
				if($precioActive == "gratis"){
					$where = 'WHERE precio = 0';
				}
				else{
					$order_by = 'ORDER BY precio '.$precioActive;
				}
			}
			else{
				header("Location:verEventos.php");
			}
		}
		

	}
	else if(isset($_GET['creacion'])){
		if(isset($_GET['tiempo']) || isset($_GET['precio'])){
			header("Location:verEventos.php");
		}
		else{
			if(in_array($_GET['creacion'], $tiempoArray)){
				$order_by = 'ORDER BY id ' . htmlspecialchars($_GET['creacion']);
			}
			else{
				header("Location:verEventos.php");
			}
		}
	}
	else{
		header("Location:verEventos.php");
	}

	if(!empty($_GET['preciomin'])){
		if(is_numeric($_GET['preciomin'])){
			$where = 'WHERE precio>='.htmlspecialchars($_GET['preciomin']);
		}
		else{
			header("Location:verEventos.php");
		}
	}

	if(!empty($_GET['preciomax'])){	
		if(is_numeric($_GET['preciomax'])){
			if(empty($where)){
				$where = 'WHERE precio<='.htmlspecialchars($_GET['preciomax']);
			}
			else{
				$where .= ' AND precio<='.htmlspecialchars($_GET['preciomax']);	
			}			
		}
		else{
			header("Location:verEventos.php");
		}
	}

	$departamentoArray = array("artigas", "canelones","cerroLargo","colonia","durazno","flores","florida","lavalleja","maldonado","montevideo","paysandu","rioNegro","rivera","rocha","salto","sanJose","soriano","tacuarembo","treintayTres");


	if(!empty($_GET['departamento'])){
		if(is_array($_GET['departamento'])){
			foreach($_GET['departamento'] as $departamentoGet){
				if(in_array($departamentoGet, $departamentoArray)){
				    switch ($departamentoGet) {
						case "cerroLargo":
							$departamentoGet = "cerro largo";
							break;
					}
					if(empty($where)){
						$where .= "WHERE (departamento = '".$departamentoGet."'";
					}
					else{
						if(str_contains($where, 'departamento')){
							$where .= " OR departamento = '".$departamentoGet."'";
						}
						else{
							$where .= " AND (departamento = '".$departamentoGet."'";
						}
					}
				}
				else{
					header("Location:verEventos.php");
				}

			}
			$where .= ")";			
		}
		else{
			header("Location:verEventos.php");
		}
	}

	$categoriasArray = array("cine", "deportes","teatro","musica","gastronomia","danza","carnaval","cursos","noche","ferias","encuentros","espaciosPublicos","exposiciones","museos","otro");

	if(!empty($_GET['categoria'])){
		if(is_array($_GET['categoria'])){
			foreach($_GET['categoria'] as $categoriaGet){
				if(in_array($categoriaGet, $categoriasArray)){
					if(empty($where)){
						$where .= "WHERE (categoria = '".$categoriaGet."'";
					}
					else{
						if(str_contains($where, 'categoria')){
							$where .= " OR categoria = '".$categoriaGet."'";
						}
						else{
							$where .= " AND (categoria = '".$categoriaGet."'";
						}
					}
				}
				else{
					header("Location:verEventos.php");
				}			
			}
		}
		else{
			header("Location:verEventos.php");
		}
		$where .= ")";
	}



	

	/*for($i=0;$i<19;$i++){
		if(!empty($_GET['departamento'])&&$_GET['departamento'] == $departamentoArray[$i]){
			if(empty($where)){
				$where .= "WHERE departamento = '".$departamentoArray[$i]."'";
			}
			else{
				$where .= " AND departamento = '".$departamentoArray[$i]."'";
			}
		}
	}*/

	//echo($where);
	
	$_SESSION['where'] = $where;
	$_SESSION['order_by'] = $order_by;

	$infousers_stmt_Events= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events $where $order_by");
	$totalusers_Allevents = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events $where")->fetchColumn();

	if(!empty($_GET['pag'])){
    		$pagActive = htmlspecialchars($_GET['pag']);
    		for ($i=0; $i <= ($totalusers_Allevents/25); $i++) { 
    			$PagsRow[$i] = ($i+1);
    		}
    		if(!in_array($pagActive, $PagsRow)){ 
				header("Location: verEventos.php?pag=1&tiempo=ASC");
				$pagActive = 1;
			}
    	}
    	else{
			header("Location: verEventos.php?pag=1&tiempo=ASC");
			$pagActive = 1;
    	}
    	
    	$_SESSION['pagActive'] = $pagActive;


	$totalusers = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events")->fetchColumn();

	/*
	if(isset($_GET['tiempo'])&&in_array($_GET['tiempo'], $tiempoArray)){
		$tiempoPag = $_GET['tiempo'];
		$infousers_stmt_Events= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events ORDER BY primera_fecha $tiempoPag, primera_hora $tiempoPag");
		$totalusers_Allevents = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events")->fetchColumn();
	}

	else if(isset($_GET['precio'])&&in_array($_GET['precio'], $precioArray)){
		$precioActive = $_GET['precio'];
		if($precioActive == "gratis"){
			$infousers_stmt_Events= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events WHERE precio = 0 ORDER BY primera_fecha $tiempoPag, primera_hora $tiempoPag");
			$totalusers_Allevents = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events WHERE precio = 0")->fetchColumn();
		}
		else{
			$infousers_stmt_Events= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events ORDER BY precio $precioActive");
		}
	}
	
	else{
		$infousers_stmt_Events=$db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events ORDER BY primera_fecha ASC, primera_hora ASC");
	}


	if(!empty($_GET['preciomin']) || !empty($_GET['preciomax'])){	
		if(!empty($_GET['preciomin']) && !empty($_GET['preciomax'])){
			$precioMin = $_GET['preciomin'];
			$precioMax = $_GET['preciomax'];
			if(!empty($_GET['precio']) && in_array($_GET['precio'], $tiempoArray)){
				$infousers_stmt_Events= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events WHERE precio>=$precioMin AND precio<=$precioMax ORDER BY precio $precioActive");
			}
			else{
				$infousers_stmt_Events= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events WHERE precio>=$precioMin AND precio<=$precioMax ORDER BY primera_fecha $tiempoPag, primera_hora $tiempoPag");
			}
			$totalusers_Allevents = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events WHERE precio>=$precioMin AND precio<=$precioMax")->fetchColumn();
		}
		else if(!empty($_GET['preciomin']) && empty($_GET['preciomax'])){
			$precioMin = $_GET['preciomin'];
			if(!empty($_GET['precio']) && in_array($_GET['precio'], $tiempoArray)){
				$infousers_stmt_Events= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events WHERE precio>=$precioMin ORDER BY precio $precioActive");
			}
			else{
				$infousers_stmt_Events= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events WHERE precio>=$precioMin ORDER BY primera_fecha $tiempoPag, primera_hora $tiempoPag");
			}
			$totalusers_Allevents = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events WHERE precio>=$precioMin")->fetchColumn();
		}
		else if(empty($_GET['preciomin']) && !empty($_GET['preciomax'])){
			$precioMax = $_GET['preciomax'];
			if(!empty($_GET['precio']) && in_array($_GET['precio'], $tiempoArray)){
				$infousers_stmt_Events= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events WHERE precio<=$precioMax ORDER BY precio $precioActive");
			}
			else{
				$infousers_stmt_Events= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events WHERE precio<=$precioMax ORDER BY primera_fecha $tiempoPag, primera_hora $tiempoPag");
			}
			$totalusers_Allevents = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events WHERE precio<=$precioMax")->fetchColumn();
		}
	}
	*/
	
	$infousers_stmt_Events->execute([]);
    for($k = 0; $k < $totalusers_Allevents; $k++){
    	
    	$rowusers_Events[$k][] = $infousers_stmt_Events->fetch(PDO::FETCH_ASSOC);

    	//$users_id[$k] = $rowusers[];
    }

    $infousers_stmt=$db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events ORDER BY primera_fecha ASC, primera_hora ASC");
	$infousers_stmt->execute([]);
    for($k = 0; $k < $totalusers; $k++){
    	
    	$rowusers[$k][] = $infousers_stmt->fetch(PDO::FETCH_ASSOC);
    }
    $p = 0;
    if(!empty($totalusers_Allevents)){
	    foreach($rowusers_Events as $usersInfo_Events){
	    	if(isset($usersInfo_Events)){
	    		$idusers[$p] = $usersInfo_Events[0]['id'];
	    		$nameusers[$p] = $usersInfo_Events[0]['username'];
	    		$scoreusers[$p] = $usersInfo_Events[0]['email'];
	    		$eventsID[$p] = $usersInfo_Events[0]['id'];
	    		$eventsPortada[$p] = $usersInfo_Events[0]['portada'];
	    		$eventsTitulo[$p] = $usersInfo_Events[0]['titulo'];
	    		$eventsDesc[$p] = $usersInfo_Events[0]['descripcion'];
	    		$eventsCategoria[$p] = $usersInfo_Events[0]['categoria'];
	    		$eventsFechas = $usersInfo_Events[0]['fecha'];
	    		$fechanocoma = str_replace(",", "", $eventsFechas);
	     		$fechanospace= explode(' ', $fechanocoma);
	     		$eventsTimestamp = str_replace("_", " ", $fechanospace[0]);
	     		//echo nl2br ("\n");
	     		$timestamp = strtotime($eventsTimestamp);
	     		$dayNum = date('d', $timestamp);
				$day_events = GetDay($timestamp);
				$month_events = GetMonth($timestamp);
				$year_events = date('Y', $timestamp);
	    		$eventsHoras = $usersInfo_Events[0]['hora'];
	    		$horanocoma_events = str_replace(",", "", $eventsHoras);
	     		$horanospace_events= explode(' ', $horanocoma_events);
	     		$eventsHora = str_replace("_", " ", $horanospace_events[0]);
	     		$eventsFecha[$p] = $day_events . ", " .$dayNum . " de " . $month_events . " | " .$eventsHora. "HS.";
				//echo($cineID[$c]);
	    		$eventsLugar[$p] = $usersInfo_Events[0]['lugar'];
	    		$eventsPrecio[$p] = $usersInfo_Events[0]['precio'];
	    	}
	    	
	    		$p++;
	    }    	
    }

    $p = 0;
    $c = 0;
    $g = 0;
    $m = 0;
    $t = 0;
    $cineTitulo[] = '';
    foreach($rowusers as $usersInfo){
    	switch ($usersInfo[0]['categoria']) {
    		case 'Cine':
    			$cineID[$c] = $usersInfo[0]['id'];
    			$cinePortada[$c] = $usersInfo[0]['portada'];
    			$cineTitulo[$c] = $usersInfo[0]['titulo'];
    			$cineDesc[$c] = $usersInfo[0]['descripcion'];
    			$cineFechas = $usersInfo[0]['fecha'];
    			$fechanocoma = str_replace(",", "", $cineFechas);
     			$fechanospace= explode(' ', $fechanocoma);
     			$cineTimestamp = str_replace("_", " ", $fechanospace[0]);
     			//echo nl2br ("\n");
     			$timestamp = strtotime($cineTimestamp);
     			$dayNum = date('d', $timestamp);
				$day = GetDay($timestamp);
				$month = GetMonth($timestamp);
				$year = date('Y', $timestamp);
    			$cineHoras = $usersInfo[0]['hora'];
    			$horanocoma = str_replace(",", "", $cineHoras);
     			$horanospace= explode(' ', $horanocoma);
     			$cineHora = str_replace("_", " ", $horanospace[0]);
     			$cineFecha[$c] = $day . ", " .$dayNum . " de " . $month . " | " . $cineHora ."HS.";
				//echo($cineID[$c]);
    			$cineLugar[$c] = $usersInfo[0]['lugar'];
    			$cinePrecio[$c] = $usersInfo[0]['precio'];
    			$c++;
    			break;
    		
    		case 'Gastronomía':
    			$gastronomiaID[$g] = $usersInfo[0]['id'];
    			$gastronomiaPortada[$g] = $usersInfo[0]['portada'];
    			$gastronomiaTitulo[$g] = $usersInfo[0]['titulo'];
    			$gastronomiaDesc[$g] = $usersInfo[0]['descripcion'];
    			$gastronomiaFechas = $usersInfo[0]['fecha'];
    			$fechanocoma = str_replace(",", "", $gastronomiaFechas);
     			$fechanospace= explode(' ', $fechanocoma);
     			$gastronomiaTimestamp = str_replace("_", " ", $fechanospace[0]);
     			//echo nl2br ("\n");
     			$timestamp = strtotime($gastronomiaTimestamp);
     			$dayNum = date('d', $timestamp);
				$day = GetDay($timestamp);
				$month = GetMonth($timestamp);
				$year = date('Y', $timestamp);
    			$gastronomiaHoras = $usersInfo[0]['hora'];
    			$horanocoma = str_replace(",", "", $gastronomiaHoras);
     			$horanospace= explode(' ', $horanocoma);
     			$gastronomiaHora = str_replace("_", " ", $horanospace[0]);
     			$gastronomiaFecha[$g] = $day . ", " .$dayNum . " de " . $month . " | " . $gastronomiaHora ."HS.";
				//echo($cineID[$c]);
    			$gastronomiaLugar[$g] = $usersInfo[0]['lugar'];
    			$gastronomiaPrecio[$g] = $usersInfo[0]['precio'];
    			$g++;
    			break;
    		case 'Música':
    			$musicaID[$m] = $usersInfo[0]['id'];
    			$musicaPortada[$m] = $usersInfo[0]['portada'];
    			$musicaTitulo[$m] = $usersInfo[0]['titulo'];
    			$musicaDesc[$m] = $usersInfo[0]['descripcion'];
    			$musicaFechas = $usersInfo[0]['fecha'];
    			$fechanocoma = str_replace(",", "", $musicaFechas);
     			$fechanospace= explode(' ', $fechanocoma);
     			$musicaTimestamp = str_replace("_", " ", $fechanospace[0]);
     			//echo nl2br ("\n");
     			$timestamp = strtotime($musicaTimestamp);
     			$dayNum = date('d', $timestamp);
				$day = GetDay($timestamp);
				$month = GetMonth($timestamp);
				$year = date('Y', $timestamp);
    			$musicaHoras = $usersInfo[0]['hora'];
    			$horanocoma = str_replace(",", "", $musicaHoras);
     			$horanospace= explode(' ', $horanocoma);
     			$musicaHora = str_replace("_", " ", $horanospace[0]);
     			$musicaFecha[$m] = $day . ", " .$dayNum . " de " . $month . " | " . $musicaHora ."HS.";
				//echo($cineID[$c]);
    			$musicaLugar[$m] = $usersInfo[0]['lugar'];
    			$musicaPrecio[$m] = $usersInfo[0]['precio'];
    			$m++;
    			break;
    		case 'Teatro':
    			$teatroID[$t] = $usersInfo[0]['id'];
    			$teatroPortada[$t] = $usersInfo[0]['portada'];
    			$teatroTitulo[$t] = $usersInfo[0]['titulo'];
    			$teatroDesc[$t] = $usersInfo[0]['descripcion'];
    			$teatroFechas = $usersInfo[0]['fecha'];
    			$fechanocoma = str_replace(",", "", $teatroFechas);
     			$fechanospace= explode(' ', $fechanocoma);
     			$teatroTimestamp = str_replace("_", " ", $fechanospace[0]);
     			//echo nl2br ("\n");
     			$timestamp = strtotime($teatroTimestamp);
     			$dayNum = date('d', $timestamp);
				$day = GetDay($timestamp);
				$month = GetMonth($timestamp);
				$year = date('Y', $timestamp);
    			$teatroHoras = $usersInfo[0]['hora'];
    			$horanocoma = str_replace(",", "", $teatroHoras);
     			$horanospace= explode(' ', $horanocoma);
     			$teatroHora = str_replace("_", " ", $horanospace[0]);
     			$teatroFecha[$t] = $day . ", " .$dayNum . " de " . $month . " | " . $teatroHora ."HS.";
				//echo($cineID[$c]);
    			$teatroLugar[$t] = $usersInfo[0]['lugar'];
    			$teatroPrecio[$t] = $usersInfo[0]['precio'];
    			$t++;
    			break;
    	}
    		$p++;
    	}

    	$currentUrl = $_SERVER['REQUEST_URI'];


    	//Parametros en la URL permitidos
    	$getsArray = array("precio","categoria[]","departamento[]","tiempo","preciomin","preciomax","pag","creacion");

    	//Parseo la URL para obtener solo los parametros en un string, queda un string como por ejemplo: tiempo=ASC&departamento[]=canelones&categoria[]=cine
    	$parsedURL = parse_url($currentUrl, PHP_URL_QUERY);

    	//Si no está vacio, continuo con el filtreo del link
    	if(!empty($parsedURL)){
    		//Se reemplaza todos los caracteres que se encuentren entre un caracter de tipo = y & para ir poco a poco obteniendo solo las palabras pasadas como parametros. Con el string del ejemplo anterior quedaria: tiempo departamento[] categoria[]=cine
    		//Como se puede ver, en el ultimo parametro sigue quedando un =cine
	    	$parsedURL = preg_replace('/=[\s\S]+?&/', ' ', $parsedURL);
	    	//Finalmente, se reemplaza todo lo que esté a la derecha del caracter = por lo visto anteriormente. Quedaría: tiempo departamento[] categoria[]
	    	$parsedURL = substr_replace($parsedURL, '', strpos($parsedURL, '='));
	    	//Por último, por cada espacio que haya, se le da un valor por numero de array, entonces quedaria: $parsedURL[0]="tiempo", $parsedURL[1]="departamento[]"" y etc
	    	$parsedURL = explode(' ', $parsedURL);
	    	//Por cada valor en el array, se checkea si esa palabra existe en el arreglo creado al principio que verificaba que parametros son los permitidos en la URL, si esa palabra no está en el array, se manda al usuario a la página predeterminada de verEventos.php ya que muy seguramente haya querido cambiar manualmente la URL por algo.
	    	for($x=0;$x<count($parsedURL);$x++){
	    		if(!in_array($parsedURL[$x], $getsArray)){
	    			header("Location:verEventos.php");
	    		}
	    	}
    	}

 ?>
<html lang="es">
<head >
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
	<link href="https://api.fontshare.com/v2/css?f[]=cabinet-grotesk@500&display=swap" rel="stylesheet">
	<link href="https://api.fontshare.com/v2/css?f[]=supreme@400&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="includes/css/style_vereventos.css">
	<link rel="stylesheet" type="text/css" href="includes/css/style_footer.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Página para ver todos los eventos culturales en Uruguay. En esta se muestran las categorias mas populares para poder visitar, algunos eventos que son los mas cercanos por ocurrir. Se puede filtrar por varios filtros, y buscar tu evento favorito.">
    <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png" sizes="16x16" />

	<title>Ver Eventos | QueHayHoy?</title>
</head>
<body id="bodyID">
<nav class = "navbar navbar-expand-lg fixed-top navbar_size navbar_scrolled border-3 border-bottom border-dark" id = "nav_id">
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

	<div class="container-fluid mt-3">
		<div class="row">
			<div class="col-12">
				<h1 class="text-center display-1">Buscar eventos</h1>
				<h3 class="text-center display-6">Encuentra y filtra por lo que quieras hacer</h3>
				<div class="inCenter mt-3">
					<input type="text" class="form-control live_search w-50" id="live_search" autocomplete="off" placeholder="Buscar..." onkeyup="">
				</div>
				<div class="inCenter mt-3">
					<a href="evento.php?id=<?php echo(rand(1,$totalusers_Allevents)); ?>" class="btn text-center justify-content-center randomBtn"><i class="bi bi-shuffle"></i> ¡Ir a un evento aleatorio!</a>	
				</div>
			</div>
		</div>
	</div>

<div class="container-fluid mt-3" style="background-color: #EDEDED;">
	<div class="row no-gutters justify-content-evenly">
	    <button class="btn btn-primary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtros_menu" aria-expanded="false" aria-controls="filtros_menu">
    		<i class="bi bi-filter text-light fst-italic"> Filtros</i>
  		</button>
		<div class="col-lg-2 col-md-2 col-sm-12 col-12 mt-3 collapse" id="filtros_menu">
			<h6><a href="verEventos.php?pag=1&tiempo=ASC" class="mt-3 linksFiltros">Resetear filtros</a></h6>
			<div class="text-dark linksFiltros">
					<h4 class="text_index mt-5">¿Cuándo?</h4>
					<h6><a class="linksFiltros text_subtitle" href="<?php 
					if(isset($_GET['precio'])){
						$urlPrecio = str_replace("precio=".$_GET['precio'], "tiempo=ASC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['tiempo'])){
						$urlPrecio = str_replace("tiempo=".$_GET['tiempo'], "tiempo=ASC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['creacion'])){
						$urlPrecio = str_replace("creacion=".$_GET['creacion'], "tiempo=ASC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else{
						$urlPrecio = $currentUrl."&tiempo=ASC";
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					 ?>">Mostrar los eventos más cercanos en tiempo.</a></h6>
					<h6><a class="linksFiltros text_subtitle" href="<?php 
					if(isset($_GET['precio'])){
						$urlPrecio = str_replace("precio=".$_GET['precio'], "tiempo=DESC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['tiempo'])){
						$urlPrecio = str_replace("tiempo=".$_GET['tiempo'], "tiempo=DESC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['creacion'])){
						$urlPrecio = str_replace("creacion=".$_GET['creacion'], "tiempo=DESC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else{
						$urlPrecio = $currentUrl."&tiempo=DESC";
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					 ?>">Mostrar los eventos más lejanos en tiempo.</a></h6>
					<h4 class="text_index mt-5">¿Dónde?</h4>
					<div class="generos_cine" id = "generos_div">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="artigasID" value="Artigas" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="artigasID">Artigas</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="canelonesID" value="Canelones" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="canelonesID">Canelones</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="cerroLargoID" value="Cerro Largo" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="cerroLargoID">Cerro Largo</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="coloniaID" value="Colonia" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="coloniaID">Colonia</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="duraznoID" value="Durazno" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="duraznoID">Durazno</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="floresID" value="Flores" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="floresID">Flores</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="floridaID" value="Florida" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="floridaID">Florida</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="lavallejaID" value="Lavalleja" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="lavallejaID">Lavalleja</label>
            </div>
                        <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="maldonadoID" value="Maldonado" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="maldonadoID">Maldonado</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="montevideoID" value="Montevideo" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="montevideoID">Montevideo</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="paysanduID" value="Paysandú" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="paysanduID">Paysandú</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="rioNegroID" value="Río Negro" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="rioNegroID">Río Negro</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="riveraID" value="Rivera" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="riveraID">Rivera</label>
            </div>
            <div class="form-check form-check-inline position-relative">
              <input class="form-check-input" type="checkbox" id="rochaID" value="Rocha" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="rochaID">Rocha</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="saltoID" value="Salto" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="saltoID">Salto</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="sanJoseID" value="San José" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="sanJoseID">San José</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="sorianoID" value="Soriano" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="sorianoID">Soriano</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="tacuaremboID" value="Tacuarembó" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="tacuaremboID">Tacuarembó</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="treintayTresID" value="Treinta y Tres" name="generos[]" onchange="change_checkbox(this)">
              <label class="form-check-label" for="treintayTresID">Treinta y Tres</label>
            </div>
          </div>
					<h4 class="text_index mt-5">Categoría</h4>
					<div class="categorias" id = "categoria_div">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="cineID" value="Cine" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="cineID">Cine</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="deportesID" value="Deportes" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="deportesID">Deportes</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="teatroID" value="Teatro" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="teatroID">Teatro</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="musicaID" value="Música" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="musicaID">Música</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="gastronomiaID" value="Gastronomía" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="gastronomiaID">Gastronomía</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="danzaID" value="Danza" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="danzaID">Danza</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="carnavalID" value="Carnaval" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="carnavalID">Carnaval</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="cursosID" value="Cursos" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="cursosID">Cursos</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="nocheID" value="Noche" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="nocheID">Noche</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="feriasID" value="Ferias" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="feriasID">Ferias</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="encuentrosID" value="Encuentros" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="encuentrosID">Encuentros</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="espaciosPublicosID" value="Espacios Públicos" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="espaciosPublicosID">Espacios Públicos</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="exposicionesID" value="Exposiciones" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="exposicionesID">Exposiciones</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="museosID" value="Museos" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="museosID">Museos</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="otroID" value="Otro" name="categorias[]" onchange="change_checkboxCategorias(this)">
              <label class="form-check-label" for="otroID">Otro</label>
            </div>
          </div>
					<h4 class="text_index mt-5">Precio</h4>
					<h6 class="text_subtitle"><a class=" linksFiltros" href="<?php 
					if(isset($_GET['tiempo'])){
						$urlPrecio = str_replace("tiempo=".$_GET['tiempo'], "precio=gratis", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['precio'])){
						$urlPrecio = str_replace("precio=".$_GET['precio'], "precio=gratis", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['creacion'])){
						$urlPrecio = str_replace("creacion=".$_GET['creacion'], "precio=gratis", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else{
						$urlPrecio = $currentUrl."&precio=gratis";
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					 ?>">Eventos gratis.</a></h6>
					<h6 class="text_subtitle linksFiltros"><a class="linksFiltros" href="<?php 
					if(isset($_GET['tiempo'])){
						$urlPrecio = str_replace("tiempo=".$_GET['tiempo'], "precio=ASC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['precio'])){
						$urlPrecio = str_replace("precio=".$_GET['precio'], "precio=ASC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['creacion'])){
						$urlPrecio = str_replace("creacion=".$_GET['creacion'], "precio=ASC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else{
						$urlPrecio = $currentUrl."&precio=ASC";
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					
					 ?>">De menor a mayor.</a></h6>
					<h6 class="text_subtitle"><a class="linksFiltros" href="<?php 
					if(isset($_GET['tiempo'])){
						$urlPrecio = str_replace("tiempo=".$_GET['tiempo'], "precio=DESC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['precio'])){
						$urlPrecio = str_replace("precio=".$_GET['precio'], "precio=DESC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['creacion'])){
						$urlPrecio = str_replace("creacion=".$_GET['creacion'], "precio=DESC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else{
						$urlPrecio = $currentUrl."&precio=DESC";
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					 ?>">De mayor a menor.</a></h6>
					<input type="text" class="form-control mt-3" value="<?php 
					if(isset($_GET['preciomin'])){
						echo($_GET['preciomin']);
					}	
					 ?>" placeholder = "<?php 
					 if(!isset($_GET['preciomin'])){
						echo('Precio mínimo ($UYU)');
						}
					  ?>" aria-label="Precio Mínimo" aria-describedby="basic-addon1" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" inputmode="numeric" pattern="[0-9]*"name = "precioMin" id = "precioMin">
					
					
					<input type="text" class="form-control" value = "<?php 
					if(isset($_GET['preciomax'])){
						echo($_GET['preciomax']);
					}	
					 ?>" placeholder="<?php 
					if(isset($_GET['preciomax'])){
						echo($_GET['preciomax']);
					}
					else{
						echo('Precio máximo ($UYU)');
					}	
					 ?>" aria-label="Precio Máximo" aria-describedby="basic-addon1" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" inputmode="numeric" pattern="[0-9]*"name = "precioMax" id = "precioMax">

					<a class="linksFiltros" href="<?php 
					$stringMin ="";
					$stringMax="";
					if(isset($_GET['preciomin'])){
						$stringMin = "&preciomin=".$_GET['preciomin'];
					}
					if(isset($_GET['preciomax'])){
						$stringMax = "&preciomax=".$_GET['preciomax'];
					}
					
					$currentUrlPrice = str_replace($stringMin, "",$currentUrl);
					$currentUrlPrice = str_replace($stringMax, "",$currentUrlPrice);	
					if(isset($_GET['precio']) && $_GET['precio'] == "gratis"){
						$currentUrlPrice = str_replace("&precio=gratis", "",$currentUrl);
					}
					echo($currentUrl);
					 ?>" id="precioBut">Confirmar</a>

					 <h4 class="text_index mt-5">Fecha de creación</h4>
					<h6 class="text_subtitle"><a class=" linksFiltros" href="<?php 
					if(isset($_GET['tiempo'])){
						$urlPrecio = str_replace("tiempo=".$_GET['tiempo'], "creacion=DESC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['precio'])){
						$urlPrecio = str_replace("precio=".$_GET['precio'], "creacion=DESC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['creacion'])){
						$urlPrecio = str_replace("creacion=".$_GET['creacion'], "creacion=DESC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else{
						$urlPrecio = $currentUrl."&creacion=ASC";
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					 ?>">Más recientes.</a></h6>

					 <h6 class="text_subtitle"><a class=" linksFiltros" href="<?php 
					if(isset($_GET['tiempo'])){
						$urlPrecio = str_replace("tiempo=".$_GET['tiempo'], "creacion=ASC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['precio'])){
						$urlPrecio = str_replace("precio=".$_GET['precio'], "creacion=ASC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else if(isset($_GET['creacion'])){
						$urlPrecio = str_replace("creacion=".$_GET['creacion'], "creacion=ASC", $currentUrl);
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					else{
						$urlPrecio = $currentUrl."&creacion=DESC";
						$urlPrecio = str_replace("pag=".$pagActive, "pag=1", $urlPrecio);
						echo($urlPrecio);
					}
					 ?>">Más antiguos.</a></h6>
			</div>
		</div>
		<div class="col-12 col-lg-10 col-md-10" id="searchResult">
			<!-- Empieza publicacion -->
			<?php 
				if(($totalusers_Allevents/$pagActive) > 25){
					for($i = ($pagActive-1)*25; $i < 25*$pagActive; $i++){
					echo('
						<div class = "container-fluid my-3">
						<div class = "row no-gutters bgPost border-4 border-dark">
							<div class="col-12 text-center bg_'.str_replace(' ', '_', $eventsCategoria[$i]));echo(' border-4 border-dark border-bottom" style="border-top-left-radius: 0.6rem; border-top-right-radius: 0.6rem; height:3.4em;">
							<h1 class="lineHeightCat" style="font-size: 2.1em;">');echo($eventsCategoria[$i]);echo('</h1>
								
							</div>
							<div class="container">
								<div class="row inCenter" id="rowEvent">
									<div class="col-md-2 col-12 p-0 m-0 position-relative" >
										<img src="includes/uploaded/portadas/'); echo($eventsPortada[$i]); echo('" class="heightImgEvent" style=" width: 100%; height:10px;object-position: center; object-fit: cover; border-bottom-left-radius: 0.6rem;" alt="Portada del evento '.$eventsTitulo[$i]);echo('">
										<a href="evento.php?id=');echo($eventsID[$i]);echo('" class="stretched-link"></a>	
									</div>
									<div class="col-12 col-md-10 position-relative heightInfoEvent">
										<h2 class="text-dark mt-2 mt-md-0 align-self-top">'); echo($eventsTitulo[$i]);echo('</h2>
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
				else if($totalusers_Allevents == 0){
					echo('

						<h2 class = "inCenter mt-5">No hay eventos con estos filtros.</h2>
						'
					);
				}
				else{
					for($i = ($pagActive-1)*25; $i < $totalusers_Allevents; $i++){
					echo('
						<div class = "container-fluid my-3">
						<div class = "row no-gutters bgPost border-4 border-dark ">
							<div class="col-12 text-center bg_'.str_replace(' ', '_', $eventsCategoria[$i]));echo(' border-4 border-dark border-bottom" style="border-top-left-radius: 0.6rem; border-top-right-radius: 0.6rem; height:3.4em;">
								<h1 class="lineHeightCat" style="font-size: 2.1em;">');echo($eventsCategoria[$i]);echo('</h1>
							</div>
							<div class="container">
								<div class="row inCenter" id="rowEvent">
									<div class="col-md-2 col-12 p-0 m-0 position-relative" >
										<img src="includes/uploaded/portadas/'); echo($eventsPortada[$i]); echo('" class="heightImgEvent" style=" width: 100%; height:10px;object-position: center; object-fit: cover; border-bottom-left-radius: 0.6rem;" alt="Portada del evento '.$eventsTitulo[$i]);echo('">
										<a href="evento.php?id=');echo($eventsID[$i]);echo('" class="stretched-link"></a>	
									</div>
									<div class="col-12 col-md-10 position-relative heightInfoEvent">
										<h2 class="text-dark mt-2 mt-md-0 align-self-top">'); echo($eventsTitulo[$i]);echo('</h2>
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
			

			<!-- <div class = "container-fluid">
				<div class = "row no-gutters bgPost border-4 border-dark">
					<div class="col-12 bg-danger p-0 m-0 position-relative">
						<h1 class="text-center">Cine</h1>
					</div>
					<div class="col-lg-2 p-0 m-0 position-relative">
						<img src="includes/uploaded/portadas/<?php echo($cinePortada[0]); ?>" class="" style="height: 100%; max-width: 100%; object-position: center; object-fit: cover; border-bottom-left-radius: 1rem; border-top-left-radius: 1rem;">
						<a href="evento.php?id=<?php echo($cineID[0]); ?>" class="stretched-link"></a>	
					</div>
					<div class="col-lg-10 position-relative">
						<h2 class="text-dark align-self-top"><?php echo($cineTitulo[0]); ?></h2>
						<div style="overflow: hidden;">
							<h5 class="truncateText"><?php echo($cineDesc[0]); ?></h5>
						</div>
						<h5 class="align-self-bottom"><i class="bi bi-calendar-event"></i> <?php echo($cineFecha[0]); ?></h5>
						<h5 class="align-self-bottom"><i class="bi bi-geo-alt-fill"></i> <?php echo($cineLugar[0]); ?></h6>
						<h5 class="align-self-bottom"><i class="bi bi-coin"></i> $<?php echo($cinePrecio[0]); ?></h6>
						<a href="evento.php?id=<?php echo($cineID[0]); ?>" class="stretched-link"></a>					
					</div>	
					
				</div>
				
			</div>-->
			
			<!-- Termina publicacion -->
		</div>
		<div>
				<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center pagination-lg">
				    <li class="page-item <?php 
				    if(!in_array(($pagActive-1), $PagsRow) || !isset($eventsCategoria[(($pagActive-1)-1)*25])){
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
				    for($i=0;$i<$totalusers_Allevents/25;$i++){
				    	?>
				    	<li class="page-item <?php 
				    	if(!isset($eventsCategoria[(($i+1)-1)*25])){
				    		echo("disabled");
				    	}
				     ?>" id="<?php echo("pag".($i+1)); ?>"><a type="submit" href="<?php 
				     	$newUrlPag = str_replace("pag=".$pagActive,"pag=".($i+1),$currentUrl);
				     	echo($newUrlPag);
				     ?>" class="page-link"><?php echo($i+1); ?></a></li>
				     <?php
				    }?>

				    <li class="page-item <?php 
				    if(!in_array(($pagActive+1), $PagsRow) || !isset($eventsCategoria[(($pagActive+1)-1)*25])){
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


<div class="text-dark text_index mt-5">


	<!--Cine-->
	<div class="mt-2 mb-5 mx-2" id="cine">
		<h1>Cine</h1>

		<div class="carousel slide carousel_cine carousel-light carousel-transition inCenter" data-bs-ride="carousel" id="carouselCine" data-bs-interval="5000">
			<div class="carousel-inner carousel-inner_cine carousel-inner_style">
				<div class="carousel-item active carousel-item_cine">
					<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
						<div class="card h-100">
							<img src="includes/uploaded/portadas/<?php echo($cinePortada[0]); ?>" alt="one" class="img-fluid imgsCard" alt="Portada de <?php echo($cineTitulo[0]); ?>">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel"><?php echo($cineTitulo[0]); ?></h3>
								<h6 class="card-text truncateText_Carousel"><?php echo($cineDesc[0]); ?> </h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> <?php echo($cineFecha[0]); ?></h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> <?php echo($cineLugar[0]); ?></h6>
								<h6 class="card-text"><i class="bi bi-coin"></i> $<?php echo($cinePrecio[0]); ?></h6>
							</div>
							<a href="evento.php?id=<?php echo($cineID[0]); ?>" class="stretched-link"></a>
							</div>
						</div>
					</div>
				</div>
				<?php 
				for($i = 1; $i < $c; $i++){
					echo('
						<div class="carousel-item carousel-item_cine">
					<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
						<div class="card h-100">
							<img src="includes/uploaded/portadas/'); echo($cinePortada[$i]); echo('" alt="one" class="img-fluid imgsCard">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel">');echo($cineTitulo[$i]);echo('</h3>
								<h6 class="card-text truncateText_Carousel">');echo($cineDesc[$i]);echo('</h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> ');echo($cineFecha[$i]);echo('</h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> ');echo($cineLugar[$i]);echo('</h6>
								<h6 class="card-text"><i class="bi bi-coin"></i> $');echo($cinePrecio[$i]);echo('</h6>
							</div>
							<a href="evento.php?id=');echo($cineID[$i]);echo('" class="stretched-link"></a>
							</div>
						</div>
					</div>
				</div>
						');
					}			
				 ?>
			</div>
			<button class="carousel-control-prev buttonCarousel_L" type="button" data-bs-target="#carouselCine" data-bs-slide="prev">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowPrev.png" class="iconArrow"></span>
	    <span class="visually-hidden">Previous</span>
	  </button>
	  <button class="carousel-control-next buttonCarousel_R" type="button" data-bs-target="#carouselCine" data-bs-slide="next">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowNext.png" class="iconArrow"></span>
	    <span class="visually-hidden">Next</span>
	  </button>
		</div>	
	</div>


	<!--Gastronomía-->
	<!--<div class="mt-2 mb-5 mx-2" id="gastronomia">
		<h1>Gastronomía</h1>

		<div class="carousel slide carousel_gastronomia carousel-light carousel-transition inCenter" data-bs-ride="carousel" id="carouselGastronomia" data-bs-interval="5000">
			<div class="carousel-inner carousel-inner_gastronomia">
				<div class="carousel-item active carousel-item_gastronomia">
					<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
						<div class="card h-100">
							<img src="includes/uploaded/portadas/<?php echo($gastronomiaPortada[0]); ?>" alt="one" class="img-fluid imgsCard">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel"><?php echo($gastronomiaTitulo[0]); ?></h3>
								<h6 class="card-text truncateText_Carousel"><?php echo($gastronomiaDesc[0]); ?> </h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> <?php echo($gastronomiaFecha[0]); ?></h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> <?php echo($gastronomiaLugar[0]); ?></h6>
								<h6 class="card-text"><i class="bi bi-coin"></i> $<?php echo($gastronomiaPrecio[0]); ?></h6>
							</div>
							<a href="evento.php?id=<?php echo($gastronomiaID[0]); ?>" class="stretched-link"></a>
							</div>
						</div>
					</div>
				</div>
				<?php 
				for($i = 1; $i < $g; $i++){
					echo('
						<div class="carousel-item carousel-item_gastronomia">
					<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
						<div class="card h-100">
							<img src="includes/uploaded/portadas/'); echo($gastronomiaPortada[$i]); echo('" alt="one" class="img-fluid imgsCard">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel">');echo($gastronomiaTitulo[$i]);echo('</h3>
								<h6 class="card-text truncateText_Carousel">');echo($gastronomiaDesc[$i]);echo('</h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> ');echo($gastronomiaFecha[$i]);echo('</h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> ');echo($gastronomiaLugar[$i]);echo('</h6>
								<h6 class="card-text"><i class="bi bi-coin"></i> $');echo($gastronomiaPrecio[$i]);echo('</h6>
							</div>
							<a href="evento.php?id=');echo($gastronomiaID[$i]);echo('" class="stretched-link"></a>
							</div>
						</div>
					</div>
				</div>
						');
					}			
				 ?>
			</div>
			<button class="carousel-control-prev buttonCarousel_L" type="button" data-bs-target="#carouselGastronomia" data-bs-slide="prev">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowPrev.png" class="iconArrow"></span>
	    <span class="visually-hidden">Previous</span>
	  </button>
	  <button class="carousel-control-next buttonCarousel_R" type="button" data-bs-target="#carouselGastronomia" data-bs-slide="next">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowNext.png" class="iconArrow"></span>
	    <span class="visually-hidden">Next</span>
	  </button>
		</div>	
	</div>-->

	<!--Música-->
	<div class="mt-2 mb-5 mx-2" id="musica">
		<h1>Música</h1>

		<div class="carousel slide carousel_musica carousel-light carousel-transition inCenter" data-bs-ride="carousel" id="carouselMusica" data-bs-interval="5000">
			<div class="carousel-inner carousel-inner_musica">
				<div class="carousel-item active carousel-item_musica">
					<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
						<div class="card h-100">
							<img src="includes/uploaded/portadas/<?php echo($musicaPortada[0]); ?>" alt="one" class="img-fluid imgsCard">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel"><?php echo($musicaTitulo[0]); ?></h3>
								<h6 class="card-text truncateText_Carousel"><?php echo($musicaDesc[0]); ?> </h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> <?php echo($musicaFecha[0]); ?></h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> <?php echo($musicaLugar[0]); ?></h6>
								<h6 class="card-text"><i class="bi bi-coin"></i> $<?php echo($musicaPrecio[0]); ?></h6>
							</div>
							<a href="evento.php?id=<?php echo($musicaID[0]); ?>" class="stretched-link"></a>
							</div>
						</div>
					</div>
				</div>
				<?php 
				for($i = 1; $i < $m; $i++){
					echo('
						<div class="carousel-item carousel-item_musica">
					<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
						<div class="card h-100">
							<img src="includes/uploaded/portadas/'); echo($musicaPortada[$i]); echo('" alt="one" class="img-fluid imgsCard">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel">');echo($musicaTitulo[$i]);echo('</h3>
								<h6 class="card-text truncateText_Carousel">');echo($musicaDesc[$i]);echo('</h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> ');echo($musicaFecha[$i]);echo('</h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> ');echo($musicaLugar[$i]);echo('</h6>
								<h6 class="card-text"><i class="bi bi-coin"></i> $');echo($musicaPrecio[$i]);echo('</h6>
							</div>
							<a href="evento.php?id=');echo($musicaID[$i]);echo('" class="stretched-link"></a>
							</div>
						</div>
					</div>
				</div>
						');
					}			
				 ?>
			</div>
			<button class="carousel-control-prev buttonCarousel_L" type="button" data-bs-target="#carouselMusica" data-bs-slide="prev">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowPrev.png" class="iconArrow"></span>
	    <span class="visually-hidden">Previous</span>
	  </button>
	  <button class="carousel-control-next buttonCarousel_R" type="button" data-bs-target="#carouselMusica" data-bs-slide="next">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowNext.png" class="iconArrow"></span>
	    <span class="visually-hidden">Next</span>
	  </button>
		</div>	
	</div>

	<!--Teatro-->
	<div class="mt-2 mb-5 mx-2" id="teatro">
		<h1>Teatro</h1>

		<div class="carousel slide carousel_teatro carousel-light carousel-transition inCenter" data-bs-ride="carousel" id="carouselTeatro" data-bs-interval="5000">
			<div class="carousel-inner carousel-inner_teatro">
				<div class="carousel-item active carousel-item_teatro">
					<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
						<div class="card h-100">
							<img src="includes/uploaded/portadas/<?php echo($teatroPortada[0]); ?>" alt="one" class="img-fluid imgsCard">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel"><?php echo($teatroTitulo[0]); ?></h3>
								<h6 class="card-text truncateText_Carousel"><?php echo($teatroDesc[0]); ?> </h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> <?php echo($teatroFecha[0]); ?></h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> <?php echo($teatroLugar[0]); ?></h6>
								<h6 class="card-text"><i class="bi bi-coin"></i> $<?php echo($teatroPrecio[0]); ?></h6>
							</div>
							<a href="evento.php?id=<?php echo($teatroID[0]); ?>" class="stretched-link"></a>
							</div>
						</div>
					</div>
				</div>
				<?php 
				for($i = 1; $i < $t; $i++){
					echo('
						<div class="carousel-item carousel-item_teatro">
					<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
						<div class="card h-100">
							<img src="includes/uploaded/portadas/'); echo($teatroPortada[$i]); echo('" alt="one" class="img-fluid imgsCard">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel">');echo($teatroTitulo[$i]);echo('</h3>
								<h6 class="card-text truncateText_Carousel">');echo($teatroDesc[$i]);echo('</h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> ');echo($teatroFecha[$i]);echo('</h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> ');echo($teatroLugar[$i]);echo('</h6>
								<h6 class="card-text"><i class="bi bi-coin"></i> $');echo($teatroPrecio[$i]);echo('</h6>
							</div>
							<a href="evento.php?id=');echo($teatroID[$i]);echo('" class="stretched-link"></a>
							</div>
						</div>
					</div>
				</div>
						');
					}			
				 ?>
			</div>
			<button class="carousel-control-prev buttonCarousel_L" type="button" data-bs-target="#carouselTeatro" data-bs-slide="prev">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowPrev.png" class="iconArrow"></span>
	    <span class="visually-hidden">Previous</span>
	  </button>
	  <button class="carousel-control-next buttonCarousel_R" type="button" data-bs-target="#carouselTeatro" data-bs-slide="next">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowNext.png" class="iconArrow"></span>
	    <span class="visually-hidden">Next</span>
	  </button>
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


	//Carousel
	let width = screen.width;
	let slideEvents = 4;

	if(width >= 768 && width < 1400){
			slideEvents = 4;
		}
		else if(width >= 1400){
			slideEvents = 6;
		}
		else{
			slideEvents = 1;
			document.getElementById('carouselCine').classList.remove('slide');
			document.getElementById('carouselMusica').classList.remove('slide');
			document.getElementById('carouselTeatro').classList.remove('slide');
		}
	
	var items_cine = document.querySelectorAll('.carousel_cine .carousel-item_cine');
	items_cine.forEach((e)=>{
		let next_cine = e.nextElementSibling;
		for(var i =0; i < slideEvents; i++){
			if(!next_cine){
				next_cine = items_cine[0];
			}
			let cloneChild_cine = next_cine.cloneNode(true);
			e.appendChild(cloneChild_cine.children[0]);
			next_cine = next_cine.nextElementSibling;
		}
	})

	//Gastronomia

	var items_gastronomia = document.querySelectorAll('.carousel_gastronomia .carousel-item_gastronomia');
	items_gastronomia.forEach((f)=>{
		let next_gastronomia = f.nextElementSibling;
		for(var i =0; i < slideEvents; i++){
			if(!next_gastronomia){
				next_gastronomia = items_gastronomia[0];
			}
			let cloneChild_gastronomia = next_gastronomia.cloneNode(true);
			f.appendChild(cloneChild_gastronomia.children[0]);
			next_gastronomia = next_gastronomia.nextElementSibling;
		}
	})

	//Musica

	var items_musica = document.querySelectorAll('.carousel_musica .carousel-item_musica');
	items_musica.forEach((g)=>{
		let next_musica = g.nextElementSibling;
		for(var i =0; i < slideEvents; i++){
			if(!next_musica){
				next_musica = items_musica[0];
			}
			let cloneChild_musica = next_musica.cloneNode(true);
			g.appendChild(cloneChild_musica.children[0]);
			next_musica = next_musica.nextElementSibling;
		}
	})

	//Teatro

	var items_teatro = document.querySelectorAll('.carousel_teatro .carousel-item_teatro');
	items_teatro.forEach((g)=>{
		let next_teatro = g.nextElementSibling;
		for(var i =0; i < slideEvents; i++){
			if(!next_teatro){
				next_teatro = items_teatro[0];
			}
			let cloneChild_teatro = next_teatro.cloneNode(true);
			g.appendChild(cloneChild_teatro.children[0]);
			next_teatro = next_teatro.nextElementSibling;
		}
	})

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

	for($i=0;$i<$totalusers_Allevents/25;$i++){
		if($i+1 == $pagActive){
			echo("document.getElementById('pag".($i+1)."').classList.add('active');");
		}
		else{
			echo("document.getElementById('pag".($i+1)."').classList.remove('active');");
		}
	}
	 ?>


var inputPMin = document.getElementById('precioMin');
var inputPMax = document.getElementById('precioMax');

var valMin = document.getElementById('precioMin').value;
var valMax = document.getElementById('precioMax').value;


<?php 
echo("var urlPrecioBut = ".'"'.$currentUrlPrice.'";');
echo("var currentUrl = ".'"'.$currentUrl.'";');
 ?>

 //console.log(urlPrecioBut);
 //console.log(currentUrl);

inputPMin.onkeyup = function(){
   valMin = document.getElementById('precioMin').value;
   if(valMax && valMin){
   	document.getElementById('precioBut').href = urlPrecioBut+"&preciomin="+valMin+"&preciomax="+valMax;
   }
   else if(valMin && !valMax){
   	document.getElementById('precioBut').href = urlPrecioBut+"&preciomin="+valMin;
   }
   else if(valMax && !valMin){
   	document.getElementById('precioBut').href = urlPrecioBut+"&preciomax="+valMax;
   }
   else{
   	document.getElementById('precioBut').href = urlPrecioBut;
   }
  
}

inputPMax.onkeyup = function(){
   valMax = document.getElementById('precioMax').value;
   //console.log(valMin);
   if(valMax && valMin){
   	document.getElementById('precioBut').href = urlPrecioBut+"&preciomin="+valMin+"&preciomax="+valMax;
   }
   else if(valMax && !valMin){
   	document.getElementById('precioBut').href = urlPrecioBut+"&preciomax="+valMax;
   }
   else if(valMin && !valMax){
   	document.getElementById('precioBut').href = urlPrecioBut+"&preciomin="+valMin;
   }
   else{
   	document.getElementById('precioBut').href = urlPrecioBut;
   }
  
}

  var categoriasArray = ["cine", "deportes","teatro","musica","gastronomia","danza","carnaval","cursos","noche","ferias","encuentros","espaciosPublicos","exposiciones","museos","otro"];

  for(var c = 0; c < 14; c++){
  	if(currentUrl.includes('&categoria[]='+categoriasArray[c])){
  		document.getElementById(categoriasArray[c]+'ID').checked = true;
  	}
  }


function change_checkboxCategorias(cat){
	if(cat.checked){
		switch(cat.value){
		case "Cine":
			location.href = currentUrl+"&categoria[]=cine";
			break;
			case "Deportes":
			location.href = currentUrl+"&categoria[]=deportes";
			break;
			case "Teatro":
			location.href = currentUrl+"&categoria[]=teatro";
			break;
			case "Música":
			location.href = currentUrl+"&categoria[]=musica";
			break;
			case "Gastronomía":
			location.href = currentUrl+"&categoria[]=gastronomia";
			break;
			case "Danza":
			location.href = currentUrl+"&categoria[]=danza";
			break;
			case "Carnaval":
			location.href = currentUrl+"&categoria[]=carnaval";
			break;
			case "Cursos":
			location.href = currentUrl+"&categoria[]=cursos";
			break;
			case "Noche":
			location.href = currentUrl+"&categoria[]=noche";
			break;
			case "Ferias":
			location.href = currentUrl+"&categoria[]=ferias";
			break;
			case "Encuentros":
			location.href = currentUrl+"&categoria[]=encuentros";
			break;
			case "Espacios Públicos":
			location.href = currentUrl+"&categoria[]=espaciosPublicos";
			break;
			case "Exposiciones":
			location.href = currentUrl+"&categoria[]=exposiciones";
			break;
			case "Museos":
			location.href = currentUrl+"&categoria[]=museos";
			break;
			case "Otro":
			location.href = currentUrl+"&categoria[]=otro";
			break;
		}
	}
	else{
		switch(cat.value){
		case "Cine":
			location.href = currentUrl.replace('&categoria[]=cine','');
			break;
			case "Deportes":
			location.href = currentUrl.replace('&categoria[]=deportes','');
			break;
			case "Teatro":
			location.href = currentUrl.replace('&categoria[]=teatro','');
			break;
			case "Música":
			location.href = currentUrl.replace('&categoria[]=musica','');
			break;
			case "Gastronomía":
			location.href = currentUrl.replace('&categoria[]=gastronomia','');
			break;
			case "Danza":
			location.href = currentUrl.replace('&categoria[]=danza','');
			break;
			case "Carnaval":
			location.href = currentUrl.replace('&categoria[]=carnaval','');
			break;
			case "Cursos":
			location.href = currentUrl.replace('&categoria[]=cursos','');
			break;
			case "Noche":
			location.href = currentUrl.replace('&categoria[]=noche','');
			break;
			case "Ferias":
			location.href = currentUrl.replace('&categoria[]=ferias','');
			break;
			case "Encuentros":
			location.href = currentUrl.replace('&categoria[]=encuentros','');
			break;
			case "Espacios Públicos":
			location.href = currentUrl.replace('&categoria[]=espaciosPublicos','');
			break;
			case "Exposiciones":
			location.href = currentUrl.replace('&categoria[]=exposiciones','');
			break;
			case "Museos":
			location.href = currentUrl.replace('&categoria[]=museos','');
			break;
			case "Otro":
			location.href = currentUrl.replace('&categoria[]=otro','');
			break;
		}
	}
}

  var departamentoArray = ["artigas", "canelones","cerroLargo","colonia","durazno","flores","florida","lavalleja","maldonado","montevideo","paysandu","rioNegro","rivera","rocha","salto","sanJose","soriano","tacuarembo","treintayTres"];

  for(var x = 0; x < 19; x++){
  	if(currentUrl.includes('&departamento[]='+departamentoArray[x])){
  		document.getElementById(departamentoArray[x]+'ID').checked = true;
  	}
  }

  function change_checkbox(el){
  if(el.checked){
  	switch(el.value){
  	case "Artigas":
  		location.href = currentUrl+"&departamento[]=artigas";
  		break;
  	case "Canelones":
  		location.href = currentUrl+"&departamento[]=canelones";
  		break;
  		case "Cerro Largo":
  		location.href = currentUrl+"&departamento[]=cerroLargo";
  		break;
  		case "Colonia":
  		location.href = currentUrl+"&departamento[]=colonia";
  		break;
  		case "Durazno":
  		location.href = currentUrl+"&departamento[]=durazno";
  		break;
  		case "Flores":
  		location.href = currentUrl+"&departamento[]=flores";
  		break;
  		case "Florida":
  		location.href = currentUrl+"&departamento[]=florida";
  		break;
  		case "Lavalleja":
  		location.href = currentUrl+"&departamento[]=lavalleja";
  		break;
  		case "Maldonado":
  		location.href = currentUrl+"&departamento[]=maldonado";
  		break;
  		case "Montevideo":
  		location.href = currentUrl+"&departamento[]=montevideo";
  		break;
  		case "Paysandú":
  		location.href = currentUrl+"&departamento[]=paysandu";
  		break;
  		case "Río Negro":
  		location.href = currentUrl+"&departamento[]=rioNegro";
  		break;
  		case "Rivera":
  		location.href = currentUrl+"&departamento[]=rivera";
  		break;
  		case "Rocha":
  		location.href = currentUrl+"&departamento[]=rocha";
  		break;
  		case "Salto":
  		location.href = currentUrl+"&departamento[]=salto";
  		break;
  		case "San José":
  		location.href = currentUrl+"&departamento[]=sanJose";
  		break;
  		case "Soriano":
  		location.href = currentUrl+"&departamento[]=soriano";
  		break;
  		case "Tacuarembó":
  		location.href = currentUrl+"&departamento[]=tacuarembo";
  		break;
  		case "Treinta y Tres":
  		location.href = currentUrl+"&departamento[]=treintayTres";
  		break;
  	}
  }else{
  	switch(el.value){
  	case "Artigas":
  		location.href = currentUrl.replace('&departamento[]=artigas','');
  		break;
  	case "Canelones":
  		location.href = currentUrl.replace('&departamento[]=canelones','');
  		break;
  		case "Cerro Largo":
  		location.href = currentUrl.replace('&departamento[]=cerroLargo','');
  		break;
  		case "Colonia":
  		location.href = currentUrl.replace('&departamento[]=colonia','');
  		break;
  		case "Durazno":
  		location.href = currentUrl.replace('&departamento[]=durazno','');
  		break;
  		case "Flores":
  		location.href = currentUrl.replace('&departamento[]=flores','');
  		break;
  		case "Florida":
  		location.href = currentUrl.replace('&departamento[]=florida','');
  		break;
  		case "Lavalleja":
  		location.href = currentUrl.replace('&departamento[]=lavalleja','');
  		break;
  		case "Maldonado":
  		location.href = currentUrl.replace('&departamento[]=maldonado','');
  		break;
  		case "Montevideo":
  		location.href = currentUrl.replace('&departamento[]=montevideo','');
  		break;
  		case "Paysandú":
  		location.href = currentUrl.replace('&departamento[]=paysandu','');
  		break;
  		case "Río Negro":
  		location.href = currentUrl.replace('&departamento[]=rioNegro','');
  		break;
  		case "Rivera":
  		location.href = currentUrl.replace('&departamento[]=rivera','');
  		break;
  		case "Rocha":
  		location.href = currentUrl.replace('&departamento[]=rocha','');
  		break;
  		case "Salto":
  		location.href = currentUrl.replace('&departamento[]=salto','');
  		break;
  		case "San José":
  		location.href = currentUrl.replace('&departamento[]=sanJose','');
  		break;
  		case "Soriano":
  		location.href = currentUrl.replace('&departamento[]=soriano','');
  		break;
  		case "Tacuarembó":
  		location.href = currentUrl.replace('&departamento[]=tacuarembo','');
  		break;
  		case "Treinta y Tres":
  		location.href = currentUrl.replace('&departamento[]=treintayTres','');
  		break;
  	}
  }
}


//Live Search

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("live_search").addEventListener("keyup", function (e) {
  		var inputText = e.target.value; // Get the text typed by user
  		//console.log(inputText);
  		//alert(inputText); // log the input text out
  		if(inputText !== null){
  			var request = new XMLHttpRequest();
  			//request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

			request.onreadystatechange = function() {	//Call a function when the state changes.
			    if(request.readyState == 4 && request.status == 200) {
			        //alert(request.responseText);
			        document.getElementById("searchResult").innerHTML=request.responseText;
			        //Imagen e informacion de los eventos de la misma altura

					var items_showEvents = document.querySelectorAll('.heightImgEvent'); //Selecciono todos los divs con la clase heightImgEvent
					items_showEvents.forEach((e)=>{ //Voy por cada uno
						var f = e.parentElement.nextElementSibling; //selecciono el div que contiene la informacion (el hermano del padre de la iamgen)
							var styleHeight = getComputedStyle(f).height; //Guardo la altura de ese div
							//console.log('style Height : ', styleHeight);
							e.style.height = styleHeight; //Igualo la altura del texto con el de la imagen
					})

			        //alert(inputText);
			    }
			}
  			 request.open("GET","live_search.php?q="+inputText,true);
 			 request.send();
			
			

			//searchResult
  		}
  		else{
  			document.getElementById("searchResult").innerHTML=```
  			<!-- Empieza publicacion -->
			<?php 
				if(($totalusers_Allevents/$pagActive) > 25){
					for($i = ($pagActive-1)*25; $i < 25*$pagActive; $i++){
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
				else if($totalusers_Allevents == 0){
					echo('

						<h2 class = "inCenter mt-5">No hay eventos con estos filtros.</h2>
						'
					);
				}
				else{
					for($i = ($pagActive-1)*25; $i < $totalusers_Allevents; $i++){
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
  			```;
  		}
	});
});

if(width >= 768){
	document.getElementById("filtros_menu").classList.remove("collapse");
}

	
</script>
</body>
</html>