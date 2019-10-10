<?php
//create_cat.php
include 'connect.php';
include 'header.php';
 
echo '<h2>Create a topic</h2>';
if(!isset($_SESSION['signed_in']) or $_SESSION['signed_in'] == false)
{
    
    echo 'Sorry, you have to be <a href="signin.php">signed in</a> to create a topic.';
}
else
{
    //the user is signed in
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {   
        //selectionne les catégories pour les afficher dans le menu déroulant
        $sql = $con->prepare("SELECT *
                FROM
                    categories");
         
        $result = $sql->execute();
         
        if(!$result)
        {
           
            echo 'Error';
        }
        else
        {
            if(($sql->fetchAll(PDO::FETCH_COLUMN)) == 0) //si pas de catégories on affiches les messages
            {
                
                if($_SESSION['user_level'] == 1)
                {
                    echo 'You have not created categories yet.';
                }
                else
                {
                    echo 'Before you can post a topic, you must wait for an admin to create some categories.';
                }
            }
            else //si on a des catégories, les affiches dans un menu déroulant + le reste 
            {
         
                echo '<form method="post" action="">
                    Subject: <input type="text" name="topic_subject" />
                    Category:'; 
                 
                echo '<select name="topic_cat">';
				$sql->execute();
				$results=$sql->fetchAll();
                foreach($results as $row)
                    {
                        echo '<option value="' . $row->cat_id . '">' . htmlentities($row->cat_name) . '</option>';
                    }
                echo '</select>'; 
                     
                echo 'Message: <textarea name="post_content" /></textarea>
                    <input type="submit" value="Create topic" />
                 </form>';
            }
        }
    }
    else //on commence l'insert
    {
        $result=$con->beginTransaction(); 
         
        if(!$result)
        {
            
            echo 'An error occured while creating your topic. Please try again later.';
        }
        else
        {
     
            //insert d'abord le topic dans la table de topics
			$time=new DateTime();
			$time=$time->format('y-m-d-h-i-s');
			$data = [
			'subject'=>$_POST['topic_subject'],
			'cat'=>$_POST['topic_cat'],
			'date'=>$time,
			'user'=>$_SESSION['user_id'],
			
			];
            $sql = $con->prepare("INSERT INTO 
                        topics(topic_subject,
                               topic_date,
                               topic_cat,
                               topic_by)
                   VALUES(:subject , :date , :cat , :user)");
                      
            $result = $sql->execute($data);
            if(!$result)
            {
                //something went wrong, display the error
                echo 'An error occured while inserting your data. Please try again later.' ;
				$con->rollBack();
             
            }
            else
            {
                //insert le message sujet du topic dans la table de post
                $topicid = $con->lastInsertId();
               
				$data = [
				'topicid'=>$topicid,
				'content'=>$_POST['post_content'],
				'userid'=>$_SESSION['user_id'],
				'time'=>$time 
				];
                 $sql =$con->prepare( "INSERT INTO 
                        posts(post_content,
                               post_date,
                               post_topic,
                               post_by)
                   VALUES(:content,
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
    }
}
 
include 'footer.php';
?>