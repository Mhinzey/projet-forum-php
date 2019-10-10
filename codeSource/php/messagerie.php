<?php
	include 'connect.php';
	include 'header.php';
	if (!isset($_SESSION['signed_in'])) {
		
		echo'Vous n\'êtes pas connecté';
		//header ('Location: ../index.php');
		exit();
	}
	
	echo'<a class="item" href="myChat.php">Mes conversations</a> ';
	echo'<a class="item" href="messagerie.php?option=new">messages non lus</a> ';
	echo'<a class="item" href="messagerie.php?option=sent">messages envoyés</a> ';
	echo'<a class="item" href="messagerie.php">Tout mes messages</a> <br><br>';
	if(!isset($_GET['option'])){ //si pas d'option set, montre tous les messages reçus
		$sql = $con->prepare("SELECT * FROM messages as m INNER JOIN users as u ON m.id_from=u.user_id WHERE id_to=:to ORDER BY m.msg_date DESC");
		
		$sql->execute(array('to' => $_SESSION['user_id']));
		$results = $sql->fetchAll(PDO::FETCH_ASSOC);
		
		if($results==0){
			echo'Vous n\'avez aucun message';
		}
		else{
			
			foreach($results as $row){
				
				echo $row['msg_date'] , ' - <a href="read.php?id_message=' , $row['msg_id'] , '">' , stripslashes(htmlentities(trim($row['title']))) , '</a> [ Message de ' , stripslashes(htmlentities(trim($row['user_name']))) , ' ]<br />';
			}
			
		}
	}
	else if(isset($_GET['option'])&&$_GET['option']=='new'  ) { //si l'option = new, montre uniquement les messages reçus qui ont l'état défini sur non lu
		
		$sql = $con->prepare("SELECT * FROM messages as m INNER JOIN users as u ON m.id_from=u.user_id WHERE id_to=:to and opened=0 ORDER BY m.msg_date DESC");
		
		$sql->execute(array('to' => $_SESSION['user_id']));
		$results = $sql->fetchAll(PDO::FETCH_ASSOC);
		if($results==null){
			echo'Vous n\'avez aucun message non lu';
		}
		else{
			
			foreach($results as $row){
				
				echo $row['msg_date'] , ' - <a href="read.php?id_message=' , $row['msg_id'] , '">' , stripslashes(htmlentities(trim($row['title']))) , '</a> [ Message de ' , stripslashes(htmlentities(trim($row['user_name']))) , ' ]<br />';
			}
			
		}
	}
	else if(isset($_GET['option'])&&$_GET['option']=='sent'  ) { //si option=envoyer, on affiche les messages dont on est l'auteur, et le destinataire à qui on l'a envoyé 
		
		$sql = $con->prepare("SELECT * FROM messages as m INNER JOIN users as u ON m.id_to=u.user_id WHERE id_from=:to ORDER BY m.msg_date DESC");
		
		$sql->execute(array('to' => $_SESSION['user_id']));
		$results = $sql->fetchAll(PDO::FETCH_ASSOC);
		if($results==0){
			echo'Vous n\'avez aucun message envoyé';
		}
		else{
			
			foreach($results as $row){
				
				echo $row['msg_date'] , ' - <a href="read.php?id_message=' , $row['msg_id'] , '">' , stripslashes(htmlentities(trim($row['title']))) , '</a> [ Message à ' , stripslashes(htmlentities(trim($row['user_name']))) , ' ]<br />';
			}
			
		}
	}
	
?>
<br /><a href="sendmsg.php">Envoyer un message</a>
<?php
	include 'footer.php';
?>