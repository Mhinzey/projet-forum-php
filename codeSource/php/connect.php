<?php
	//connect.php
	//établit la connexion avec la base de donnée
date_default_timezone_set('Europe/Brussels');
$hote = 'localhost';
$user = 'root';
$mdp = '';
$nomBD = 'forumdb';
 

try {
$con=new PDO('mysql:host='.$hote.';dbname='.$nomBD,$user,$mdp);
$con->exec("SET NAMES 'utf8'");
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
}
catch (Exception $e) {
echo 'Erreur de connexion à la BD';
} 


?>