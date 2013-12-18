<?php
	if(session_id() == "") session_start(); // Vérification de l'existance de session
	header('Content-Type: text/html; charset=utf-8');	// Encodage UTF-8 PHP
	include("include/connexion.php");
	include("include/fonction.php");
	
	$extensionsAutorise = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/x-png")
		|| ($_FILES["file"]["type"] == "image/png"))
		//&& ($_FILES["file"]["size"] < 20000)
		&& in_array($extension, $extensionsAutorise)){
	
		// Vérification de la création du fichier temporaire
		if ($_FILES["file"]["error"] > 0){
			$msg = "Error: " . $_FILES["file"]["error"] . "<br>";
		} else {
			  
			// Récupération des données
			$nom = $_POST["nom"];
			$description = htmlspecialchars($_POST["description"]);
			$tags = htmlspecialchars($_POST["tags"]);
			$idCategorie = $_POST["formIdCategorie"];
			$pattern_nom = '/^[a-zA-Z0-9 áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]*$/';
			$nom = htmlspecialchars($nom);
	
			// Vérification du format des éléments du formulaire
			if( preg_match($pattern_nom,$nom) ){
				
				try
				{
					$sql = "SELECT * FROM categorie WHERE idcategorie = :idCategorie";
					
					// Début de la transaction
					$pdo->beginTransaction();
					$stm = $pdo->prepare($sql);
					$stm->execute(array(":idCategorie" => $idCategorie));
					$rowCategorie = $stm->fetch(PDO::FETCH_ASSOC);
					
					// Enregistrement du fichier
					$nb = 1;
					$fichier = "";
					$fichierFinal = $_FILES["file"]["name"];
					if (file_exists(dirname(__FILE__)."/utilisateurs/".$_SESSION['utilisateur']."/".format_dossier($rowCategorie['nom'])."/".$_FILES["file"]["name"]))
					{
						$temp = explode(".", $_FILES["file"]["name"]);
						$nbPart = count($temp);
						$fichier = $temp[0];
						for( $i = 1 ; $i < $nbPart-1 ; ++$i){
							$fichier = $fichier.".".$temp[$i];
						}
						$fichierFinal = $fichier."(".$nb.").".$extension;
						while( file_exists(dirname(__FILE__)."/utilisateurs/".$_SESSION['utilisateur']."/".$rowCategorie['nom']."/".$fichierFinal) ){
							++$nb;
							$fichierFinal = $fichier."(".$nb.").".$extension;
						}
					}
					
					$fichierFinal = iconv('UTF-8', 'CP1252', $fichierFinal); // Encodage pour le fichier
					$cheminTotal = dirname(__FILE__).'/utilisateurs/'.$_SESSION["utilisateur"].'/'.format_dossier($rowCategorie["nom"]).'/'.$fichierFinal;
					// Vérification de l'intégration de l'image dans notre système de fichier
					if ( move_uploaded_file($_FILES["file"]["tmp_name"],$cheminTotal)){
						// Création de l'image dans la base
						$fichierFinal = iconv('CP1252','UTF-8', $fichierFinal); // Retour encodage pour la base
						
						// Récupérer l'image précédente pour avoir l'ordre de l'image
						$sql = "SELECT * FROM image WHERE idcategorie = :idCategorie ORDER BY ordre DESC";
						$stm = $pdo->prepare($sql);
						$stm->execute(array("idCategorie" => $idCategorie));
						$rowImage = $stm->fetch(PDO::FETCH_ASSOC);

						if ( $rowImage["ordre"] == ""){
							$ordre = 1;
						} else {
							$ordre = intval($rowImage["ordre"])+1;
						}
						
						$sql = "INSERT INTO image (nom,description,lien,ordre,idcategorie) VALUES (:nom,:description,:lien,:ordre,:idCategorie)";
						$stm = $pdo->prepare($sql);
						$stm->execute(array(":nom" => $nom, ":description" => $description, ":lien" => $fichierFinal, ":ordre" => $ordre, "idCategorie" => $idCategorie));
						
						// Insertion des tags dans la base
						$listTags = explode(" ", $tags);
						$idImage = $pdo->lastInsertId();
						for($i = 0; $i < count($listTags); ++$i){
							if ( $listTags[$i] != " "){
								$sql = "INSERT INTO tag (libelle,idimage) VALUES (:libelle,:idimage)";
								$stm = $pdo->prepare($sql);
								$stm->execute(array(":libelle" => $listTags[$i], ":idimage" => $idImage));
							}
						}
						
						// Validation de la dernière transaction
						$pdo->commit();
					
						$msg .= "ok";
						
					} else {
						 $msg = "L'image n'est pas enregistrée !";
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
				$msg =" Le format du nom ne correspond pas aux exigences des administrateurs !";
			}
			
		}
	} else {
		$msg =" Le format du fichier n'est pas une image qui correspond aux exigences des administrateurs !";
	}
	include("include/deconnexion.php");
	
	echo json_encode(array('msg' => $msg));
?>