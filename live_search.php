<?php 
session_start();
	require_once 'connection.php';
	include 'functions.php';
	$q= htmlspecialchars($_GET["q"]);
	if(!empty($_SESSION['where'])){
		$newWhere = $_SESSION['where'] . " AND (titulo LIKE ". "'%".$q."%')";
	}
	else{
		//$newWhere = "WHERE titulo LIKE " . "'%".$q."%'" . " OR descripcion LIKE " . "'%".$q."%'" ;
		$newWhere = "WHERE titulo LIKE " . "'%".$q."%'";
	}
	
	$newOrderBy = $_SESSION['order_by'];
	$pagActive = $_SESSION['pagActive'];
	//echo($newWhere);

	//echo("SELECT count(*) FROM u956478100_quehayhoy.validated_events ". $newWhere);

	$infoSearch = $db->prepare("SELECT * FROM u956478100_quehayhoy.validated_events $newWhere $newOrderBy");
	$infoSearchTotal = $db->query("SELECT count(*) FROM u956478100_quehayhoy.validated_events $newWhere")->fetchColumn();
	$infoSearch->execute([]);
    for($k = 0; $k < $infoSearchTotal; $k++){
    	
    	$rowInfoSearch[$k][] = $infoSearch->fetch(PDO::FETCH_ASSOC);

    	//$users_id[$k] = $rowusers[];
    }

    $p = 0;
   	if(!empty($infoSearchTotal)){
   		foreach ($rowInfoSearch as $infoSearch_Events) {
   			if(isset($infoSearch_Events)){
   				$idusers[$p] = $infoSearch_Events[0]['id'];
	    		$nameusers[$p] = $infoSearch_Events[0]['username'];
	    		$scoreusers[$p] = $infoSearch_Events[0]['email'];
	    		$eventsID[$p] = $infoSearch_Events[0]['id'];
	    		$eventsPortada[$p] = $infoSearch_Events[0]['portada'];
	    		$eventsTitulo[$p] = $infoSearch_Events[0]['titulo'];
	    		$eventsDesc[$p] = $infoSearch_Events[0]['descripcion'];
	    		$eventsCategoria[$p] = $infoSearch_Events[0]['categoria'];
	    		$eventsFechas = $infoSearch_Events[0]['fecha'];
	    		$fechanocoma = str_replace(",", "", $eventsFechas);
	     		$fechanospace= explode(' ', $fechanocoma);
	     		$eventsTimestamp = str_replace("_", " ", $fechanospace[0]);
	     		//echo nl2br ("\n");
	     		$timestamp = strtotime($eventsTimestamp);
	     		$dayNum = date('d', $timestamp);
				$day_events = GetDay($timestamp);
				$month_events = GetMonth($timestamp);
				$year_events = date('Y', $timestamp);
	    		$eventsHoras = $infoSearch_Events[0]['hora'];
	    		$horanocoma_events = str_replace(",", "", $eventsHoras);
	     		$horanospace_events= explode(' ', $horanocoma_events);
	     		$eventsHora = str_replace("_", " ", $horanospace_events[0]);
	     		$eventsFecha[$p] = $day_events . ", " .$dayNum . " de " . $month_events . " | " .$eventsHora. "HS.";
				//echo($cineID[$c]);
	    		$eventsLugar[$p] = $infoSearch_Events[0]['lugar'];
	    		$eventsPrecio[$p] = $infoSearch_Events[0]['precio'];
   			}
   			$p++;
   		}
   	}

	if(($infoSearchTotal/$pagActive) > 25){
					for($i = ($pagActive-1)*25; $i < 25*$pagActive; $i++){
					echo('
						<div class = "container-fluid my-3">
						<div class = "row no-gutters bgPost border-4 border-dark">
							<div class="col-12 text-center bg_'.str_replace(' ', '_', $eventsCategoria[$i]));echo(' border-4 border-dark border-bottom" style="border-top-left-radius: 0.6rem; border-top-right-radius: 0.6rem; height:3.4em;">
								<h1 style="line-height: 1.6em; font-size: 2.1em;">');echo($eventsCategoria[$i]);echo('</h1>	
								
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
				else if($infoSearchTotal == 0){
					echo('

						<h2 class = "inCenter mt-5">No hay eventos con estos filtros.</h2>
						'
					);
				}
				else{
					for($i = ($pagActive-1)*25; $i < $infoSearchTotal; $i++){
					echo('
						<div class = "container-fluid my-3">
						<div class = "row no-gutters bgPost border-4 border-dark ">
							<div class="col-12 text-center bg_'.str_replace(' ', '_', $eventsCategoria[$i]));echo(' border-4 border-dark border-bottom" style="border-top-left-radius: 0.6rem; border-top-right-radius: 0.6rem; height:3.4em;">
								<h1 style="line-height: 1.6em; font-size: 2.1em;">');echo($eventsCategoria[$i]);echo('</h1>	
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