<?php
	include 'connect.php';
	include 'header.php';
	
	if (!isset($_SESSION['signed_in'])) {
		// si ce n'est pas le cas, on le redirige vers l'accueil
		header ('Location: ../index.php');
		exit();
	}
	if (!isset($_GET['id_message']) || empty($_GET['id_message'])) {
		echo 'Aucun message reconnu.';
	}
	else {
		
		$sql =$con->prepare('SELECT m.title, m.msg_date, m.message, u.user_name, m.id_to, m.id_from   FROM messages as m INNER JOIN users as u ON m.id_from=u.user_id WHERE msg_id=:idmsg');
		$data=['idmsg' => $_GET['id_message'] ];
		$sql->execute($data);
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		//si l'utilisateur connecté correspond au destinataire ou destinateur
		if($row['id_from'] == $_SESSION['user_id'] || $row['id_to'] == $_SESSION['user_id']){ 
			
			
			
			if($row == 0) {
				echo 'Aucun message reconnu.';
			}
			else {
				
				// si le message a été trouvé, on l'affiche
				
				echo $row['msg_date'] , ' - ' , stripslashes(htmlentities(trim($row['title']))) , '</a> [ Message from ' , stripslashes(htmlentities(trim($row['user_name']))) , ' ]<br /><br />';
				echo nl2br(stripslashes(htmlentities(trim($row['message']))));
				
				// on affiche également un lien permettant de supprimer ce message de la boite de réception
				echo '<br /><br /><a href="deletemsg.php?id_message=' , $_GET['id_message'] , '">Supprimer ce message</a>';
				echo '<br /><br /><a href="sendmsg.php?id_message=' , $_GET['id_message'] , '">Réponde à ce message</a>';
				$update=$con->prepare('UPDATE messages set opened=:state where msg_id=:id');
				$update->execute(array('state'=> true, 'id' =>$_GET['id_message']));
			}
		}
		else{ //l'utilisateur ne peut pas voir le fichier car il ne l'a ni envoyé ni reçu
			echo'Vous ne pouvez pas voir ce message';
		}
	}
?>

<?php
include 'footer.php';
?>