<?php
include 'connect.php';
include 'header.php';

if(!isset($_SESSION['signed_in']) or $_SESSION['signed_in'] == false){
	echo'Vous devez être connecté pour voir la liste des membres';
}
else{ //affiche les infos des membres dans un tableau
	$sql=$con->query( 'SELECT user_name, profil_pic, user_date, description, country FROM users');
	
	$result = $sql->execute();
	

 
	if(!$result)
	{
		echo 'Aucun membre à afficher.' ;
	}
	else	
	{
		if(($sql->fetchAll(PDO::FETCH_COLUMN)) == 0)
		{
			echo 'No members.';
		}
		else
		{
			echo '<table border="1">
                      <tr>
                        <th>Membres</th>
                        
                      </tr>'; 
			$sql->execute();
			$results=$sql->fetchAll();
			foreach($results as $row)
			{
				echo '<tr>';
				echo '<td class="leftpart">';
				echo ' Pseudo: '. $row->user_name .'  |  membre depuis: ' . $row->user_date .'  |   description: '. $row->description .'  |   Pays: '. $row->country . '';
				echo '</td>';
				
			echo '</tr>';
			}
			echo'</table>';
       
		}

}}


include 'footer.php';
?>