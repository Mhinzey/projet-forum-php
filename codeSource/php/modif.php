<?php
include 'connect.php';
include 'header.php';

if (!isset($_SESSION['signed_in']) ) {
	
	header ('Location: ../index.php');
	exit();
}
if (!isset($_GET['id_post']) || empty($_GET['id_post'])) {
	echo 'Le poste n\'existe pas.';
}
if($_SESSION['user_id'] == $_GET['auth'] || $_SESSION['user_level'] >0 ){
	
	$verif=$con->prepare("select post_topic, post_by, post_content from posts where post_id=".$_GET['id_post']);
	$verif->execute();
	$result=$verif->fetch(PDO::FETCH_ASSOC);
	
	if($result['post_by']== $_SESSION['user_id'] || $_SESSION['user_level']>0){ //autorise la modif du poste si on est l'auteur ou modérateur
		$id=$_GET['id_post'];
		echo ' </table> <br><form method="post" action="">
		 
			Message: <textarea name="reply" >'.htmlentities($result['post_content']).'</textarea>
				<br>
				<input type="submit" name="ok" value="Répondre" />
				<input type="submit" name="del" value="supprimer" />
			 </form>
			 </table>';
			 
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(isset($_POST['ok'])){
			$update=$con->prepare('update posts set post_content=:rep where post_id='.$id);
			try{
			$update->execute(array('rep'=>$_POST['reply']));
			}
			catch(Exception $e){
			header('Location: topic.php?id='.$result['post_topic']);}
		}
			else if (isset($_POST['del'])){
				$delete=$con->prepare('delete from posts where post_id='.$id);
				$delete->execute();
			
			header('Location: topic.php?id='.$result['post_topic']);
				}
				}
		}
	else{
		echo'Vous ne pouvez pas modifier ce post';
		}
}

include 'footer.php';
?>