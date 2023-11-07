<!DOCTYPE html>
<?php 
require_once 'connection.php';
include 'functions.php';
  $iniciado = false;
  session_start();
  if(!isset($_SESSION['user']) || $_SESSION['user']['admin'] != 1){
    $iniciado = false;
    header("location: index.php");
  }
  else{
    $iniciado = true;
  }

  $totalunValEvents = $db->query("SELECT count(*) FROM u956478100_quehayhoy.unvalidated_events")->fetchColumn();

  if($totalunValEvents !=0){
    $infoEvents_stmt=$db->prepare("SELECT * FROM u956478100_quehayhoy.unvalidated_events ORDER BY id ASC");
  $infoEvents_stmt->execute([]);
    for($k = 0; $k < $totalunValEvents; $k++){
      
      $rowevents[$k][] = $infoEvents_stmt->fetch(PDO::FETCH_ASSOC);
      //$users_id[$k] = $rowusers[];
    }
    $p = 0;
    foreach($rowevents as $eventsData){
      $eventid[$p] = $eventsData[0]['id'];
      $eventname[$p] = $eventsData[0]['titulo'];
      $p++;
    }
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
  <link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="includes/css/style_index.css">
  <link rel="stylesheet" type="text/css" href="includes/css/style_footer.css">
  <meta name="description" content="Página sólo permitida para los administradores del sitio. Aquí se puede ver qué eventos necesitan ser verificados para ser subidos finalmente al sitio.">
    <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png" sizes="16x16" />

  <title>Validar Eventos | QuéHayHoy?</title>
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

  <div class="container-fluid mt-5">
    <div class="row">
      <div class="col-12">
        <h1 class="text-center mt-1 mb-5">Validar eventos</h1>
        <table class="table table-striped">
          <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Link</th>
          </tr>
          <?php 
        for ($i=0; $i < $totalunValEvents; $i++) { 
          echo('
            <tr>
            <td>'.$eventid[$i].'</td>
            <td>'.$eventname[$i].'</td>
            <td><a href="validation_event.php?id='.$eventid[$i].'">Link</a></td>
            </tr>
            ');
        }
         ?>
        </table>
        
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
  
</script>
</body>
</html>