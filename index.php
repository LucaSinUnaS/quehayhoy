<!DOCTYPE html>
<?php require_once 'connection.php';
include 'functions.php';
	$iniciado = false;
	session_start();
	//Fijarse si usuario inició sesión
	if(!isset($_SESSION['user'])){
		$iniciado = false;
	}
	else{
		$iniciado = true;
	}
	//Seleccionar los 10 primeros en orden de mas cercanos en tiempo eventos ya validados para la creacion del carrusel
	//$totalusers = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events")->fetchColumn();
	$infousers_stmt= $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events ORDER BY fecha ASC, hora ASC LIMIT 10");
	$infousers_stmt->execute([]);
    for($k = 0; $k < 10; $k++){
    	
    	$rowusers[$k][] = $infousers_stmt->fetch(PDO::FETCH_ASSOC);
    	//$users_id[$k] = $rowusers[];
    }
    $p = 0;
    $c = 0;
    $eventsTitulo[] = '';
    //Por cada evento que exista, asignar en un array a cada variable necesaria sus datos correspondientes
    foreach($rowusers as $usersInfo){
    		$idusers[$p] = $usersInfo[0]['id'];
    		$nameusers[$p] = $usersInfo[0]['username'];
    		$scoreusers[$p] = $usersInfo[0]['email'];
    		$eventsID[$p] = $usersInfo[0]['id'];
    		$eventsPortada[$p] = $usersInfo[0]['portada'];
    		$eventsTitulo[$p] = $usersInfo[0]['titulo'];
    		$eventsDesc[$p] = $usersInfo[0]['descripcion'];
    		$eventsFechas = $usersInfo[0]['fecha'];
    		$fechanocoma = str_replace(",", "", $eventsFechas);
     		$fechanospace= explode(' ', $fechanocoma);
     		$eventsTimestamp = str_replace("_", " ", $fechanospace[0]);
     		//echo nl2br ("\n");
     		$timestamp = strtotime($eventsTimestamp);
     		$dayNum = date('d', $timestamp);
			$day = GetDay($timestamp);
			$month = GetMonth($timestamp);
			$year = date('Y', $timestamp);
    		$eventsHoras = $usersInfo[0]['hora'];
    		$horanocoma = str_replace(",", "", $eventsHoras);
     		$horanospace= explode(' ', $horanocoma);
     		$eventsHora = str_replace("_", " ", $horanospace[0]);
     		$eventsFecha[$p] = $day . ", " .$dayNum . " de " . $month . " | " . $eventsHora ."HS.";
			//echo($cineID[$c]);
    		$eventsLugar[$p] = $usersInfo[0]['lugar'];
    		$eventsPrecio[$p] = $usersInfo[0]['precio'];
    		$p++;
    }  	

    $currentUrl = $_SERVER['REQUEST_URI'];
    $parsedURL = parse_url($currentUrl, PHP_URL_QUERY);

    if(!empty($parsedURL)){
    	header("Location:index.php");
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
	<meta name="description" content="Página de inicio para la página QueHayHoy?. En esta se muestran las categorias mas populares para poder visitar, algunos eventos que son los mas cercanos por ocurrir y principales noticias relacionados a los eventos realizados en Uruguay.">
    <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png" sizes="16x16" />
	<link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="includes/css/style_index.css">
	<link rel="stylesheet" type="text/css" href="includes/css/style_footer.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

	<title>Inicio | QueHayHoy?</title>
</head>
<body id="bodyID">
	<div class="bgFirstScreen">
<nav class = "navbar navbar-expand-lg fixed-top navbar_size" id = "nav_id">
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

<div class="container-fluid ">
		<div class = "row">
			<div class = "col-12 main_pg d-none d-lg-block">
				<div class="mx-5" style="display: flex;height:100%;">
					<div class="container-fluid h-100">
						<div class="row text-center h-100">

							<div class="col-lg-3 col-md-12 col-sm-12 col-12 h-100">
								<div class="container-fluid  h-100">
									<div class="row text-center h-100 my-2">
										<div class="col-lg-12 col-md-6 col-sm-6 text-center box" style="display:flex; height: 30%;background-color: #E2E2E2; display: flex; align-items:center; justify-content:center;">
											<h2 class="text-center text_index" style="color: #3CA0A1;">¿QUÉ PODEMOS HACER HOY?</h2>
										</div>
										<div class="col-lg-12 col-md-6 col-sm-6 text-center box box_move" style="height: 60%; background-color: #E60012;display: flex; align-items:center; justify-content: center; " >
												
											<a href="crearEvento.php" aria-label="¡AÑADE TU PROPIO EVENTO!" style="height: 100%; display:flex; text-decoration: none;">
												<div style="display: flex; align-items:center; justify-content: center; ">
													<h2 class="text-center text-light text_index justify-content-center">¡AÑADE TU PROPIO EVENTO!</h2>
												</div>
											</a>
											
										</div>
										
											
									</div>
								</div>
							</div>

							<div class="col-lg-3 col-md-12 col-sm-12 h-100">
								<div class="container-fluid  h-100">
									<div class="row text-center h-100 my-2">
										<div class="col-lg-12 col-md-6 col-sm-6 text-center box box_move" style="height: 45%; background-color: #B394F1;">
											<a href="verEventos.php?pag=1&tiempo=ASC&categoria[]=cine" aria-label="Ver eventos de cine" style="text-decoration:none;">
												<div>
													<h1 class="text-center text-light text_index mt-2">CINE</h1>
												</div>
												<div class="box_imgs my-auto" style=" height: 70% ; background: no-repeat center; background-image: url('includes/images/cine.webp'); background-size: cover; ">
												</div>	
											</a>
										</div>
										<div class="col-lg-12 col-md-6 col-sm-6 text-center box box_move" style="height: 45%; background-color: #E2E2E2 ;">
											<a href="verEventos.php?pag=1&tiempo=ASC&categoria[]=gastronomia" aria-label="Ver eventos de gastronomía" style="text-decoration:none;">
												<div>
													<h2 class="text-center text_index mt-2" style="color: #B394F1;">GASTRONOMÍA</h2>
												</div>
												<div style=" height: 70% ; background: no-repeat center; background-image: url('includes/images/gastronomia.webp'); background-size: cover;" class="box_imgs">
												</div>	
											</a>
											
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-3 col-md-12 col-sm-12 h-100">
								<div class="container-fluid  h-100">
									<div class="row text-center h-100 my-2">

										<div class="col-lg-12 col-md-6 col-sm-6 text-center box box_move" style="height:45%; background-color: #E2E2E2;">
											<a href="verEventos.php?pag=1&tiempo=ASC&categoria[]=musica" aria-label="Ver eventos de música" style="text-decoration: none;">
												<div>
													<h1 class="text-center text_index mt-2" style="color: #3CA0A1;">MÚSICA</h1>
												</div>
												<div style=" height: 70%; background: no-repeat center; background-image: url('includes/images/musica.webp'); background-size: cover;" class="box_imgs">
												</div>
											</a>
										</div>
										
										
										<div class="col-lg-12 col-md-6 col-sm-6 text-center box box_move" style="height: 45%; background-color: #E60012; ">
											<a href="verEventos.php?pag=1&tiempo=ASC&categoria[]=teatro" aria-label="Ver eventos de teatro" style="text-decoration: none;">
												<div>
													<h1 class="text-center text_index mt-2 text-light">TEATRO</h1>
												</div>
												<div style=" height: 70%; background: no-repeat center; background-image: url('includes/images/teatro.webp'); background-size: cover;" class="box_imgs">
												</div>
											</a>
										</div>

									</div>
								</div>
							</div>

							<div class="col-lg-3 col-md-12 col-sm-12 h-100">
								<div class="container-fluid h-100">
									<div class="row text-center h-100 my-2">
										<div class="col-lg-12 col-md-6 col-sm-6 border text-center box box_move" style="height: 30%; background-color: #3CA0A1;  ">
											<a href="noticias.php" aria-label="Ver noticias" style="text-decoration: none;">
												<div>
													<h1 class="text-center text_index mt-2 text-light">NOTICIAS</h1>
												</div>
												<div style=" height: 50%; background: no-repeat center; background-image: url('includes/images/noticias.webp'); background-size: cover;" class="box_imgs">
												</div>
											</a>
										</div>
										<div class="col-lg-12 col-md-6 col-sm-6 bg-light border text-center box box_move" style="height: 60%; background: no-repeat center/200%; background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('includes/images/carnaval.webp'); background-size: cover; display: flex; align-items:center; justify-content:center;">
											<a href="verEventos.php" aria-label="Ver todos los eventos" style="height: 100%;display:flex; text-decoration: none;">
												<div style="display: flex; align-items:center; justify-content:center;">
													<h1 class="text-center text-light text_index">TENEMOS MÁS OPCIONES</h1>
												</div>
											</a>
											
										</div>
									</div>
								</div>
							</div>	

						</div>
					</div>
				</div>
				
				
			</div>

			<div class="col-12 main_pg d-block d-lg-none">
				<div class="container-fluid h-100">
					<div class="row h-100 ">
						<div class="col-12 h-100 inCenter">
								<div class="container-fluid h-100">
									<div class="row text-center h-100 inCenter justify-content-evenly">
									    
										<div class="col-5 text-center box" style="height:50%;background-color: #E2E2E2; display: flex; align-items:center; justify-content:center;">
												<div style="display: flex; align-items:center; justify-content: center; ">
												    <h2 class="text-center text_index" style="color: #3CA0A1;"><a href="verEventos.php" aria-label="¿QUÉ PODEMOS HACER HOY?" style="text-decoration: none; color: #3CA0A1;">¿QUÉ PODEMOS HACER HOY?</a></h2>
												</div>
										</div>
										<div class="col-5 text-center box box_move" style="height:50%; background-color: #E60012;display: flex; align-items:center; justify-content: center; " >
												
											<a href="crearEvento.php" aria-label="¡AÑADE TU PROPIO EVENTO!" style="text-decoration: none;">
												<div style="display: flex; align-items:center; justify-content: center; ">
													<h2 class="text-center text-light text_index justify-content-center">¡AÑADE TU PROPIO EVENTO!</h2>
												</div>
											</a>
											
										</div>
										
											
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>

	<div class="container-fluid mb-5">
			<div class = "row">
			<div class = "col-12 text-light bgQuiero" style="display:flex; align-items:center; justify-content: center; transition:0.3s;">
				<h4 class="text-dark text-center my-5 fw-bold indexQuiero d-inline-block">Hoy quiero... <span class="fst-italic changingText fadeInOut inCenter d-inline-block" id="changeText" style="transition:0.3s;"> mirar una peli.</span></h4>
			</div>
		</div>
	</div>
		
			<!-- De que es la página -->
	<div class="container-fluid mt-5 text_subtitle">
		<div class="row">
			<div class="col-12 mb-2">
				<h2 class="text_index">Categorías destacadas</h2>
			</div>
			<div class="col-6 col-lg-2 marginCards">
				<div class="card categoriasDest h-100">
					<img src="includes/images/cine.webp"class="img-fluid imgsCard" alt="Imagen de Barbieheimer">
					<div class="card-body">
						<h6 class="card-title truncateText_Carousel text-center">Cine</h6>
					</div>
					<a href="verEventos.php?pag=1&tiempo=ASC&categoria[]=cine" aria-label="Ver eventos de cine" class="stretched-link"></a>
				</div>
			</div>
			<div class="col-6 col-lg-2 marginCards">
				<div class="card categoriasDest h-100">
					<img src="includes/images/gastronomia.webp"class="img-fluid imgsCard" alt="Un restaurante">
					<div class="card-body">
						<h6 class="card-title truncateText_Carousel text-center">Gastronomía</h6>
					</div>
					<a href="verEventos.php?pag=1&tiempo=ASC&categoria[]=gastronomia" aria-label="Ver eventos de gastronomía" class="stretched-link"></a>
				</div>
			</div>
			<div class="col-6 col-lg-2 marginCards">
				<div class="card categoriasDest h-100">
					<img src="includes/images/musica.webp"class="img-fluid imgsCard" alt="Un grupo de música">
					<div class="card-body">
						<h6 class="card-title truncateText_Carousel text-center">Música</h6>
					</div>
					<a href="verEventos.php?pag=1&tiempo=ASC&categoria[]=musica" aria-label="Ver eventos de música" class="stretched-link"></a>
				</div>
			</div>
			<div class="col-6 col-lg-2 marginCards">
				<div class="card categoriasDest h-100">
					<img src="includes/images/teatro.webp"class="img-fluid imgsCard" alt="Un show de teatro">
					<div class="card-body">
						<h6 class="card-title truncateText_Carousel text-center">Teatro</h6>
					</div>
					<a href="verEventos.php?pag=1&tiempo=ASC&categoria[]=teatro" aria-label="Ver eventos de teatro" class="stretched-link"></a>
				</div>
			</div>
			<div class="col-6 col-lg-2 marginCards">
				<div class="card categoriasDest h-100">
					<img src="includes/images/carnaval.webp"class="img-fluid imgsCard" alt="Un grupo de carnaval">
					<div class="card-body">
						<h6 class="card-title truncateText_Carousel text-center">Carnaval</h6>
					</div>
					<a href="verEventos.php?pag=1&tiempo=ASC&categoria[]=carnaval" aria-label="Ver eventos de carnaval" class="stretched-link"></a>
				</div>
			</div>
			<div class="col-6 col-lg-2 marginCards">
				<div class="card categoriasDest h-100">
					<img src="includes/images/futbol.webp"class="img-fluid imgsCard" alt="Un futbolista jugando al fútbol">
					<div class="card-body">
						<h6 class="card-title truncateText_Carousel text-center">Deportes</h6>
					</div>
					<a href="verEventos.php?pag=1&tiempo=ASC&categoria[]=deportes" aria-label="Ver eventos de deporte" class="stretched-link"></a>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid mt-5 text_index">
		<div class="row justify-content-evenly bgBEventoBack p-5 border-3 border-top border-bottom border-dark">
			<div class="col-12 col-lg-5 bgBEvento p-5 marginCards" id="divquiero">
				<h2>Quiero hacer algo</h2>
				<h5 style="margin-bottom: 1.5em;" class="text_subtitle">¡Cientos de actividades para hacer!</h5>
				<a href = "verEventos.php" aria-label="Buscar eventos" class="text-dark text-center border border-dark rounded-pill mt-3 btnBuscarEventos event_id" id = "">Buscar eventos</a>
			</div>
			<div class="col-12 col-lg-5 marginCards p-5 bgCEvento" id="divcrear">
				<h2>Crear un evento</h2>
				<h5 style="margin-bottom: 1.5em;" class="text_subtitle">¡Publica tu actividad a cientas de personas!</h5>
				<a href = "crearEvento.php" aria-label="Crear un evento" class="text-dark text-center border border-dark rounded-pill btnBuscarEventos mt-3 event_id" id = "">Crear un evento</a>
			</div>
		</div>
	</div>


	<div class="mt-5 mb-5 text_index" id="events">
		<h2 class="text_index ms-3">Próximos eventos</h2>

		<div class="carousel carousel_events slide carousel-light carousel-transition inCenter mx-3" data-bs-ride="carousel" id="carouselEvents" data-bs-interval="5000">
			<div class="carousel-inner carousel-inner_events">
				<div class="carousel-item active carousel-item_events">
					<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
						<div class="card card_Carousel h-100">
							<img src="includes/uploaded/portadas/<?php echo($eventsPortada[0]); ?>" alt="one" class="img-fluid imgsCard">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel"><?php echo($eventsTitulo[0]); ?></h3>
								<h6 class="card-text truncateText_Carousel"><?php echo($eventsDesc[0]); ?> </h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> <?php echo($eventsFecha[0]); ?></h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> <?php echo($eventsLugar[0]); ?></h6>
								<h6 class="card-text"><i class="bi bi-coin"></i> $<?php echo($eventsPrecio[0]); ?></h6>
							</div>
							<a href="evento.php?id=<?php echo($eventsID[0]); ?>" class="stretched-link" aria-label="Ir al evento <?php echo($eventsTitulo[0]); ?>"></a>
							</div>
						</div>
					</div>
				</div>
				<?php 
				for($i = 1; $i < $p; $i++){
					echo('
						<div class="carousel-item carousel-item_events">
					<div class="col-xxl-2 col-lg-3 col-md-3 col-sm-12">
						<div class="card card_Carousel h-100">
							<img src="includes/uploaded/portadas/'); echo($eventsPortada[$i]); echo('" alt="one" class="img-fluid imgsCard">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel">');echo($eventsTitulo[$i]);echo('</h3>
								<h6 class="card-text truncateText_Carousel">');echo($eventsDesc[$i]);echo('</h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-calendar-event"></i> ');echo($eventsFecha[$i]);echo('</h6>
								<h6 class="card-text truncateText_Carousel"><i class="bi bi-geo-alt-fill"></i> ');echo($eventsLugar[$i]);echo('</h6>
								<h6 class="card-text"><i class="bi bi-coin"></i> $');echo($eventsPrecio[$i]);echo('</h6>
							</div>
							<a href="evento.php?id=');echo($eventsID[$i]);echo('" class="stretched-link" aria-label="Ir a evento '.$eventsTitulo[$i].'"></a>
							</div>
						</div>
					</div>
				</div>
						');
					}			
				 ?>
			</div>
			<button class="carousel-control-prev buttonCarousel_L" type="button" data-bs-target="#carouselEvents" data-bs-slide="prev" aria-label="Ir al anterior slide">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowPrev.png" class="iconArrow"></span>
	    <span class="visually-hidden">Previous</span>
	  </button>
	  <button class="carousel-control-next buttonCarousel_R" type="button" data-bs-target="#carouselEvents" data-bs-slide="next" aria-label="Ir al siguiente slide">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowNext.png" class="iconArrow"></span>
	    <span class="visually-hidden">Next</span>
	  </button>
		</div>	
	</div>

	<!-- Noticias -->


		<div class="mt-5 mb-5 text_index" id="Noticias">
		<h2 class="text_index ms-3">Noticias</h2>

		<div class="carousel carousel_news slide carousel-light carousel-transition inCenter mx-3" data-bs-ride="carousel" id="carouselNews" data-bs-interval="8000">
			<div class="carousel-inner carousel-inner_news">
				<div class="carousel-item active carousel-item_news">
					<div class="col-12 col-sm-6">
						<div class="card card_Carousel h-100">
							<img src="includes/images/news/newsplaceholder_1.jpg" alt="one" class="img-fluid imgsCard_news">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel">OPS alerta sobre la posibilidad de brotes de sarampión ante la reducción de las coberturas de vacunación</h3>
							</div>
							<a href="noticia.php?id=" class="stretched-link" aria-label="Ir a la noticia"></a>
							</div>
						</div>
					</div>
				</div>
			<div class="carousel-item carousel-item_news">
					<div class="col-12 col-sm-6">
						<div class="card card_Carousel h-100">
							<img src="includes/images/news/newsplaceholder_2.jpg" alt="one" class="img-fluid imgsCard_news">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel">Guerra Ucrania - Rusia: últimas noticias en directo | Zelenski propone la destitución de su ministro de Defensa</h3>
							</div>
							<a href="noticia.php?id=" class="stretched-link" aria-label="Ir a la noticia"></a>
							</div>
						</div>
					</div>
				</div>	

				<div class="carousel-item carousel-item_news">
					<div class="col-12 col-sm-6">
						<div class="card card_Carousel h-100">
							<img src="includes/images/news/newsplaceholder_3.jpg" alt="one" class="img-fluid imgsCard_news">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel">Temblor hoy en México: SSN registra sismo de 4.0 grados en Chiapas</h3>
							</div>
							<a href="noticia.php?id=" class="stretched-link" aria-label="Ir a la noticia"></a>
							</div>
						</div>
					</div>
				</div>	

				<div class="carousel-item carousel-item_news">
					<div class="col-12 col-sm-6">
						<div class="card card_Carousel h-100">
							<img src="includes/images/news/newsplaceholder_4.jpg" alt="one" class="img-fluid imgsCard_news">
						<div class="card-body">
							<div style="overflow: hidden;">
								<h3 class="card-title truncateText_Carousel">Cuatro astronautas regresan a la Tierra en cápsula de SpaceX tras pasar 6 meses en la EEI</h3>
							</div>
							<a href="noticia.php?id=" class="stretched-link" aria-label="Ir a la noticia"></a>
							</div>
						</div>
					</div>
				</div>	
			</div>


			<button class="carousel-control-prev buttonCarousel_L" aria-label="Ir al anterior slide" type="button" data-bs-target="#carouselNews" data-bs-slide="prev">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowPrev.png" class="iconArrow"></span>
	    <span class="visually-hidden">Previous</span>
	  </button>
	  <button class="carousel-control-next buttonCarousel_R" aria-label="Ir al siguiente slide" type="button" data-bs-target="#carouselNews" data-bs-slide="next">
	    <span class="" aria-hidden="true"><img src="includes/images/arrowNext.png" class="iconArrow"></span>
	    <span class="visually-hidden">Next</span>
	  </button>
		</div>	
	</div>
	
		
	<div class="container-fluid">
		<div class="row inCenter bgOlimpiadasBack border-3 border-top border-bottom border-dark p-5">
			<div class="col-10 p-5 bgOlimpiadas" id="divOlimpiadas">
				<h2 class="text_index">Este es un proyecto para las olimpiadas de programación de Ceibal</h2>
					<h5 style="margin-bottom: 1.5em;" class="text_subtitle">¿Quieres saber más sobre nuestra participación, quienes somos nosotros, y nuestro proceso?</h5>
					<a href = "https://www.instagram.com/ztech_uy/" aria-label="Informarse más" target="_blank" rel="noopener noreferrer" class="text-dark text-center border border-dark rounded-pill btnBuscarEventos mt-3 text_index event_id" id = "">Informarse más</a>
			</div>
		</div>
	</div>

		<!-- FAQ -->
		<div class="container-fluid bgNews mt-5 p-5">
			<div class="row">
				<div class="col-12">
					<h1 class="text_index">FAQ</h1>
					<div class="mt-5">
<div class="accordion accordion-flush" id="accordionFlushExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="flush-headingOne">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
        ¿Sobre qué es esta página?
      </button>
    </h2>
    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne">
      <div class="accordion-body">Es una página web diseñada para mostrar a los jóvenes del país las posibilidades que tienen para pasar su tiempo libre, buscando reconectar la juventud uruguaya y revivir la vida social en el país.</div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="flush-headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
        ¿Por qué fue creada?
      </button>
    </h2>
    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo">
      <div class="accordion-body">Actualmente el tiempo de ocio está muy vinculado a lo rápido, conocido y comercializado, en acudir a las mismas actividades o directamente a ninguna, lo que nos desconecta de nuestros pares y nos lleva a un abuso de la teconología, siendo dañino para nuestra salud tanto física como mental.</div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="flush-headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
        ¿Cómo la creamos?
      </button>
    </h2>
    <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree">
      <div class="accordion-body">Usamos diferentes lengaujes de programación para ayudarnos con su creación, concretamente para el frontend: HTML, CSS, JS y para el backend: PHP, MYSQL. Además, usamos Canva para su diseño y Google Forms para investigar.</div>
    </div>
  </div>
</div>
					</div>
				</div>
			</div>
		</div>
<?php 

if($iniciado == false){
	echo('
		<div class="container-fluid mt-5 mb-5">
			<div class="row inCenter">
				<div class="col-10 p-5 bgUnirse" id="divUnirse">
					<h2 class="text_index">¡Únete ahora!</h2>
					<h5 style="margin-bottom: 1.5em;" class="text_subtitle">Con una cuenta puedes crear publicaciones, hacer comentarios, guardar tus publicaciones favoritas y más.</h5>
					<a href = "signup.php" aria-label="Unirse" class="text-dark text-center border border-dark rounded-pill btnBuscarEventos mt-3 text_index event_id" id = "">Unirse</a>
				</div>
			</div>
		</div>
		');
}

 ?>

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
	let width = screen.width;

	if(width < 350){
		if(document.getElementById("divUnirse")){
			document.getElementById("divUnirse").classList.remove('p-5');
  			document.getElementById("divUnirse").classList.add('py-5');
  			document.getElementById("divUnirse").classList.add('text-center');
		}
		
		
  		document.getElementById("divOlimpiadas").classList.remove('p-5');
  		document.getElementById("divOlimpiadas").classList.add('py-5');
  		document.getElementById("divOlimpiadas").classList.add('text-center');
  		document.getElementById("divquiero").classList.remove('p-5');
  		document.getElementById("divquiero").classList.add('py-5');
  		document.getElementById("divquiero").classList.add('text-center');
  		document.getElementById("divcrear").classList.remove('p-5');
  		document.getElementById("divcrear").classList.add('py-5');
  		document.getElementById("divcrear").classList.add('text-center');
	}

	var pageScrolled = false;

	submitButton.addEventListener('click', function handleClick() {
		if (buttonClicked == false) {
  			buttonClicked = true;
  			document.getElementById('nav_id').style.height="100%";
  			document.querySelectorAll('.event_id').forEach(el=>el.style.transition="0");
  			document.querySelectorAll('.event_id').forEach(el=>el.classList.remove('me-5'));
  			document.getElementById("crearEventoID").classList.remove('pe-5');
  			document.getElementById('nav_id').classList.add('navbar_scrolled');
    		document.getElementById('nav_id').style.transition="0.1s";
    		document.getElementById('nav_id').classList.add('border-dark');
    		document.getElementById('nav_id').classList.add('border-bottom');
    		document.getElementById('nav_id').classList.add('border-3');
    		document.getElementById("bodyID").style.height="100%";
  			document.getElementById("bodyID").style.overflowY="hidden";
 		}
 		else{
			buttonClicked = false;
			document.getElementById('nav_id').style.height="15vh";
			document.querySelectorAll('.event_id').forEach(el=>el.classList.add('me-5'));
			document.querySelectorAll('.event_id').forEach(el=>el.style.transition="0.3s");
			document.getElementById("bodyID").style.height="auto";
  			document.getElementById("bodyID").style.overflowY="visible";
			if(pageScrolled == false){
				document.getElementById('nav_id').classList.remove('navbar_scrolled');
    			document.getElementById('nav_id').style.transition="0.1s";
    			document.getElementById('nav_id').classList.remove('border-dark');
    			document.getElementById('nav_id').classList.remove('border-bottom');
    			document.getElementById('nav_id').classList.remove('border-3');
			}
			
 		}
 	
});

    document.addEventListener('scroll', () =>{
    	pageScrolled = true;
    	const navbar = document.getElementById('nav_id');
    	if(window.scrollY == 0 && buttonClicked != true){
    		document.getElementById('nav_id').classList.remove('navbar_scrolled');
    		document.getElementById('nav_id').style.transition="0.3s";
    		document.getElementById('nav_id').classList.remove('border-bottom');
    		document.getElementById('nav_id').classList.remove('border-dark');
    		document.getElementById('nav_id').classList.remove('border-3');
    		pageScrolled = false;
    	}
    	else{
    		document.getElementById('nav_id').classList.add('navbar_scrolled');
    		document.getElementById('nav_id').style.transition="0.1s";
    		document.getElementById('nav_id').classList.add('border-dark');
    		document.getElementById('nav_id').classList.add('border-bottom');
    		document.getElementById('nav_id').classList.add('border-3');
    	}
    });

	var inverval_timer;
	let prevX = 1;
	let x = 0;

	//Time in milliseconds [1 second = 1000 milliseconds ]    
	inverval_timer = setInterval(function() { 
		do{
			x = Math.floor((Math.random() * 16) + 1);
		} while(prevX == x);
    	prevX = x;
    	console.log(x);
    	switch(x) {
  			case 1:
  				 document.getElementById("changeText").textContent=" mirar una peli.";
    		break;
  			case 2:
    			document.getElementById("changeText").textContent=" jugar al fútbol.";
   	 		break;
   	 		case 3:
    			document.getElementById("changeText").textContent=" salir a bailar.";
   	 		break;
   	 		case 4:
    			document.getElementById("changeText").textContent=" ir a un museo.";
   	 		break;
   	 		case 5:
    			document.getElementById("changeText").textContent=" ir a un concierto.";
   	 		break;
   	 		case 6:
    			document.getElementById("changeText").textContent=" armar mi propio evento.";
   	 		break;
   	 		case 7:
    			document.getElementById("changeText").textContent=" jugar al truco.";
   	 		break;
   	 		case 8:
    			document.getElementById("changeText").textContent=" tomar mates en grupo.";
   	 		break;
   	 		case 9:
    			document.getElementById("changeText").textContent=" salir a correr.";
   	 		break;
   	 		case 10:
    			document.getElementById("changeText").textContent=" ir a un tablado.";
   	 		break;
   	 		case 11:
    			document.getElementById("changeText").textContent=" ir a tomar un café.";
   	 		break;
   	 		case 12:
    			document.getElementById("changeText").textContent=" aprender algo nuevo.";
   	 		break;
   	 		case 13:
    			document.getElementById("changeText").textContent=" ir a una feria.";
   	 		break;
   	 		case 14:
    			document.getElementById("changeText").textContent=" ir a una convención.";
   	 		break;
   	 		case 15:
    			document.getElementById("changeText").textContent=" ir a un encuentro.";
   	 		break;
   	 		case 16:
    			document.getElementById("changeText").textContent=" ir a un festival de comida.";
   	 		break;
}
	}, 5000);

	//Carousel
	let slide_Events = 4;
	let slide_News = 2;

	if(width < 992){
		document.querySelectorAll('.marginCards').forEach(el=>el.classList.add('mb-3'));
	}


	var items_events = document.querySelectorAll('.carousel_events .carousel-item_events');
	items_events.forEach((e)=>{
		if(width >= 768 && width < 1400){
			slide_Events = 4;
		}
		else if(width >= 1400){
			slide_Events = 6;
		}
		else{
			slide_Events = 1;
			document.getElementById('carouselEvents').classList.remove('slide');
		}
		let next_events = e.nextElementSibling;
		for(var i =0; i < slide_Events; i++){
			if(!next_events){
				next_events = items_events[0];
			}
			let cloneChild_events = next_events.cloneNode(true);
			e.appendChild(cloneChild_events.children[0]);
			next_events = next_events.nextElementSibling;
		}
	})

	var items_news = document.querySelectorAll('.carousel_news .carousel-item_news');

	items_news.forEach((l)=>{
		if(width >= 768){
			slide_News = 2;
		}
		else{
			slide_News = 1;
			document.getElementById('carouselNews').classList.remove('slide');
		}
		let next_news = l.nextElementSibling;
		for(var i =0; i < slide_News; i++){
			if(!next_news){
				next_news = items_news[0];
			}
			let cloneChild_news = next_news.cloneNode(true);
			l.appendChild(cloneChild_news.children[0]);
			next_news = next_news.nextElementSibling;
		}
	})
	
</script>
</body>
</html>