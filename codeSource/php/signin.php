<?php
	//signin.php
	include 'connect.php';
	include 'header.php';
	
	echo '<h3>Sign in</h3>';
	
	
	if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
	{
		echo 'Déjà connecté';
	}
	else
	{
		if($_SERVER['REQUEST_METHOD'] != 'POST')
		{
			/*the form hasn't been posted yet, display it
			note that the action="" will cause the form to post to the same page it is on */
			echo '<form method="post" action="">
            Pseudo: <input type="text" name="user_name" />
            mot de passe: <input type="password" name="user_pass">
            <input type="submit" value="Se conncter" />
			</form>';
			
			
		}
		else 
		{
			/* Envoi de la connection -> check des données
			*/
			$errors = array(); /* declare the array for later use */
			
			if(!isset($_POST['user_name']))
			{
				$errors[] = 'The username field must not be empty.';
			}
			
			if(!isset($_POST['user_pass']))
			{
				$errors[] = 'The password field must not be empty.';
			}
			
			if(!empty($errors)) //affiche les erreurs
			{
				echo 'Uh-oh.. a couple of fields are not filled in correctly..';
				echo '<ul>';
				foreach($errors as $key => $value)
				{
					echo '<li>' . $value . '</li>'; 
				}
				echo '</ul>';
			}
			else
			{
				
				//pas d'erreur, prepare de la requête qui vérifie si les infos se troivent dans la db
				$param=array(
				'pseudo' => $_POST['user_name'],
				'pass' => sha1($_POST['user_pass']));
				$sql = $con->prepare("SELECT 
				user_id,
				user_name,
				user_level
				FROM
				users
				WHERE
				user_name = :pseudo  AND
				user_pass = :pass");
				$result=$sql->execute($param);
				
				if(!$result)
				{
					
					echo 'Erreur, pas de résultat';
					
				}
				else
				{
					//pas de correspondance -> mauvais mdp/user
					if(($sql->fetchColumn()) == 0)
					{
						echo 'Mauvaise combinaison. Reassayez.';
					}
					else
					{
						//pose les variables de session
						$_SESSION['signed_in'] = true;
						
						
						$sql->execute($param);
						$results=$sql->fetchAll();
						foreach($results as $row)
						{
							$_SESSION['user_id']    = $row->user_id;
							$_SESSION['user_name']  = $row->user_name;
							$_SESSION['user_level'] = $row->user_level;
						}
						
						echo 'Bienvenue, ' . $_SESSION['user_name'] . '. <a href="../index.php">Voir le forum</a>.';
					}
				}
			}
		}
	}
	
	include 'footer.php';
?>			