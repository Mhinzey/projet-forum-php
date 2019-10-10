<?php

include 'connect.php';
include 'header.php';

if (!isset($_SESSION['signed_in']) or $_SESSION['signed_in'] == false) {
	// si ce n'est pas le cas, on le redirige vers l'accueil
	header ('Location: ../index.php');
	
	
	exit();
	
}
else{

// on teste si l'id du message a bien été fourni en argument au script envoyer.php
if (!isset($_GET['id_message']) || empty($_GET['id_message'])) {
	header ('Location: messagerie.php');
	exit();
}
else {
	
	// on prépare une requête SQL permettant de supprimer le message tout en vérifiant qu'il appartient bien au membre qui essaye de le supprimer
	$sql =$con->prepare( 'DELETE FROM messages WHERE id_to=:to AND msg_id=:idmsg');
	$data=['to' => $_SESSION['user_id'], 'idmsg' =>$_GET['id_message']];
	$sql->execute($data);

	header ('Location: messagerie.php');
	exit();
}}

?>