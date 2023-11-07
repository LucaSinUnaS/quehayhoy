<!DOCTYPE html>
<?php 

  $iniciado = false;
  require_once 'connection.php'; 
  
  session_start();
  //Checkear si el usuario ha iniciado sesión
  if(!isset($_SESSION['user'])){
    $iniciado = false;
    header("location: login.php");
  }
  else{
    $iniciado = true;
    //Si se ha presionado el boton de guardado, guardar todos los valores con sus variables correspondidas
    if(isset($_REQUEST['buttonGuardado'])){
      $username = $_SESSION['user']['username'];
      $titulo = htmlspecialchars($_REQUEST['titulo']);
      if(empty($titulo)){
        //echo("Se requiere un título.");
        $errorMsg[0][] = "Se requiere un título.";
      }
      if(strlen($titulo) > 100){
        //echo("La descripción debe tener menos de 50 caracteres.");
        $errorMsg[0][] = "El título debe tener menos de 100 caracteres.";
      }
      $descripcion = htmlspecialchars($_REQUEST['descripcion']);
      if(empty($descripcion)){
        //echo("Se requiere una descripción");
        $errorMsg[1][] = "Se requiere una descripción.";
      }
      if(strlen($descripcion) > 2000){
        //echo("La descripción debe tener menos de 200 caracteres.");
        $errorMsg[1][] = "La descripción debe tener menos de 1000 caracteres.";
      }
      $categoriasCheck = array("Cine", "Deportes", "Teatro", "Música", "Gastronomía", "Danza", "Carnaval", "Cursos", "Noche", "Ferias", "Encuentros", "Espacios Públicos", "Exposiciones", "Museos", "Otro");
      $categoria = htmlspecialchars($_REQUEST['categoria']);
      if(!in_array($categoria, $categoriasCheck)){
        //echo("Ha ocurrido un error. Por favor intentelo de nuevo.");
        $errorMsg[2][] = "Ha ocurrido un error. Por favor intentelo de nuevo.";
      }

      $generosCheck = array("Acción", "Aventura", "Ciencia_Ficción", "Comedia", "Documental", "Drama","Fantasía","Musical","Suspense","Terror","Automovilismo","Baloncesto","Béisbol","Boxeo","Ciclismo","Críquet","Fútbol","Fútbol_Americano","Gimnasia_Artística","Golf","Hockey","Natación","Rugby","Tenis","Tenis_de_Mesa","Voleibol","Monólogo","Tragedia","Tragicomedia","Ópera","Blues","Clásica","Country","Cumbia","Electrónica","Folklore","Funk","Hip-Hop","Jazz","Metal","Pop","Punk","R&B","Reggae","Rock","Salsa","Bar","Cafetería","Cervecería","Comida_Rápida","Pizzería","Puesto_Callejero","Restaurante", "Candombe","Comparsas","Corsos_Barriales","Desfiles","Ensayos","Llamadas","Murgas","Tablado", "Académicos", "Artístico","Desarrollo_Personal","Idiomas","Online","Presencial","Profesional", "Agricultura","Artesanía","Automóviles","Comercial","Cultura","de_Libros","Educación","Gastronómica","Moda","Tecnología","Turismo", "Académico","Cultural","Deportivo","Negocios","Religioso","Social","Voluntariado","Bibliotecas","Espacios_Deportivos","Jardines_Botánicos","Miradores","Parques","Paseos","Playas","Áreas_de_Picnic","Animales","Arte","Ciencia","Deportes","Fotografía","Historia","Joyería","Libros","Medio_Ambiente","Antropología","Arqueología","Astronomía","Botánica","Comunicación","Deporte","Juegos","Literatura","Medicina","Música","Transporte","Gastronomía");
      $genero = "";
      
      if(isset($_REQUEST['generos'])){
        $generos = $_REQUEST['generos'];
        foreach($generos as $genero_txt){
          if(in_array($genero_txt, $generosCheck)){
            $genero .= $genero_txt . ", ";
            //echo($genero);
          }
          else{
            //echo("Ha ocurrido un error. Por favor intentelo de nuevo.");
            $errorMsg[3][] = "Ha ocurrido un error. Por favor intentelo de nuevo.";
          }
          
        }
      }
      $i = 0;
      $fecha = "";
      $hora = "";
      
      while(isset($_REQUEST['fecha'.$i])){
        if(empty($_REQUEST['fecha'.$i])){
          $errorMsg[4][] = "Se requiere una fecha.";
        }
        if(empty($_REQUEST['hora'.$i])){
          $errorMsg[5][] = "Se requiere una hora.";
        }
        //echo($_REQUEST['fecha'.$i]);
        $fecha .= $_REQUEST['fecha'.$i] .", ";
        $hora .= $_REQUEST['hora'.$i] . ", ";

        $primera_fecha = $_REQUEST['fecha0'];
        $primera_hora = $_REQUEST['hora0'];
        //echo("Número de fechas: " .($i+1));
        $i++;
      }
      /* Recuperar fechas
      $fechanocoma = str_replace(",", "", $fecha);
      $fechanospace= explode(' ', $fechanocoma);
 
      foreach ($fechanospace as $word) {
        echo $word;
        echo"<br>";
      }*/
      $ubicacion = htmlspecialchars($_REQUEST['ubicacion']);
      if(empty($ubicacion)){
        $errorMsg[6][] = "Se requiere una ubicación.";
      }
      if(strlen($ubicacion) > 300){
        $errorMsg[6][] = "La ubicación debe tener menos de 300 caracteres.";
      }
      $precio = htmlspecialchars($_REQUEST['precio']);
      if(!is_numeric($precio)){
          $errorMsg[7][] = "Se requiere un precio (si es un evento gratuito ponga 0).";        
      }
      if(strlen($precio) > 6){
        $errorMsg[7][] = "El precio máximo permitido es de 999999.";
      }
      $link = strip_tags($_REQUEST['link']);
      if(empty($link)){
        //$errorMsg[8][] = "Se requiere un link de compra.";
      }
      if(strlen($link) > 300){
        $errorMsg[8][] = "El link debe tener menos de 300 caracteres.";
      }
      $nombre = htmlspecialchars($_REQUEST['nombre']);
      if(empty($nombre)){
        $errorMsg[9][] = "Se requiere un nombre.";
      }
      if(strlen($nombre) > 30){
        $errorMsg[9][] = "El nombre debe tener menos de 30 caracteres.";
      }
      /*$mail = filter_var($_REQUEST['mail'], FILTER_VALIDATE_EMAIL);
      if(empty($mail)){
        $errorMsg[10][] = "Se requiere un mail.";
      }
      if(strlen($mail) > 100){
        $errorMsg[10][] = "El mail debe tener menos de 100 caracteres.";
      }*/
      $telefono = htmlspecialchars($_REQUEST['telefono']);
      if(empty($telefono)){
        $errorMsg[11][] = "Se requiere un teléfono.";
      }
      if(strlen($telefono) > 15){
        $errorMsg[11][] = "El teléfono debe tener menos de 15 números.";
      }
      $lugar = htmlspecialchars($_REQUEST['lugar']);
      if(strlen($lugar) > 100){
        $errorMsg[16][] = "El nombre del lugar debe tener menos de 100 caracteres.";
      }
      $departamentosCheck = array("Artigas", "Canelones", "Cerro Largo", "Colonia", "Durazno", "Flores", "Florida", "Lavalleja", "Maldonado", "Montevideo", "Paysandú", "Río Negro", "Rivera", "Salto", "San José", "Soriano","Tacuarembó","Treinta y Tres");
      $departamento = htmlspecialchars($_REQUEST['departamentos']);
      if(!in_array($departamento, $departamentosCheck)){
        //echo("Ha ocurrido un error. Por favor intentelo de nuevo.");
        $errorMsg[17][] = "Ha ocurrido un error. Por favor intentelo de nuevo.";
      }


      
        if($_FILES['portada']['error'] ===4){
            $errorMsg[15][] = "La imagen no existe";
          }
        else{
          if(empty($errorMsg)){
          $fileName = $_FILES['portada']['name'];
          $fileSize = $_FILES['portada']['size'];
          $tmpName = $_FILES['portada']['tmp_name'];

          $validImageExtension = ['jpg', 'jpeg', 'png'];
          $imageExtension = explode('.',$fileName);
          $imageExtension = strtolower(end($imageExtension));
          if(!in_array($imageExtension, $validImageExtension)){
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
          $output = 'includes/uploaded/portadas/' . $newImageName;
          imagewebp($content,$output);
          imagedestroy($content);

            
            //move_uploaded_file($file, 'includes/uploaded/portadas/' . $newImageName);

          }
          if(empty($errorMsg)){
            try{
              $created = new DateTime("now", new DateTimeZone('America/Montevideo'));
              $created = $created->format('Y-m-d H:i:s');
              $id_session = $_SESSION['user']['id'];
              $username = $_SESSION['user']['username'];
              $email = $_SESSION['user']['email'];

              $insert_stmt = $db->prepare("INSERT INTO u956478100_quehayhoy.unvalidated_events (username,email,created,portada,titulo,descripcion,categoria,generos,fecha,primera_fecha,hora,primera_hora,lugar,ubicacion,departamento,precio,link,nombre,telefono) VALUES (:username,:email,:created,:portada,:titulo,:descripcion,:categoria,:generos,:fecha,:primera_fecha,:hora,:primera_hora,:lugar,:ubicacion,:departamento,:precio,:link,:nombre,:telefono)");
              if($insert_stmt->execute(
              [
                ':username' => $username,
                ':email' => $email,
                ':created' => $created,
                ':portada' => $newImageName,
                ':titulo' => $titulo,
                ':descripcion' => $descripcion,
                ':categoria' => $categoria,
                ':generos' => $genero,
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
              ])
            )
              {
              header("location: crearEvento.php");
              }
            }
            catch(PDOException $e){
                $pdoError = $e->getMessage();
                echo $pdoError;
              }
          }
        }        
      }




  }
  }

  $deportes = false;

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
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Página para crear un eveneto en QueHayHoy?. Puedes seleccionar entre varias categorias, subir tu portada, titulo, descripcion, fechas y entre más.">
  <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png" sizes="16x16" />
  <link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="includes/css/style_creareventos.css">
  <link rel="stylesheet" type="text/css" href="includes/css/style_footer.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <title>Crear Evento | QueHayHoy?</title>
  <link rel="shortcut icon" href="includes/images/favicon.ico" type="image/x-icon" />
