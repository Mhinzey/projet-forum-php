<?php
	
	include 'connect.php';
	include 'header.php';
	
	$sql =$con->prepare("SELECT * FROM categories WHERE cat_id = :cat");
	$sql->bindValue('cat', $_GET['id'], PDO::PARAM_INT);
	$result=$sql->execute();
	$row = $sql->fetchColumn();
	
	
	if(!$sql)
	{
		echo 'Impossible d\'afficher les catégories.' ;
		print_r($sql->errorInfo());
	}
	else
	{
		if($row == 0)
		{
			echo 'Aucune catégorie à afficher.';
		}
		else
		{	//obtient toutesles infos des topics correspondant à la catégorie
			
			$sql =$con->prepare( "SELECT  
			topic_id,
			topic_subject,
			topic_date,
			topic_cat,
			topic_by
			FROM
			topics
			WHERE
			topic_cat = :id");
			$sql->bindValue('id', $_GET['id'], PDO::PARAM_INT); 
			$result = $sql->execute();
			
			
			if(!$result)
			{
				echo 'Impossible d\'afficher les catégories.';
			}
			else
			{
				if($sql->fetchColumn() == 0)
				{
					echo 'Aucune catégorie à afficher.';
				}
				else
				{ //affiche le tout dans le tableau
					//prepare the table
					echo '<table border="1">
					<tr>
					<th>Topic</th>
					<th>Created at</th>
					<th>Started by</th>
					</tr>'; 
					$sql->execute();
					$results=$sql->fetchAll();
					foreach($results as $row)
					{               
						echo '<tr>';
						echo '<td class="leftpart">';
						echo '<h3><a href="topic.php?id=' . $row->topic_id . '">' . htmlentities($row->topic_subject) . '</a><h3>';
						
						echo '</td>';
						echo '<td class="rightpart">';
						echo date('d-m-Y', strtotime($row->topic_date));
						echo '</td>';
						echo '<td class="rightpart">';
						
						$user=$con->prepare("SELECT user_name from users where user_id =:by ");
						$user->bindValue('by', $row->topic_by, PDO::PARAM_INT); 
						$user->execute();
						$username=$user->fetch();
						echo $username->user_name;
						
						echo '</td>';
						echo '</tr>';
					}
					echo'</table>';
				}
			}
		}
	}
	
	include 'footer.php';
?>				