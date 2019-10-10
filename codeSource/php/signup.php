<?php
	//signup.php
	include 'connect.php';
	include 'header.php';
	
	
	
	echo '<h3>Sign up</h3>';
	
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
	?>
	<form method="post">
		<label>Pseudo: <input type="text" name="user_name"/></label><br/>
		<label>Adresse e-mail: <input type="email" name="user_email"/></label><br/>
		<label>Description: <textarea name="description"></textarea></label><br/>
		<label>Mot de passe: <input type="password" name="user_pass"/></label><br/>
		<label>Confirmation du mot de passe: <input type="password" name="user_pass_check"/></label><br/>
		
		
		
		
		<?php
			country();
			echo'    <input type="submit" value="register" />
			</form>';
		}
		else
		{
			
			
			if(checkinfos()==true)
			{
				
				$time=new DateTime();
				$time=$time->format('y-m-d-h-i-s');
				$param=array( 
				'username' => $_POST['user_name'] ,
				'pass' => sha1($_POST['user_pass']) ,
				'date' => $time,
				'mail' => $_POST['user_email'],
				'country' => $_POST['country'],
				'desc' => $_POST['description']
				
				);
				
				$sql = $con->prepare("INSERT INTO
				users(user_name, user_pass, user_email ,user_date, description, country)
                VALUES(:username , :pass , :mail, :date, :desc , :country)");
				$result=false;
				try{                 
				$result=$sql->execute($param);}
				catch (exception $e) {
					
				}
				if(!$result)
				{
					
					echo 'Erreur lors de l\'insertion, réassayez.';
					// echo mysqli_error($con); //debugging purposes, uncomment when needed
				}
				else
				{
					echo 'Vous êtes maintenant nregistré. Vous pouvez <a href="signin.php">vous connecter</a> et commencer à poster! :-)';
				}
			}
		}
		
		include 'footer.php';
	?>	