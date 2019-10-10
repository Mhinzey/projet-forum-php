<!DOCTYPE html >
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Forum de discussion." />
    <meta name="keywords" content="put, keywords, here" />
    <title>PHP-MySQL forum</title>
	<?php
	include'function.php';
	if(isset($page) && $page=="index"){
		echo'<link rel="stylesheet" href="css/style.css" type="text/css">';
		$hrefindex="index.php";
		$hreflogout="php/logout.php";
		$hreflogin="php/signin.php";
		$hrefsignup="php/signup.php";
		$hrefeditprof="php/editprof.php";
		$hreftopic="php/create_topic.php";
		$hrefcat="php/create_cat.php";
		$hrefmember="php/members_list.php";
		$hrefadminpanel="php/admin.php";
		$hrefmsg="php/messagerie.php";
		$hrefforget="php/forget.php";
		
	}
	else{
		echo'<link rel="stylesheet" href="../css/style.css" type="text/css">';
		$hrefindex="../index.php";
		$hreflogout="logout.php";
		$hreflogin="signin.php";
		$hrefsignup="signup.php";
		$hrefeditprof="editprof.php";
		$hreftopic="create_topic.php";
		$hrefcat="create_cat.php";
		$hrefmember="members_list.php";
		$hrefadminpanel="admin.php";
		$hrefmsg="messagerie.php";
		$hrefforget="forget.php";
	}
	?>
</head>
<body>
<h1>Forum de discussion</h1>
    <div id="wrapper">
    <div id="menu">
		<?php
			echo'<a class="item" href="'.$hrefindex.'">Acceuil</a> ';
			echo'-<a class="item" href="'.$hreftopic.'">Créer un sujet</a> ';
			echo'-<a class="item" href="'.$hrefmember.'">Liste des membres</a>';
			echo'-<a class="item" href="'.$hrefmsg.'">Messagerie</a>';
		?>   
        
		
        <?php
			include_once('function.php');
			session_start();
			echo'<div id="userbar">';
				if (isset($_SESSION['signed_in']) && $_SESSION['signed_in']) //affiche les options de déconnection, modfier profil si on est connecté
				{
					
					echo 'Bonjour, ' . $_SESSION['user_name'] . '. Ce n\'est pas vous? <a href="'.$hreflogout.'">se déconnecter</a> <br> <a href="'.$hrefeditprof.'">Modifier votre profile</a>' ;
					if($_SESSION['user_level']==1){//si c'est l'admin, ajoute le lien vers le panel admin
					echo'   <a href="'.$hrefadminpanel.'">Admin panel</a>';
					}
				}
				else // si on est pas connecté, affiche les options de connexion, créer un compte et reset mdp
				{
					echo '<a href="'.$hreflogin.'">Se connecter</a> ou <a href="'.$hrefsignup.'">créer un compte</a> or <a href="'.$hrefforget.'">Mot de passe oublié ? ?</a>';
				}
			echo'</div> </div>';
		?> 
        <div id="content">