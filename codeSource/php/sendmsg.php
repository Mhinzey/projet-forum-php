<?php
	include 'connect.php';
	include 'header.php';
	
	if (!isset($_SESSION['signed_in'])) {
		// si ce n'est pas le cas, on le redirige vers l'accueil
		header ('Location: ../index.php');
		exit();
	}
	
	
?>

<?php
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		
		
		
		// on prépare une requete SQL selectionnant tous les login des membres du site en prenant soin de ne pas selectionner notre propre login, le tout, servant à alimenter le menu déroulant spécifiant le destinataire du message
		if(!isset($_GET['id_message']))
		{$sql = $con->prepare('SELECT user_name , user_id  FROM users WHERE user_id <> '.$_SESSION['user_id'].' ORDER BY user_name ASC');
			$req = $sql->execute();
		}
		else{$sql=$con->prepare('SELECT * FROM messages as m INNER JOIN users as u ON m.id_from=u.user_id WHERE msg_id=:to');
			$req=$sql->execute(array('to' => $_GET['id_message']));
		}
		$row = $sql->fetchALL();
		
		if ($row == 0) {
			// si aucun membre n'a été trouvé, on affiche tout simplement aucun formulaire
			echo 'Vous êtes le seul membre inscrit.';
		}
		else {
			// si au moins un membre qui n'est pas nous même a été trouvé, on affiche le formulaire d'envoie de message
		?>
		<form action="sendmsg.php" method="post">
			To : <select name="to">
				<?php
					// on alimente le menu déroulant avec les login des différents membres du site
					$sql->execute();
					$results=$sql->fetchAll();
					
					foreach($results as $row) {
						echo '<option value="' , htmlentities($row->user_id) , '">' , stripslashes(htmlentities(trim($row->user_name))) , '</option>';
					}
				?>
			</select><br />
			Title : <input type="text" name="title" value="<?php if (isset($_POST['title'])) echo stripslashes(htmlentities(trim($_POST['title']))); ?>"><br />
			Message : <textarea name="message"><?php if (isset($_POST['message'])) echo stripslashes(htmlentities(trim($_POST['message']))); ?></textarea><br />
			<input type="submit" name="go" value="Send">
		</form>
		<?php
		}
	}
	else{
		$result=$con->beginTransaction(); 
		// on teste si le formulaire a bien été soumis
		if (isset($_POST['go']) && $_POST['go'] == 'Send') {
			if (empty($_POST['to']) || empty($_POST['title']) || empty($_POST['message'])) {
				$erreur = 'Au moins un des champs est vide.';
			}
			else {
				$time=new DateTime();
				$time=$time->format('y-m-d-h-i-s');
				// si tout a été bien rempli, on insère le message dans notre table SQL
				$sql =$con->prepare('INSERT INTO messages(id_from,id_to,msg_date,title,message) VALUES( :from, :to , :date, :title, :txt)');
				
				$data = [
				'from'=>$_SESSION['user_id'],
				'to'=>$_POST['to'],
				'date'=>$time,
				'title'=>$_POST['title'],
				'txt'=>$_POST['message']
				];
				$result=$sql->execute($data);			
				if(!$result)
				{
					echo 'An error occured while sending msg. Please try again later.';
					$con->rollBack();
					
				}
				
				else
				{
					
					
					$con->commit();
					echo 'Message envoyé';
				}
				
				
				
				header('Location: messagerie.php');
				exit();
			}}
	}
	
?>
</select>
<?php
	if (isset($erreur)) echo '<br /><br />',$erreur;
	include 'footer.php';
	?>									