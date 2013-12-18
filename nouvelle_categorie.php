<?php
	if(session_id() == "") session_start(); // Vérification de l'existance de session
	header('Content-Type: text/html; charset=utf-8');	// Encodage UTF-8 PHP
	include("include/connexion.php");
	include("include/fonction.php");
	
	$nomCategorie = $_POST["nomCategorie"];
	$pattern_nomCategorie = '/^[a-zA-Z0-9 áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]*$/';
 
    if( preg_match($pattern_nomCategorie,$nomCategorie) ){
    	// Protection contre les erreurs
    	$nomCategorie = htmlspecialchars($nomCategorie);
		
		try
		{
			$sql = "INSERT INTO categorie (nom,idutilisateur) VALUES (:nomCategorie,:idutilisateur)";
			
			// Début de la transaction
			$pdo->beginTransaction();
			$stm = $pdo->prepare($sql);
            $stm->execute(array(":nomCategorie" => $nomCategorie, ":idutilisateur" => $_SESSION['idutilisateur'] ));
            
			// Validation de la transaction
			$pdo->commit();
		
			// Formatage du nom de dossier
    		$nomCategorie = format_dossier($nomCategorie);
			if ( !mkdir(dirname(__FILE__)."/utilisateurs/".$_SESSION['utilisateur']."/".$nomCategorie,0700)){
				$msg = "Le dossier ne s'est pas créé !";
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
			$msg =" Il y a eu un problème lors de l'insertion en base !";
		}

    } else {
    	$msg =" Le format ne correspond pas aux exigences des administrateurs !";
    }

	include("include/deconnexion.php");
	
	echo json_encode(array('msg' => $msg));
?>