<!doctype html>
  <?php  
  require_once 'connection.php'; 
  
  session_start();
  //Checkear si el usuario inició sesión, y en caso positivo, mandarlo a la página principal
  if(isset($_SESSION['user'])){
    header("location: index.php");
  }

  //Si se hizo click en guardar, continuar con el código
  if(isset($_REQUEST['register_btn'])){

    //Asignar variables a los datos ingresados por el usuario, con la funcion htmlspecialchars para evitar ataques XSS

    $email = strtolower(htmlspecialchars($_REQUEST['email']));
    $username = htmlspecialchars($_REQUEST['username']);
    $password = htmlspecialchars($_REQUEST['password']);
    $repeated_password = htmlspecialchars($_REQUEST['repeated_password']);

    //Fijarse si hay algun tipo de error en los datos ingresados por el usuario

    if(empty($email)){
      $errorMsg[0][] = "Se requiere un email.";
    }

    if(empty($username)){
      $errorMsg[1][] = "Se requiere un usuario.";
    }
    if(strlen($username) < 4 || strlen($username) > 16){
      $errorMsg[1][] = "El usuario debe ser mayor a 4 caracteres y menor a 16 caracteres.";
    }

    if(empty($password)){
      $errorMsg[2][] = "Se requiere una contraseña.";
    }

    if(strlen($password) < 4 || strlen($password) > 16){
      $errorMsg[2][] = "La contraseña debe ser mayor a 4 caracteres y menor a 16 caracteres.";
    }

    if(strcmp($password, $repeated_password) !== 0){
      $errorMsg[3][] = "La contraseña debe ser igual.";
    }
    // Si no hay ningun error, continuar con el codigo
    if(empty($errorMsg)){
      try{
        //Seleccionar el mail y el usuario de la base de datos el cual sea igual al mail y usuario ingresado
        $select_stmt = $db->prepare("SELECT email FROM u956478100_quehayhoy.users WHERE email = :email");
        $select_stmt->execute([':email' => $email]);
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        $select_username_stmt = $db->prepare("SELECT username FROM u956478100_quehayhoy.users WHERE username = :username");
        $select_username_stmt->execute([':username' => $username]);
        $row_username = $select_username_stmt->fetch(PDO::FETCH_ASSOC);

        //Fijarse si el mail y usuario ingresados por el usuario ya existen
        $noError = false;
        if(isset($row['email']) == $email){
          $errorMsg[0][] = "El email ya existe. Por favor, usa otro.";
          $noError = true;
        }

        if(isset($row_username['username']) == $username){
          $errorMsg[1][] = "Este usuario ya fue registrado. Por favor, usa otro.";
          $noError = true;
        }
        //En caso de que no exista ni el mail ni el usuario, continuar
        if($noError == false){
          $hashed_password = password_hash($password, PASSWORD_DEFAULT); //Hashear el codigo para mayor proteccion y seguridad
          $created = new DateTime();
          $created = $created->format('Y-m-d H:i:s');
          $descripcion = '';
          $foto = 'default_profile.webp';
          $pagina = '';
          $instagram = '';
          $facebook = '';
          //Insertar datos a la base de datos, de forma segura sanitizando los datos, y luego mandar al usuario a la página de login
          $insert_stmt = $db->prepare("INSERT INTO u956478100_quehayhoy.users (username,email,password,created,descripcion,foto,pagina,instagram,facebook) VALUES (:username,:email,:password,:created,:descripcion,:foto,:pagina,:instagram,:facebook)");

          if($insert_stmt->execute(
              [
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashed_password,
                ':created' => $created,
                ':descripcion' => $descripcion,
                ':foto' => $foto,
                ':pagina' => $pagina,
                ':instagram' => $instagram,
                ':facebook' => $facebook
              ])
        )
            {
              header("location: login.php");
            }

          }
        }

        catch(PDOException $e){
        $pdoError = $e->getMessage();
        echo $pdoError;
        }
      }
      
    }
  ?>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Creación de usuario para la página uruguaya QueHayHoy?, que permite publicar eventos, y comentar en eventos ya creados.">
    <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png" sizes="16x16" />


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="includes/css/style_login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spartan:wght@300;600&display=swap" rel="stylesheet">


    <title>Crear Usuario | QueHayHoy?</title>
  </head>
  <body class ="bg-dark">
    <section>
      <div class = "row g-0">
        <div class = "col-lg-7 d-none d-lg-block">
          <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item img-1 min-vh-100 active">
                <div class="carousel-caption d-none d-md-block">
                  <h5 class = "font-weight-bold text-dark p-2 fw-bold text_bg">Busca pudiendo elegir entre varios filtros.</h5>
                  <h6 href="" class = "fw-bold text-dark text-decoration-none p-2 text_bg">Podés elegir desde dónde hasta qué tipo de evento quieres ir.</h6>
                </div>
              </div>
              <div class="carousel-item img-2 min-vh-100">
                <div class="carousel-caption d-none d-md-block">
                    <h5 class = "font-weight-bold p-2 fw-bold text-dark text_bg">¿Qué vas a hacer hoy?</h5>
                  <h6 href="" class = "text-decoration-none p-2 fw-bold text-dark text_bg">¡Únite ya para descubrir entre decenas de eventos qué actividad vas a realizar hoy!</h6>
                </div>
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
        <div class = "col-lg-5 d-flex flex-column align-items-end min-vh-100 scrollable">
          <div class = "px-lg-5 pt-lg-4 pb-lg-3 p-4 w-100 mb-auto">
            <!--<h1 class="d-inline-block align-middle original-text-shadow ps-5">STEAMMVD</h1>-->
            <img src="includes/images/logoPag_2.png" class = "img-fluid w-50" alt="Logo de la página.">
          </div>
          <div class = "px-lg-5 py-lg-4 p-4 w-100 align-self-center">
            <h1 class = "font-weight-bold mb-4">Registrate</h1>
            <form autocomplete="off">
              <div class="mb-4">
                <label for="InputEmail1" class="form-label font-weight-bold">Email</label>
                <input type="email" class="form-control text-light border-0" placeholder="Ingresa tu email" id="InputEmail1" aria-describedby="mailDesc" style="background-color: #212529" name = "email">
                <p class ="mt-2 form-text text-muted text-decoration-none" id="mailDesc">Va a ser el que utilices para iniciar sesión, o en caso de que olvides tu contraseña.</p>
                <?php 
                  if(isset($errorMsg[0])){
                    foreach($errorMsg[0] as $emailErrors){
                      echo "<p class = 'small text-danger'>".$emailErrors."</p>";
                    }
                  }
                 ?>
              </div>
              <div class="mb-4">
                <label for="basic-addon1" class="form-label font-weight-bold">Usuario</label>
                <input type="username" class="form-control border-0 text-light" placeholder="Ingresa tu usuario" id="basic-addon1" aria-describedby="userDesc" style="background-color: #212529" name = "username">
                <p class ="mt-2 form-text text-muted text-decoration-none" id="userDesc">Va a ser tu nombre público, por el cual los demás te van a poder reconocer.</p>
                <?php 
                  if(isset($errorMsg[1])){
                    foreach($errorMsg[1] as $userErrors){
                      echo "<p class = 'small text-danger'>".$userErrors."</p>";
                    }
                  }
                 ?>
              </div>
              <div class="mb-4">
                <label for="InputPassword1" class="form-label">Contraseña</label>
                <input type="password" class="form-control text-light border-0 mb-2" placeholder="Ingresa tu contraseña" id="InputPassword1" style="background-color: #212529" name = "password">
                <p class="form-text text-muted text-decoration-none">Tiene que tener entre 4-16 caracteres y no puede tener caracteres especiales.</p>
                <?php 
                  if(isset($errorMsg[2])){
                    foreach($errorMsg[2] as $passwordErrors){
                      echo "<p class = 'small text-danger'>".$passwordErrors."</p>";
                    }
                  }
                 ?>
              </div>
              <div class="mb-4">
                <label for="InputPassword2" class="form-label">Repetir contraseña</label>
                <input type="password" class="form-control text-light border-0 mb-2" placeholder="Repite tu contraseña" id="InputPassword2" style="background-color: #212529" name = "repeated_password">
                <p class="form-text text-muted text-decoration-none font-weight-bold">Asegurate de elegir una contraseña fácil de acordarse.</p>
                <?php 
                  if(isset($errorMsg[3])){
                    foreach($errorMsg[3] as $repeated_passwordErrors){
                      echo "<p class = 'small text-danger'>".$repeated_passwordErrors."</p>";
                    }
                  }
                 ?>
              </div>
              <button type="submit" name = "register_btn" class="btn btn-primary w-100">Registrarse</button>
            </form>
          </div>
            <div class = "text-center px-lg-5 pt-lg-3 pb-lg-4 p-4 w-100 mt-auto">
              <p class = "d-inline-block mb-0">¿Ya tienes una cuenta?</p> <a href="login.php" class = "text-light font-weight-bold">Entra ahora</a>
            </div>
        </div>

        
      </div>
    </section>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
    -->
  </body>
</html>