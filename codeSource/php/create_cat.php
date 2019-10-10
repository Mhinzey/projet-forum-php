<?php

include 'connect.php';
include 'header.php';
 
echo '<h2>Créer une categorie</h2>';
if(!isset($_SESSION['signed_in']) or $_SESSION['signed_in'] == false  )
{
    
    echo 'Sorry, you have to be <a href="signin.php">signed in</a> to create a topic.';
}
else if( $_SESSION['user_level'] <1){ //pas admin ->pas le droit
	echo 'Sorry you don\'t have the permission to create a new category';
}
else
{
    
    if($_SERVER['REQUEST_METHOD'] != 'POST') //affiche les input
    {   
       
         
		echo '<form method="post" action="">
			nom de categorie : <input type="text" name="cat_name" />
			<br>'; 
		 
		
			 
		echo 'Message: <textarea name="cat_description" /></textarea>
			<br>
			<input type="submit" value="Create category" />
		 </form>';
	
    }
    else
    {
        //start the transaction
   
        $result=$con->beginTransaction(); 
        if(!$result)
        {
            
            echo 'An error occured while creating your topic. Please try again later.';
        }
        else
        {
			$data = [
			'catname'=>$_POST['cat_name'],
			'catdes'=>$_POST['cat_description']
			
			];
            //débute l'insert dans la table categories
            $sql =$con->prepare("INSERT INTO 
                        categories(cat_name,
                               cat_description)
                   VALUES( :catname , :catdes    )");
                      
            $result = $sql->execute($data);
            if(!$result)
            {
                
                echo 'An error occured while inserting your data. Please try again later.' ;
                $con->rollBack();
            }
            
			else
			{
				$con->commit();
				
				 
				
				echo 'Nouvelle catégorie créée</a>.';
			}
            
        }
    }
}
 
include 'footer.php';
?>