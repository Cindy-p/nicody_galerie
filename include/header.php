<?php
	if(session_id() == "") session_start(); // Vérification de l'existance de session
	if (!isset($_SESSION["utilisateur"]) && !isset($page["no_redirect"])) header("Location: authentification.php"); // Redirection si l'utilisateur ne s'est pas identifié
	header('Content-Type: text/html; charset=utf-8');	// Encodage UTF-8 PHP
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $page["titre"]; ?></title>
		<link rel="stylesheet" href="css/themes/cupertino/jquery-ui-1.10.3.custom.css">
		<link rel="stylesheet" href="css/style.css"/>
		<link rel="stylesheet" href="css/style_nico.css"/>
		
		<?php
		    if (isset($page["link"])){
                foreach($page["link"] as $css){
                    echo '<link rel="stylesheet" href="'.$css.'"/>';
                }
            }
		?>
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" type="text/css" href="css/style_ie.css"/>
		<![endif]-->
		
		<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.js"></script>
		<script type="text/javascript" src="js/centrer.js"></script>
		<script type="text/javascript" src="js/generer_galerie.jquery.js"></script>
		<script type="text/javascript" src="js/galerie.js"></script>
		
		<?php
		    if (isset($page["script"])){
                foreach($page["script"] as $js){
                    echo '<script type="text/javascript" src="'.$js.'"></script>';
                }
            }
		?>
		
	</head>
	<body>
	    <div id="entete">
			<div id="nomSite">
				<a href="index.php" >
					<img src="img/logo1.png" alt=""/>
				</a>
			</div>
			<ul id="navigation" class="menu_deroulant">
				<li class="lien">
					<a href="index.php">Accueil</a>
				</li>
				<?php
				    if (!isset($page["no_redirect"]) ){
				?>
				<li class="lien">
					<a href="administration.php"><?php echo $_SESSION["utilisateur"]; ?></a>
					<!--<ul id="menuAdministration">
						<li><a href="#">Gestion Utilisateur</a></li>
						<li><a href="#">Gestion Galerie</a></li>
						<li><a href="logout.php">Déconnexion</a></li>
					</ul>-->
				</li>
				<li class="lien">
					<a href="include/logout.php" >
						Déconnexion
						<!-- <img id="logout" src="img/logout.png"/> -->
					</a>
				</li>
				<li>
					<input id="recherche" type="text" placeholder="Recherche..."/>
				</li>
				<?php
                    }
                ?>
			</ul>
		</div>