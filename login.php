<!doctype html>
  <?php  require_once 'connection.php'; 

  session_start();
 //Checkear si el usuario inició sesión, y en caso positivo, mandarlo a la página principal
  if(isset($_SESSION['user'])){
    header("location: index.php");
  }
//Si se hizo click en login, continuar con el código
  if(isset($_REQUEST['login_btn'])){
    //Asignar variables a los datos ingresados por el usuario, con la funcion htmlspecialchars para evitar ataques XSS
        $email = htmlspecialchars(($_REQUEST['email']));
        $password = htmlspecialchars($_REQUEST['password']);

        //Fijarse si hay algun tipo de error en los datos ingresados por el usuario, si no hay ningun error, continuar con el codigo

        if(empty($email)){
          $errorMsg[] = "Tienes que insertar un email";
        }
        elseif(empty($password)){
          $errorMsg[] = "Tienes que insertar una contraseña";
        }
        else{
          try{
            //Seleccionar todos los datos que correspondan al mail ingresado por el usuario
            $select_stmt = $db->prepare("SELECT * from u956478100_quehayhoy.users WHERE email = :email LIMIT 1");
            $select_stmt->execute([
              ':email' => $email
            ]);

            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
            //Si existe algun dato, significa que la cuenta existe, pero si no hay ninguno, significa que no existe, y se le dice al usuario que o su mail o contraseña son incorrectos
            if($select_stmt->rowCount() > 0){
              //Se verifica si la contraseña que puso el usuario fue correcta, en caso positivo continuar con el codigo
              if(password_verify($password, $row["password"])){
                //Añadir todos los datos del usuario a su sesión para que mientras no cierre sesion los datos se mantengan
                $_SESSION['user']['id'] = $row["id"];
                $_SESSION['user']['username'] = $row["username"];
                $_SESSION['user']['email'] = $row["email"];
                $_SESSION['user']['admin'] = $row["admin"];
                $_SESSION['user']['descripcion'] = $row["descripcion"];
                $_SESSION['user']['foto'] = $row["foto"];
                $_SESSION['user']['pagina'] = $row["pagina"];
                $_SESSION['user']['instagram'] = $row["instagram"];
                $_SESSION['user']['facebook'] = $row["facebook"];

                header("location: index.php");
              }
              else{
                $errorMsg[] = "Email o contraseña incorrectos.";
              }
            }
            else{
              $errorMsg[] = "Email o contraseña incorrectos.";
            }
          }
          catch(PDO_EXCEPTION $e){
            echo $e->getMessage();
          }
          
        }

  }
  ?>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ingreso de usuario para la página uruguaya QueHayHoy?, que permite publicar eventos, y comentar en eventos ya creados.">
    <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png" sizes="16x16" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="includes/css/style_login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spartan:wght@300;600&display=swap" rel="stylesheet">

    <title>Ingresar Usuario | QueHayHoy?</title>
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
            <img src="includes/images/logoPag_2.png" class = "img-fluid w-50" alt="Logo de la página.">
          </div>
          <div class = "px-lg-5 py-lg-4 p-4 w-100 align-self-center">
            <h1 class = "font-weight-bold mb-4">Bienvenido de vuelta</h1>
            <form autocomplete="off">
              <div class="mb-4">
                <label for="InputEmail1" class="form-label font-weight-bold">Email</label>
                <input type="email" class="form-control text-light border-0" placeholder="Ingresa tu email" id="InputEmail1" aria-describedby="mailDesc" style="background-color: #212529" name = "email">
                <p class ="mt-2 form-text text-muted text-decoration-none" id="mailDesc">Ingresa tu email.</p>
              </div>
              <div class="mb-4">
                <label for="InputPassword1" class="form-label">Contraseña</label>
                <input type="password" class="form-control text-light border-0 mb-2" placeholder="Ingresa tu contraseña" id="InputPassword1" style="background-color: #212529" name = "password" aria-describedby="contraDesc">
                <p class ="mt-2 form-text text-muted text-decoration-none" id="contraDesc">Ingresa tu contraseña.</p>
                <!--<a href="" id="emailHelp" class="form-text text-muted">¿Has olvidado tu contraseña?</a>-->
              </div>
              <button type="submit" class="btn btn-primary w-100" name = "login_btn">Iniciar sesión</button>
              <?php  
                  if(isset($errorMsg)){
                    foreach($errorMsg as $loginErrors){
                      echo "<p class = 'mt-3 small text-danger'>".$loginErrors."</p>";
                    }
                  }
                ?>
            </form>
          </div>
            <div class = "text-center px-lg-5 pt-lg-3 pb-lg-4 p-4 w-100 mt-auto">
              <p class = "d-inline-block mb-0">¿Todavía no tienes una cuenta?</p> <a href="signup.php" class = "text-light font-weight-bold">Crea una ahora</a>
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