</head>
<body class="" id="bodyID">
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

  <div class="container-fluid">
    <div class="row">
      <div class="col-12 main_pg">
        <h1 class="text-center">CREAR EVENTO</h1>
        <form class="row g-3 mt-3 needs-validation" novalidate autocomplete="off" method="post" enctype="multipart/form-data">
          <div class="col-12" style="display: flex; align-items:center; justify-content:center;">
            <div class="" id="" style="">
              <label for="input_file" id="drop_area">
                <input type="file" name="portada" accept="image/*" class="" id="input_file" hidden>
                <div id="img_view">
                  <div id="textImgRemove">
                  <img src="includes/images/upload.png" alt = "Subir imagen">
                  
                    <p>Arrastre y suelte o clickee aquí<br> para subir la imagen.</p>
                    <span>Tamaño recomendado: 1080x1080 <br>Formatos permitidos: jpg, jpeg, png </span>
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
          </div>
          <div class="row mt-5" >
            <div class="col-md-6 col-lg-6 col-sm-12">

          <div class="input-group">
            <span class="input-group-text" id="tituloID">Título del evento<span class="text-danger">*</span></span>
            <input type="text" class="form-control" placeholder="Título" aria-label="Título" aria-describedby="tituloID" name="titulo">
            
          </div>
          <?php 
            if(isset($errorMsg[0])){
              foreach($errorMsg[0] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
          ?>

        <div class="input-group mt-2">
          <span class="input-group-text">Descripción<span class="text-danger">*</span></span>
          <textarea class="form-control" aria-label="Descripción de tú evento..." name ="descripcion"></textarea>
        </div>
        <?php 
            if(isset($errorMsg[1])){
              foreach($errorMsg[1] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
          ?>
        <div class="input-group mt-2">
            <label class="input-group-text" for="categoriaDiv">Categoría...<span class="text-danger">*</span></label>
            <select class="form-select" id="categoriaDiv" onchange="valueCat()" name="categoria">
              <option selected value="Cine">Cine</option>
              <option value="Deportes">Deportes</option>
              <option value="Teatro">Teatro</option>
              <option value="Música">Música</option>
              <option value="Danza">Danza</option>
              <option value="Gastronomía">Gastronomía</option>
              <option value="Carnaval">Carnaval</option>
              <option value="Cursos">Cursos</option>
              <option value="Noche">Noche</option>
              <option value="Ferias">Ferias</option>
              <option value="Encuentros">Encuentros</option>
              <option value="Espacios Públicos">Espacios Públicos</option>
              <option value="Exposiciones">Exposiciones</option>
              <option value="Museos">Museos</option>
              <option value="Otro">Otro</option>
            </select>
          </div>
          <?php 
            if(isset($errorMsg[2])){
              foreach($errorMsg[2] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
          ?>

          <h5 class="mt-3" id="genero_titulo">Sub-Categoría<span class="text-danger"></span></h5>
          <div class="generos_cine" id = "generos_div">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Acción" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Acción</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Aventura" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Aventura</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Ciencia_Ficción" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Ciencia Ficción</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Comedia" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Comedia</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Documental" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Documental</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Drama" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Drama</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Fantasía" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Fantasía</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox9" value="Musical" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox9">Musical</label>
            </div>
                        <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox10" value="Suspense" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox10">Suspense</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox11" value="Terror" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox11">Terror</label>
            </div>
          </div>
          <?php 
            if(isset($errorMsg[3])){
              foreach($errorMsg[3] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
          ?>
        </div>
        

        
        <div class="col-md-6 col-lg-6 col-sm-12"> 
          <div id = "parent"> 
            <div class="input-group mb-2" id="duplicate_date">
              <span class="input-group-text">Fecha<span class="text-danger">*</span></span>
              <input type="date" aria-label="Fecha" class="form-control" id="fechaID" name = "fecha0">
              <span class="input-group-text">Hora<span class="text-danger">*</span></span>
              <input type="time" aria-label="Hora" class="form-control" id=horaID name = "hora0">
              
          </div>
        </div>
        <span name = "1" id ="countDates"></span>
        <div class="inCenter text-center mt-2">
          <a class = "buttonChange mx-3 text-dark text-center" type="button" id="button_dup" onclick="duplicate();" name = "1">+</a>
          <a class = "buttonChange mx-3 text-dark text-center" type="button" id="button_rem" onclick="remove()">-</a>
        </div>
        <?php 
            if(isset($errorMsg[4])){
              foreach($errorMsg[4] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
            if(isset($errorMsg[5])){
              foreach($errorMsg[5] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
          ?>
          
            <div class="input-group mt-2"> 
                <span class="input-group-text" id="lugarID">Lugar<span class="text-danger">*</span></span>
                <input type="text" class="form-control" placeholder="Nombre del lugar" aria-label="Nombre del lugar" aria-describedby="lugarID" name = "lugar">
            </div>
            <?php 
            if(isset($errorMsg[16])){
              foreach($errorMsg[16] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
          ?>
          <div class="input-group mt-2"> 
                <span class="input-group-text" id="ubicacionID">Ubicación<span class="text-danger">*</span></span>
                <input type="text" class="form-control" placeholder="Ubicación" aria-label="Ubicacion" aria-describedby="ubicacionID" name = "ubicacion">
            </div>
            <?php 
            if(isset($errorMsg[6])){
              foreach($errorMsg[6] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
          ?>

          <div class="input-group mt-2">
            <label class="input-group-text" for="departamentoDiv">Departamento...<span class="text-danger">*</span></label>
            <select class="form-select" id="departamentoDiv" onchange="" name="departamentos">
              <option selected value="Artigas">Artigas</option>
              <option value="Canelones">Canelones</option>
              <option value="Cerro Largo">Cerro Largo</option>
              <option value="Colonia">Colonia</option>
              <option value="Durazno">Durazno</option>
              <option value="Flores">Flores</option>
              <option value="Florida">Florida</option>
              <option value="Lavalleja">Lavalleja</option>
              <option value="Maldonado">Maldonado</option>
              <option value="Montevideo">Montevideo</option>
              <option value="Paysandú">Paysandú</option>
              <option value="Río Negro">Río Negro</option>
              <option value="Rivera">Rivera</option>
              <option value="Rocha">Rocha</option>
              <option value="Salto">Salto</option>
              <option value="San José">San José</option>
              <option value="Soriano">Soriano</option>
              <option value="Tacuarembó">Tacuarembó</option>
              <option value="Treinta y Tres">Treinta y Tres</option>
            </select>
          </div>
          <?php 
            if(isset($errorMsg[17])){
              foreach($errorMsg[17] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
          ?>

            <div class="input-group mt-2"> 
                <span class="input-group-text" id="precioID">Precio ($UYU)<span class="text-danger">*</span></span>
                <input type="text" class="form-control" placeholder="Precio ($UYU)" aria-label="Precio" aria-describedby="precioID" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" inputmode="numeric" pattern="[0-9]*"name = "precio">
            </div>
            <?php 
            if(isset($errorMsg[7])){
              foreach($errorMsg[7] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
          ?>

            <div class="input-group mt-2"> 
                <span class="input-group-text" id="linkID">Link de compra/contacto<span class="text-danger">*</span></span>
                <input type="text" class="form-control" placeholder="Link" aria-label="Link" aria-describedby="linkID" name="link">
            </div>
            <?php 
            if(isset($errorMsg[8])){
              foreach($errorMsg[8] as $emailErrors){
                 echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
              }
            }
          ?>
        </div>
        </div>

            <h2>Información extra (solo pública a los administradores)</h2>

            <div class="input-group">
                <span class="input-group-text" id="nombreID">Nombre<span class="text-danger">*</span></span>
                <input type="text" class="form-control" placeholder="Nombre" aria-label="Nombre" aria-describedby="nombreID" name="nombre">
            </div>
            <?php 
                if(isset($errorMsg[9])){
                  foreach($errorMsg[9] as $emailErrors){
                     echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
                  }
                }
              ?>
            <!--<div class="input-group">
                <span class="input-group-text" id="basic-addon1">Mail<span class="text-danger">*</span></span>
                <input type="text" class="form-control" placeholder="Mail" aria-label="Mail" aria-describedby="basic-addon1" name="mail">
            </div>
            <?php 
                /*if(isset($errorMsg[10])){
                  foreach($errorMsg[10] as $emailErrors){
                     echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
                  }
                }*/
              ?>-->
            <div class="input-group">
                <span class="input-group-text" id="telefonoID">Teléfono<span class="text-danger">*</span></span>
                <input type="text" class="form-control" placeholder="Teléfono" aria-label="Telefono" aria-describedby="telefonoID" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" inputmode="numeric" pattern="[0-9]*" name="telefono">
            </div>
            <?php 
                if(isset($errorMsg[11])){
                  foreach($errorMsg[11] as $emailErrors){
                     echo "<p class = 'small text-danger text-center'>".$emailErrors."</p>";
                  }
                }
              ?>
            <div class="col-12 mb-3" style="display: flex; align-items:center; justify-content:center;">
              <button class="btn btn-primary" type="submit" name="buttonGuardado">GUARDAR</button>
            </div>

      </form>
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

    document.getElementById('button_dup').onclick = duplicate;
    document.getElementById('button_rem').onclick = remove;
    var i = 1;
var original_date = document.getElementById('duplicate_date');
var countDates = document.getElementById('button_dup');

function duplicate() {
    var clone_date = original_date.cloneNode(true);
    clone_date.id = "duplicetor_date" + i;
    clone_date.children[1].id = "fechaID" + i;
    clone_date.children[3].id = "horaID" + i;
    clone_date.children[1].name = "fecha" + i;
    clone_date.children[3].name = "hora" + i;
    original_date.parentNode.appendChild(clone_date);
    ++i;
    countDates.name = i;
}

var parent = document.getElementById("parent");

function remove(){
  if(document.getElementById('duplicetor_date1') != null){
    parent.lastChild.remove();
    --i;
    countDates.name = i;
  }
  
}

function valueCat(){
  let getValue = document.getElementById('categoriaDiv').value;
  console.log(getValue); // This will output the value selected.

  switch(getValue){
    case "Cine":
      document.getElementById('generos_div').innerHTML = `
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Acción" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Acción</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Aventura" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Aventura</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Ciencia_Ficción" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Ciencia Ficción</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Comedia" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Comedia</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Documental" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Documental</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Drama" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Drama</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Fantasía" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Fantasía</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox9" value="Musical" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox9">Musical</label>
            </div>
                        <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox10" value="Suspense" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox10">Suspense</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox11" value="Terror" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox11">Terror</label>
            </div>
      `;
    break;
    case "Deportes":
      document.getElementById('generos_div').innerHTML = `
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Automovilismo" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Automovilismo</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Baloncesto" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Baloncesto</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Béisbol" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Béisbol</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Boxeo" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Boxeo</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Ciclismo" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Ciclismo</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Críquet" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Críquet</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Fútbol" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Fútbol</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox9" value="Fútbol_Americano" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox9">Fútbol Americano</label>
            </div>
                        <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox10" value="Gimnasia_Artística" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox10">Gimnasia Artística</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox11" value="Golf" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox11">Golf</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox12" value="Hockey" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox12">Hockey</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox13" value="Natación" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox13">Natación</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox14" value="Rugby" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox14">Rugby</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox15" value="Tenis" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox15">Tenis</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox16" value="Tenis_de_Mesa" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox16">Tenis de Mesa</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox17" value="Voleibol" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox17">Voleibol</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox18" value="Otro" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox18">Otro</label>
            </div>
      `;
    break;
    case "Teatro":
      document.getElementById('generos_div').innerHTML = `
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Comedia" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Comedia</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Drama" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Drama</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Monólogo" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Monólogo</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Musical" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Musical</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Tragedia" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Tragedia</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Tragicomedia" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Tragicomedia</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Ópera" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Ópera</label>
            </div>
      `;
    break;
    case "Música":
      document.getElementById('generos_div').innerHTML = `
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Blues" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Blues</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Clásica" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Clásica</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Country" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Country</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Cumbia" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Cumbia</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Electrónica" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Electrónica</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Folklore" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Folklore</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Funk" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Funk</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox9" value="Hip-Hop" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox9">Hip-Hop</label>
            </div>
                        <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox10" value="Jazz" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox10">Jazz</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox11" value="Metal" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox11">Metal</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox12" value="Pop" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox12">Pop</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox13" value="Punk" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox13">Punk</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox14" value="R&B" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox14">R&B</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox15" value="Reggae" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox15">Reggae</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox16" value="Rock" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox16">Rock</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox17" value="Salsa" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox17">Salsa</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox18" value="Otro" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox18">Otro</label>
            </div>
      `;
    break;
    case "Danza":
      document.getElementById('generos_div').innerHTML = `
     
      `;
    break;
    case "Gastronomía":
      document.getElementById('generos_div').innerHTML = `
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Bar" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Bar</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Cafetería" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Cafetería</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Cervecería" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Cervecería</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Comida_Rápida" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Comida Rápida</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Mercado" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Mercado</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox6" value="Pizzería" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox6">Pizzería</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Puesto_Callejero" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Puesto Callejero</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Restaurante" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Restaurante</label>
            </div>
            `;
    break;
    case "Carnaval":
      document.getElementById('generos_div').innerHTML = `
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Candombe" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Candombe</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Comparsas" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Comparsas</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Corsos_Barriales" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Corsos Barriales</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Desfiles" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Desfiles</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Ensayos" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Ensayos</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Llamadas" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Llamadas</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Murgas" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Murgas</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox9" value="Tablado" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox9">Tablado</label>
            </div>
      `;
    break;
    case "Cursos":
      document.getElementById('generos_div').innerHTML = `
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Académicos" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Académicos</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Artístico" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Artístico</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Desarrollo_Personal" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Desarrollo Personal</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Gastronomía" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Gastronomía</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Idiomas" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Idiomas</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Online" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Online</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Presencial" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Presencial</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox9" value="Presencial" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox9">Presencial</label>
            </div>
      `;
    break;
    case "Noche":
      document.getElementById('generos_div').innerHTML = `
     
      `;
    break;
    case "Ferias":
      document.getElementById('generos_div').innerHTML = `
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Agricultura" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Agricultura</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Alimentos" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Alimentos</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Artesanía" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Artesanía</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Automóviles" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Automóviles</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Comercial" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Comercial</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Cultura" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Cultura</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="de_Libros" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">de Libros</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox9" value="Educación" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox9">Educación</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox10" value="Gastronómica" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox10">Gastronómica</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox11" value="Moda" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox11">Moda</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox12" value="Tecnología" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox12">Tecnología</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox13" value="Turismo" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox13">Turismo</label>
            </div>
      `;
    break;
    case "Encuentros":
      document.getElementById('generos_div').innerHTML = `
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Académico" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Académico</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Artístico" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Artístico</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Cultural" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Cultural</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Deportivo" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Deportivo</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Negocios" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Negocios</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Online" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Online</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Religioso" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Religioso</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox9" value="Social" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox9">Social</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox10" value="Voluntariado" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox10">Voluntariado</label>
            </div>
      `;
    break;
    case "Espacios Públicos":
      document.getElementById('generos_div').innerHTML = `
<div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox10" value="Áreas_de_Picnic" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox10">Áreas de Picnic</label>
            </div>
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Bibliotecas" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Bibliotecas</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Espacios_Deportivos" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Espacios Deportivos</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Jardines_Botánicos" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Jardines Botánicos</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Miradores" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Miradores</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Parques" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Parques</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Paseos" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Paseos</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Playas" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Playas</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox9" value="Plazas" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox9">Plazas</label>
            </div>
      `;
    break;
    case "Exposiciones":
      document.getElementById('generos_div').innerHTML = `
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Agricultura" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Agricultura</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Animales" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Animales</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Arte" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Arte</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Artesanía" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Artesanía</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Automóviles" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Automóviles</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Ciencia" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Ciencia</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Deportes" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Deportes</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox9" value="Educación" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox9">Educación</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox10" value="Fotografía" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox10">Fotografía</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox11" value="Historia" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox11">Historia</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox12" value="Joyería" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox12">Joyería</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox13" value="Libros" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox13">Libros</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox14" value="Medio_Ambiente" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox14">Medio Ambiente</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox15" value="Moda" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox15">Moda</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox16" value="Negocios" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox16">Negocios</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox17" value="Tecnología" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox17">Tecnología</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox18" value="Turismo" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox18">Turismo</label>
            </div>
      `;
    break;
    case "Museos":
      document.getElementById('generos_div').innerHTML = `
      <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Antropología" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox1">Antropología</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Arqueología" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox2">Arqueología</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Arte" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox3">Arte</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Astronomía" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox4">Astronomía</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Botánica" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox5">Botánica</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="Ciencia" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox7">Ciencia</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox8" value="Comunicación" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox8">Comunicación</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox9" value="Deporte" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox9">Deporte</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox10" value="Fotografía" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox10">Fotografía</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox11" value="Historia" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox11">Historia</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox12" value="Juegos" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox12">Juegos</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox13" value="Literatura" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox13">Literatura</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox14" value="Medicina" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox14">Medicina</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox15" value="Moda" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox15">Moda</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox16" value="Música" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox16">Música</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox17" value="Tecnología" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox17">Tecnología</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox18" value="Transporte" name="generos[]">
              <label class="form-check-label" for="inlineCheckbox18">Transporte</label>
            </div>
      `;
    break;
    case "Otro":
      document.getElementById('generos_div').innerHTML = '';
    break;
  }
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
  imgView.style.border = 0;
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

//Calendario de hoy en adelante:

var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

today = yyyy+'-'+mm+'-'+dd;
document.getElementById("fechaID").setAttribute("min", today);

</script>
</body>
</html>