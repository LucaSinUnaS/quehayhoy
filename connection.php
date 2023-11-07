<?php
	$db_host = "localhost";
	$db_user = "u956478100_ztech";
	$db_password = "xxx";
	$db_name = "xxx";

	try{
		$db = new PDO("mysql:host={$db_host};db_name={$db_name}", $db_user, $db_password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDO_EXCEPTION $e){
		echo $e->getMessage();
	}
