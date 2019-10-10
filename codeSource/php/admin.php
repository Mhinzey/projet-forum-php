<?php
	include 'connect.php';
	include 'header.php';
?>
<div>
	<section>
		<?php
			if(!isset($_SESSION['signed_in']) or $_SESSION['signed_in'] == false  )
			{
				
				echo 'Vous devez être <a href="signin.php">connecté</a> pour accéder à cette page.';
			}
			else if( isset($_SESSION['signed_in']) && $_SESSION['user_level'] <1){
				echo 'Vous n\avez pas la permission d\'accéder à cette page';
			}
			else{
				
				if(!isset($_GET['option'])){
					
					echo'<a class="item" href="create_cat.php">Créer une categorie</a> ';
					echo'<br><br><a class="item" href="admin.php?option=delcat">Supprimer une catégorie</a> ';
					echo'<br><br><a class="item" href="admin.php?option=modif">Modifier les utilisateurs</a> ';
					echo'<br><br><a class="item" href="admin.php?option=deluser">Supprimer un utilisateur</a> ';
				}
				
				
				if(isset($_GET['option']) && $_GET['option']=="delcat"){
					
					$sql = $con->prepare('SELECT cat_id,cat_name FROM categories ORDER BY cat_name ASC');
					$req = $sql->execute();
					$row = $sql->fetchALL();
					if ($row == 0) {
						// si aucun membre n'a été trouvé, on affiche tout simplement aucun formulaire
						echo 'No categories to delete.';
					}
					else{
						echo'<form action="" method="post">
						categorie à supprimer : <select name="cat">';
						
						// on alimente le menu déroulant avec les login des différents membres du site
						$sql->execute();
						$results=$sql->fetchAll();
						
						foreach($results as $row) {
							echo '<option value="' , $row->cat_id , '">' , stripslashes(htmlentities(trim($row->cat_name))) , '</option>';
						}
						echo'
						</select><br />
						<input type="submit" name="go" value="Valider">
						</form>';
						if ($_SERVER['REQUEST_METHOD'] == 'POST'){
							$del=$con->prepare('delete from categories where cat_id=:cattodel');
							$del->bindParam(':cattodel', $_POST['cat'], PDO::PARAM_STR);
							$del->execute();
							echo'categorie supprimée';
							sleep(3);
							header('Location: admin.php');
						}
					}
					
				}
				
				
				if(isset($_GET['option']) && $_GET['option']=="deluser"){
					
					$sql = $con->prepare('SELECT user_name , user_id  FROM users WHERE user_id <> '.$_SESSION['user_id'].' and user_id<'.$_SESSION['user_id'].' ORDER BY user_name ASC');
					$req = $sql->execute();
					$row = $sql->fetchALL();
					
					if ($row == 0) {
						// si aucun membre n'a été trouvé, on affiche tout simplement aucun formulaire
						echo 'Aucun membre à supprimer.';
					}
					else{
						echo'<form action="" method="post">
						user to delete : <select name="user">';
						
						// on alimente le menu déroulant avec les login des différents membres du site
						$sql->execute();
						$results=$sql->fetchAll();
						
						foreach($results as $row) {
							echo '<option value="' , $row->user_id , '">' , stripslashes(htmlentities(trim($row->user_name))) , '</option>';
						}
						echo'
						</select><br />
						<input type="submit" name="go" value="Send">
						</form>';
						if ($_SERVER['REQUEST_METHOD'] == 'POST'){
							$del=$con->prepare('delete from users where user_id=:usertodel');
							$del->bindParam(':usertodel', $_POST['user'], PDO::PARAM_STR);
							$del->execute();
							echo'User successfully deleted';
							header('Location: admin.php');
							sleep(3);
						}
					}
					
					
				}
				
				if(isset($_GET['option']) && $_GET['option']=="modif" && !isset($_GET['id'])){
					
					if(!isset($_POST['user'])){
						$users=$con->prepare('SELECT user_id , user_name from users where user_id <>'.$_SESSION['user_id'].' ORDER BY user_name ASC');
						$users->execute();
						$results=$users->fetchALL();
						echo '<form action="admin.php?option=modif" method="post">
						utilisateur à modifier: <select name="user">';
						foreach($results as $row) {
							echo '<option value="' , $row->user_id , '">' , stripslashes(htmlentities(trim($row->user_name))) , '</option>';
						}
						echo'
						</select><br />
						<input type="submit" name="change" value="Modifier" >
						
						</form>';
						
						
						
					}
					if($_SERVER['REQUEST_METHOD'] =='POST'){
						header('Location: admin.php?option=modif&id='.$_POST['user']);
					}
				}
				if(isset($_GET['option']) && $_GET['option']=="modif" && isset($_GET['id'])){
					$sql=$con->prepare('SELECT * FROM users where user_id = :id');
					
					$sql->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
					$sql->execute();
					$row = $sql->fetch(PDO::FETCH_ASSOC);
					
					
					
					
					echo'<form method="post" >
					<p>';
					
					
					echo  'Modifier le nom d\'utilisateur : ';
					echo'<br/>
					<input  type="text" name="user_name" size="30" value="';
					echo($row['user_name']);
					echo'"/><br/>						
					Change user level: current is'.htmlentities($row['user_level']).'<br><select name="level">';
					$i=0;
					while($i <= $_SESSION['user_level']) {
						echo '<option value="' , $i , '">' , $i , '</option>';
						$i++;
					}
					echo '</select><br> Modifier le pays : <br>';
					echo'<br/>'. country($row['country']);
					echo' <br/>Modifier le email : ';
					echo'<br/>
					<input  type="text" name="user_email" size="30" value="';
					echo($row['user_email']);
					echo'"/><br/><br/> Modifier la description : <br/>
					<textarea  name="description">'.htmlentities($row['description']).'</textarea><br/><br/>';
					
					echo  'Modifier le mot de passe : <br/>
					<input  type="password" name="user_pass" size="30"  /><br/><br/>';
					echo  'Confirmer le mot de passe : <br/>
					<input  type="password" name="user_pass_check" size="30" /><br/><br/>
					<input type="submit" name="save" value="save"/>
					</p>
					</form>';
					
					if ($_SERVER['REQUEST_METHOD'] == 'POST' )
					{	
						if(isset($_POST['save'])){
							if(checkinfos()==true){
								
								
								try
								{
									$req= 'UPDATE users SET user_name = :login,user_level=:lvl, country =:country, user_email =:email, user_pass =:pass, description=:desc 
									WHERE user_id ="'.$_GET['id'].'"';
									$reqpreparee= $con->prepare($req);
									
									$reqpreparee ->execute(array('login'=>$_POST['user_name'], 
									'country'=>$_POST['country'], 
									'email'=>$_POST['user_email'], 
									'pass'=>sha1($_POST['user_pass']), 
									'desc'=>$_POST['description'], 
									'lvl'=>$_POST['level']
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
							}
						}
					}
				}
			}
			echo'</section> </div>';
			
			
			
			include 'footer.php';
		?>				