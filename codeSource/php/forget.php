<?php
include 'connect.php';
include 'header.php';

if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
    header('Location: ../index.php');
}

else
{
	
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        /*the form hasn't been posted yet, display it
          note that the action="" will cause the form to post to the same page it is on */
        echo ' Confirmez pseudo et email pour continuer<br>
		<form method="post" action="">
            Username: <input type="text" name="user_name" />
            email: <input type="email" name="email">
            <input type="submit" name="reset" value="reset password" />
         </form>';
		 
		 
    }
	else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset'] )){ //teste les champs
		$errors = array(); 
        
        if(!isset($_POST['user_name']) ||empty($_POST['user_name']))
        {
			echo'cc';
            $errors[] = 'The username field must not be empty.';
        }
         
        if(!isset($_POST['email']) || empty($_POST['user_name']))
        {
            $errors[] = 'The email field must not be empty.';
        }
         
        if(!empty($errors)) 
        {
            echo 'Uh-oh.. a couple of fields are not filled in correctly..';
            echo '<ul>';
            foreach($errors as $key => $value) 
            {
                echo '<li>' . $value . '</li>';  //génère les erreurs
            }
            echo '</ul>';
        }
		else{  //cherche une correspondance entre les infos entrées par le user et la table users
				$param=array(
				'pseudo' => $_POST['user_name'],
				'email' => $_POST['email']);
            $sql = $con->prepare("SELECT 
                        user_id
                        
                        
                    FROM
                        users
                    WHERE
                        user_name = :pseudo  AND
                        user_email = :email");
			$sql->execute($param);
			$result = $sql->fetch(PDO::FETCH_ASSOC);
			
            if(!$result )
            {
                echo 'Pas de correspondance.';
            }
			else{
				
				

				$_SESSION['process'] = $result['user_id'];  //associe l'id récupérée à une variable pour l'utiliser après
				echo ' Entrez un nouveau mot de passe<br>
		<form method="post" action="">
           <label>Mot de passe: <input type="password" name="user_pass"/></label><br/>
		<label>Confirmation du mot de passe: <input type="password" name="user_pass_check"/></label><br/>
		
            <input type="submit" name="save" value="enregistrer" />
	</form>';}}}
		 else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'] )){  //propose de reset le mot de passe (en réassigner un)
			 print_r($_POST['user_pass'],$_POST['user_pass_check']);
			 
			 if($_POST['user_pass'] != $_POST['user_pass_check'])
			{
				$errors[] = 'The two passwords did not match.';
			}
			if(!isset($_POST['user_pass']) || !isset($_POST['user_pass_check'])){
				$errors[]= 'les champs doivent êtres remplis';
			}
			if(!empty($errors)) 
			{
				echo 'Uh-oh.. a couple of fields are not filled in correctly..';
				echo '<ul>';
				foreach($errors as $key => $value) 
				{
					echo '<li>' . $value . '</li>'; 
				}
				echo '</ul>';
				
			}
			else{
				//modifie l'ancien mdp par le nouveau
			$res=$con->prepare('UPDATE users SET user_pass=:new where user_id=:id');
			$array=array('new' => sha1($_POST['user_pass']), 'id' => $_SESSION['process']);
			$res->execute($array);
			unset ($_SESSION["process"]);
			echo'mot de passe enregistré,<a href="signin.php"> connectez vous!</a>'; 
			}
			}
		}
	



include 'footer.php';

?>