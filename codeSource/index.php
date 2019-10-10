<?php
$page="index";

include 'php/connect.php';
include 'php/header.php';
$sql =$con->prepare( "SELECT *
        FROM
            categories, topics JOIN (select max(topic_date) as date, topic_id as id from topics group by topic_id) as latest on topic_date= latest.date and topics.topic_id=id where topic_cat=cat_id ORDER BY date asc ");
			
			
 
$result =$sql->execute();
 
if(!$result)
{
    echo 'The categories could not be displayed, please try again later.';
}
else
{
    if($sql->rowCount() == 0)
    {
        echo 'No categories defined yet.';
    }
    else
    {
        //prepare the table
        echo '<table border="1">
              <tr>
                <th>Categories</th>
                <th>Dernier sujet</th>
              </tr>'; 
             
        while($row = $sql->fetch(PDO::FETCH_ASSOC))
        {               
            echo '<tr>';
                echo '<td class="leftpart">';
                    echo '<h3><a href="php/category.php?id='.$row['cat_id'].'">' . $row['cat_name'] . '</a></h3>' . $row['cat_description'];
                echo '</td>';
                echo '<td class="rightpart">';
							
                            echo '<a href="php/topic.php?id='.$row['id'].'">'.$row['topic_subject'].'</a> at '. $row['date'];
                echo '</td>';
            echo '</tr>';
        }
		echo'</table>';
    }
}

include 'php/footer.php';
?>
