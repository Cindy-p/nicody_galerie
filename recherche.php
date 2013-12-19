<?php
	if(session_id() == "") session_start(); // Vérification de l'existance de session
	if (!isset($_SESSION["utilisateur"]) && !isset($page["no_redirect"])) header("Location: authentification.php"); // Redirection si l'utilisateur ne s'est pas identifié
    include("include/connexion.php");
    include("include/fonction.php");
			
    $recherche = htmlspecialchars($_GET["recherche"]);
    $listImage = array();
    
	// Récupération des images
	$sql = "SELECT i.nom AS nom, i.description AS description, i.idimage AS idimage, i.lien AS lien FROM image as i LEFT JOIN tag AS t ON i.idimage = t.idimage WHERE ( description LIKE :recherche OR lien LIKE :recherche OR t.libelle LIKE :recherche OR nom LIKE :recherche ) GROUP BY idimage ORDER BY ordre ASC" ;
	$stmImage = $pdo->prepare($sql);
	$stmImage->execute(array(":recherche" => "%".$recherche."%" ));
	
	while( $image = $stmImage->fetch(PDO::FETCH_ASSOC) ){
		array_push($listImage, $image["nom"]);
	}
							
	include("include/deconnexion.php");
	
	echo json_encode($listImage);
?>