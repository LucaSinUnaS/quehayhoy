<?php 
//Funcion que recibe una variable tipo fecha con el valor de las iniciales del dia en ingles, y guarda en la variable cineMes el mes en español para devolverlo.
function GetDay($timestamp){
	$day_F = date('D', $timestamp);
	$cineDia="";
	switch($day_F){
					case "Sun":
						$cineDia = "Domingo";
						break;
					case "Mon":
						$cineDia = "Lunes";
						break;
					case "Tue":
						$cineDia = "Martes";
						break;
					case "Wed":
						$cineDia = "Miércoles";
						break;
					case "Thu":
						$cineDia = "Jueves";
						break;
					case "Fri":
						$cineDia = "Viernes";
						break;
					case "Sat":
						$cineDia = "Sábado";
						break;
				}
				return $cineDia;
}

//Funcion que recibe una variable tipo fecha con el valor de las iniciales del mes en ingles, y guarda en la variable cineMes el mes en español para devolverlo.

function GetMonth($timestamp){
	$month = date('M', $timestamp);
	$cineMes = '';
				switch($month){
					case "Jan":
						$cineMes = "Enero";
						break;
					case "Feb":
						$cineMes = "Febrero";
						break;
					case "Mar":
						$cineMes = "Marzo";
						break;
					case "Apr":
						$cineMes = "Abril";
						break;
					case "May":
						$cineMes = "Mayo";
						break;
					case "Jun":
						$cineMes = "Junio";
						break;
					case "Jul":
						$cineMes = "Julio";
						break;
					case "Aug":
						$cineMes = "Agosto";
						break;
					case "Sep":
						$cineMes = "Setiembre";
						break;
					case "Oct":
						$cineMes = "Octubre";
						break;
					case "Nov":
						$cineMes = "Noviembre";
						break;
					case "Dec":
						$cineMes = "Diciembre";
						break;
				}
				return $cineMes;
}

 
 ?>
