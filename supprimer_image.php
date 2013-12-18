<?php
	if(session_id() == "") session_start(); // Vérification de l'existance de session
	header('Content-Type: text/html; charset=utf-8');	// Encodage UTF-8 PHP
	include("include/connexion.php");
	include("include/fonction.php");
	
	$idImage = intval(htmlspecialchars($_POST["idImage"]));
	
	try
	{
		$sql = "SELECT * FROM image WHERE idimage = :idimage" ;
	
		// Début de la transaction
		$pdo->beginTransaction();
		$stm = $pdo->prepare($sql);
		$stm->execute(array(":idimage" => $idImage ));
		$rowImage = $stm->fetch(PDO::FETCH_ASSOC);
		$nomImage = $rowImage["lien"];
		$idCategorie = $rowImage["idcategorie"];
		
		// Récuperation du nom de dossier
		$sql = "SELECT * FROM categorie WHERE idcategorie = :idcategorie" ;
		$stm = $pdo->prepare($sql);
		$stm->execute(array(":idcategorie" => $idCategorie ));
		$rowImage = $stm->fetch(PDO::FETCH_ASSOC);
		$nomCategorie = format_dossier($rowImage["nom"]);
		
		// Suppression des tags de l'image en base 
		$sql = "DELETE FROM tag WHERE idimage = :idimage" ;
		$stm = $pdo->prepare($sql);
		$stm->execute(array(":idimage" => $idImage ));
		
		// Suppression de l'image en base 
		$sql = "DELETE FROM image WHERE idimage = :idimage" ;
		$stm = $pdo->prepare($sql);
		$stm->execute(array(":idimage" => $idImage ));
	
		// Validation de la transaction
		$pdo->commit();
	
		// Suppresion de l'image en vrai
		$supprimerImage = dirname(__FILE__)."/utilisateurs/".$_SESSION['utilisateur']."/".$nomCategorie."/".$nomImage;
		
		if ( !unlink($supprimerImage)){
			$msg = "L'image ne s'est pas supprimée !".$supprimerImage;
		} else {
			$msg = "ok";
		}
	}
	catch(Exception $e) //en cas d'erreur
	{
		// Annulation de la transaction
		$pdo->rollback();
	
		echo 'Erreur : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
		$msg =" Il y a eu un problème lors de la suppresion en base !";
	}

  
	include("include/deconnexion.php");
	
	echo json_encode(array('msg' => $msg));
?>