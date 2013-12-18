<?php
	if(session_id() == "") session_start(); // Vérification de l'existance de session
	header('Content-Type: text/html; charset=utf-8');	// Encodage UTF-8 PHP
	include("include/connexion.php");
	include("include/fonction.php");
	
	$idCategorie = intval(htmlspecialchars($_POST["idCategorie"]));
	$nomCategorie = htmlspecialchars($_POST["nomCategorie"]);
	
	try
	{
		// Début de la transaction
		$pdo->beginTransaction();
		
		// Récupération de l'ancien nom
		$sql = "SELECT nom FROM categorie WHERE idcategorie = :idcategorie";
		$stm = $pdo->prepare($sql);
		$stm->execute(array(":idcategorie" => $idCategorie));
		$row = $stm->fetch(PDO::FETCH_ASSOC);
		$ancienNom = $row["nom"];
		
		// Mise à jours du nom dans la base 
		$sql = "UPDATE categorie SET nom = :nom WHERE idcategorie = :idcategorie" ;
		$stm = $pdo->prepare($sql);
		$stm->execute(array(":idcategorie" => $idCategorie, ":nom" => $nomCategorie ));
	
		// Validation de la transaction
		$pdo->commit();
	
		// Formatage des noms de dossier
		$ancienNom = format_dossier($ancienNom);
    	$nomCategorie = format_dossier($nomCategorie);
		if ( !rename(dirname(__FILE__)."/utilisateurs/".$_SESSION['utilisateur']."/".$ancienNom, dirname(__FILE__)."/utilisateurs/".$_SESSION['utilisateur']."/".$nomCategorie)){
			$msg = "Le dossier n'a pas changé de nom !";
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