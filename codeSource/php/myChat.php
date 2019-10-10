<?php
	include 'connect.php';
	include 'header.php';
	if (!isset($_SESSION['signed_in'])) {
		
		header ('Location: ../index.php');
		exit();
	}
	
	echo'<a class="item" href="myChat.php">Mes conversations</a> ';
	echo'<a class="item" href="messagerie.php?option=new">message(s) non lu(s)</a> ';
	echo'<a class="item" href="messagerie.php?option=sent">Messages envoyés</a> ';
	echo'<a class="item" href="messagerie.php">Mes messages</a> <br><br>';
	
	if(!isset($_GET['id_chat'])){
	//renvoie une liste des users avec lesquels on a discuté, et montre le dernier message, si id de chat n'est pas st
		$conv=$con->prepare('select messages.* , user_name from users, messages
		join
		(select max(msg_date) as time,id_from from messages group by id_from) as latest
		on messages.msg_date=latest.time and messages.id_from=latest.id_from where messages.id_to=:to and user_id=latest.id_from ORDER BY time asc;');
		$conv->execute(array('to' => $_SESSION['user_id']));
		$results = $conv->fetchAll(PDO::FETCH_ASSOC);
		
		if($results==0){
			echo'Vous n\'avez aucun message';
		}
		else{
			
			foreach($results as $row){
				
				echo '<a href="myChat.php?id_chat='.$row['id_from'].'"> Messages from ' , stripslashes(htmlentities(trim($row['user_name']))) , '</a> \ Last message:'. $row['message'].' ,  '.$row['msg_date'].  '<br />';
			}
			
		}
	}
	else if (isset($_GET['id_chat'])){
	//si on a une id de chat, montre les messages de ce chat
		$conv=$con->prepare("SELECT * FROM messages as m INNER JOIN users as u ON m.id_from=u.user_id WHERE (id_to=:to and id_from=:uid) or (id_to=:uid and id_from=:to) ORDER BY m.msg_date DESC");
		$conv->execute(array('uid'=> $_GET['id_chat'], 'to' => $_SESSION['user_id']));
		$results = $conv->fetchAll(PDO::FETCH_ASSOC);
		if($results==0){
			echo'Vous n\'avez aucun message';
		}
		foreach($results as $row){
			
			echo $row['msg_date'] , ' - <a href="read.php?id_message=' , $row['msg_id'] , '">' , stripslashes(htmlentities(trim($row['title']))) , '</a> '; 
			if($row['id_to']==$_SESSION['user_id']){
				echo'|| Message reçu <br>';
			}
			else{
				echo'|| Message envoyé <br>';
			}
		}
		
	}
?>
<br /><a href="sendmsg.php">Envoyer un message</a>
<?php
	include 'footer.php';
?>		