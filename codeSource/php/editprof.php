<?php
	include 'connect.php';
	include 'header.php';
	
	
	
	$sql=$con->prepare('SELECT * FROM users where user_id = :id');
	
	$sql->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $sql->execute();
	$row = $sql->fetch(PDO::FETCH_ASSOC);
	
	
	
	if ($_SERVER['REQUEST_METHOD'] != 'POST' ){		//affiches les champs avec les infos actuels préremplis 
		echo'
		<form method="post" >
		<p>';
		
		
		echo  'Modifier votre nom d\'utilisateur : ';
		echo'<br/>
		<input  type="text" name="user_name" size="30" value="';
		echo(htmlentities(($row['user_name'])));
		echo'"/><br/><br/>		';				
		
		echo  'Modifier votre pays : <br>';
		echo'<br/>'. country($row['country']);
		echo' <br/>Modifier votre email : ';
		echo'<br/>
		<input  type="text" name="user_email" size="30" value="';
		echo(htmlentities($row['user_email']));
		echo'"/><br/><br/> Modifier votre description : <br/>
		<textarea  name="description">'.htmlentities($row['description']).'</textarea><br/><br/>';
		
		echo  'Modifier votre mot de passe : <br/>
		<input  type="password" name="user_pass" size="30"  /><br/><br/>';
		echo  'Confirmer votre mot de passe : <br/>
		<input  type="password" name="user_pass_check" size="30" /><br/><br/>
		<input type="submit" name="valider" value="modifier"/>
		<input type="submit" name="delete" value="delete profil"/>
		</p>
		</form>
		
		<div id="aside">';
		
	}
	
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST' ) 
	{	
		if(isset($_POST['valider'])){ //si on valide les changements
			if(checkinfos()==true){ //méthode de verif validité des données
				
				
				try //tente l'update
				{
					$req= 'UPDATE users SET user_name = :login, country =:country, user_email =:email, user_pass =:pass, description=:desc 
					WHERE user_id ="'.$_SESSION['user_id'].'"';
					$reqpreparee= $con->prepare($req);
					
					$reqpreparee ->execute(array('login'=>$_POST['user_name'], 
					'country'=>$_POST['country'], 
					'email'=>$_POST['user_email'], 
					'pass'=>sha1($_POST['user_pass']), 
					'desc'=>$_POST['description'], 
					));
				}
				catch(Exception $e)
				{
					die('Erreur : '.$e->getMessage());
				}
				
				echo '<br/><br/> Informations modifiées avec succès <br/>';
				
				$reqpreparee->closeCursor();
			}
			else
			{
				echo 'Vous devez remplir tous les champs !';
			}}
			
			else if(isset($_POST['delete'])){ //si on clique sur delete, supprime l'user de la table
				$del=$con->prepare('delete from users where user_id=:usertodel');
				$del->bindParam(':usertodel', $_SESSION['user_id'], PDO::PARAM_STR);
				$del->execute();
				print_R('User successfully deleted');
				
				session_destroy();
				header('Location: ../index.php');
				exit();
			}}
?>
<br/><br/><a href="../index.php">Retour a la page acceuil</a>
</div>
<?php
	
	include 'footer.php';
	?>	