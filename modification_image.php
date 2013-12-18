<?php
	if(session_id() == "") session_start(); // Vérification de l'existance de session
	header('Content-Type: text/html; charset=utf-8');	// Encodage UTF-8 PHP
	include("include/connexion.php");
	include("include/fonction.php");
	
	// Récupération des données
	$nom = $_POST["nom"];
	$description = htmlspecialchars($_POST["description"]);
	$idCategorie = $_POST["formIdCategorie"];
	$idImage = $_POST["formIdImage"];
	$tags = htmlspecialchars($_POST["tags"]);
	$pattern_nom = '/^[a-zA-Z0-9 áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]*$/';
	$nom = htmlspecialchars($nom);

	// Vérification du format des éléments du formulaire
	if( preg_match($pattern_nom,$nom) ){
		
		try
		{
			// Début de la transaction
			$pdo->beginTransaction();
			
			// Comparaison des tags avec la base
			$listTags = explode(" ", $tags);
			
			$sql = "SELECT * FROM tag WHERE idimage = :idimage";
			$stm = $pdo->prepare($sql);
			$stm->execute(array("idimage" => $idImage));
			while ($rowTag = $stm->fetch(PDO::FETCH_ASSOC)){
				// Vérification de l'éxistance du tags dans la liste
				if ( in_array($rowTag["libelle"], $listTags) ){
					$nb = array_search($rowTag["libelle"], $listTags);
					array_splice($listTags, $nb,$nb+1);
				// Suppression de l'ancien tag
				} else {
					$sql = "DELETE FROM tag WHERE idtag = :idtag";
					$stm = $pdo->prepare($sql);
					$stm->execute(array("idtag" => $rowTag["idtag"]));
				}
			}
			
			// Création des nouveaux tags
			for($i = 0; $i < count($listTags); ++$i){
				if ( $listTags[$i] != " "){
					$sql = "INSERT INTO tag (libelle,idimage) VALUES (:libelle,:idimage)";
					$stm = $pdo->prepare($sql);
					$stm->execute(array(":libelle" => $listTags[$i], ":idimage" => $idImage));
				}
			}
			
			
			// Récupération des informations sur l'image
			$sql = "SELECT * FROM image WHERE idimage = :idimage";
			$stm = $pdo->prepare($sql);
			$stm->execute(array("idimage" => $idImage));
			$rowImage = $stm->fetch(PDO::FETCH_ASSOC);
			
			// Récupération des informations sur la catégorie
			$sql = "SELECT * FROM categorie WHERE idcategorie = :idcategorie";
			$stm = $pdo->prepare($sql);
			$stm->execute(array("idcategorie" => $idImage));
			$rowCategorie = $stm->fetch(PDO::FETCH_ASSOC);
			
			// Modification du nom
			if ( $nom != $rowImage["nom"]){
				$sql = "UPDATE image SET nom = :nom WHERE idimage = :idimage";
				$stm = $pdo->prepare($sql);
				$stm->execute(array(":idimage" => $idImage, ":nom" => $nom));
			}
			
			// Modification de la description
			if ( $description != $rowImage["description"]){
				$sql = "UPDATE image SET description = :description WHERE idimage = :idimage";
				$stm = $pdo->prepare($sql);
				$stm->execute(array(":idimage" => $idImage, ":description" => $description));
			}
			
		
			// Validation de la transaction
			$pdo->commit();
		
			$msg = "ok";
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
		$msg =" Le format du nom ne correspond pas aux exigences des administrateurs !";
	}
			
	include("include/deconnexion.php");
	
	echo json_encode(array('msg' => $msg));
?>