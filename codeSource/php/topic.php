<?php
include 'connect.php';
include 'header.php';

$sql=$con->prepare( "SELECT * FROM topics WHERE topic_id =:id");
$sql->bindValue('id', $_GET['id'], PDO::PARAM_INT);
$result=$sql->execute();
$row = $sql->fetch(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] != 'POST')
{   
	if(!$result)
	{
		echo 'Impossible d\'afficher le sujet.';
		print_r($sql->errorInfo());
	}
	else
	{
		if($row == 0)
		{
			echo 'Ce sujet n\'existe pas.';
		}
		else
		{
			//display category data
			
				echo '<h2>Messages dans ' . $row['topic_subject'] .  '</h2>';
				
			
		 
			//do a query for the topics
			$sql=$con->prepare( "SELECT  
						*
					FROM
						posts
					WHERE
						post_topic = :topic");
			 
			$sql->bindValue('topic', $_GET['id'], PDO::PARAM_INT); 
			$result = $sql->execute();
			$row = $sql->fetchColumn(); 
			if(!$result)
			{
				echo 'The posts could not be displayed, please try again later.';
			}
			else
			{
				if($row == 0)
				{
					echo 'Aucune réponse pour le moment.';
				}
				else
				{
					//prepare the table
					echo '<table border="1">
						  <tr>
							<th>Post</th>
							<th>Posted at</th>
							<th>Posted by</th>
						  </tr>'; 
					$sql->execute();
				$results=$sql->fetchAll();
                foreach($results as $row) //affiche tous les messages du topic
					{               
						echo '<tr>';
							echo '<td class="leftpart">';
								echo  htmlentities($row->post_content) ;
								if(isset($_SESSION['signed_in'])){
								if($_SESSION['user_id'] == $row->post_by || $_SESSION['user_level']>0){
									echo' <br> <a class="mod" href="modif.php?id_post='.$row->post_id.'&auth='.$row->post_by.'">Modifier</a>';
									
									
									$_SESSION["modifpost"] = "topic.php?id=".$_GET['id'] ;
								}}
								
							echo '</td>';
							echo '<td class="rightpart">';
								echo date('d-m-Y', strtotime($row->post_date));
							echo '</td>';
			
							echo '<td class="rightpart">';		
							$user=$con->prepare("SELECT user_name from users where user_id =:by ");
							$user->bindValue('by', $row->post_by, PDO::PARAM_INT); 
							$user->execute();
							$username=$user->fetch();
							echo $username->user_name;							
							
							echo '</td>';
							
						echo '</tr>';
					}
					
				}
			}
		}
		//affiche l'ajout de commentair en bas
		echo ' </table> <br><form method="post" action="">
		 
			Message: <textarea name="reply" /></textarea>
				<br>
				<input type="submit" value="Répondre" />
			 </form>';
			
	}
}
else{
	if(!isset($_SESSION['signed_in']) or $_SESSION['signed_in'] == false)
	{
		echo 'Vous devez être <a href="signin.php">connecté</a> pour répondre au sujet.';
	}
	else if (isset($_SESSION['signed_in']) && $_SESSION['signed_in']){ //peut poster si connecté
	$con->beginTransaction();
	$time=new DateTime();
	$time=$time->format('y-m-d-h-i-s');
	$topicid=$_GET['id'];
	$data = [
	'topicid'=>$topicid,
    'reply'=>$_POST['reply'],
	'userid'=>$_SESSION['user_id'],
	'time'=>$time
	];
	//sauvegarde le post dans la table
            $sql =$con->prepare( "INSERT INTO 
                        posts(post_content,
                               post_date,
                               post_topic,
                               post_by)
                   VALUES(:reply,
                               :time,
                               :topicid,
                               :userid
                               )");
                      
			$result=$sql->execute($data);
         
            if(!$result)
            {
                echo 'An error occured while inserting your data. Please try again later.';
                $con->rollBack();
             
            }
            
			else
			{
				
			
				 $con->commit();
				echo 'You have successfully posted your reply. <a href="topic.php?id='.$topicid.'"> Go back.</a>';
			}
	}
}
 
include 'footer.php';
?>