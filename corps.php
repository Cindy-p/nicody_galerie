<?php
	if(session_id() == "") session_start(); // Vérification de l'existance de session
	if (!isset($_SESSION["utilisateur"]) && !isset($page["no_redirect"])) header("Location: authentification.php"); // Redirection si l'utilisateur ne s'est pas identifié
    include("include/connexion.php");
    include("include/fonction.php");

?>
<div id="corps">
	<div id="galerie">
	<?php
	
		// Recherche de l'existance d'image pour cette utilisateur
		if ( isset($_POST["recherche"])) {
			$sql = "SELECT COUNT(*) FROM categorie AS c, image AS i WHERE c.idcategorie = i.idcategorie AND c.idutilisateur = :idutilisateur AND ( i.nom LIKE :recherche OR i.description LIKE :recherche OR i.lien LIKE :recherche)";
			$stm = $pdo->prepare($sql);
			$stm->execute(array(":idutilisateur" => $_SESSION['idutilisateur'], ":recherche" => "%".$_POST["recherche"]."%" ));
			$recherche = true;
		} else {
			$sql = "SELECT COUNT(*) FROM categorie AS c, image AS i WHERE c.idcategorie = i.idcategorie AND c.idutilisateur = :idutilisateur";
			$stm = $pdo->prepare($sql);
			$stm->execute(array(":idutilisateur" => $_SESSION['idutilisateur']));
			$recherche = false;
		}
		
		$row = $stm->fetch(PDO::FETCH_ASSOC);
		if( $row["COUNT(*)"] == 0 ){
			if ( $recherche ){
				echo "AUCUN RESULTAT POUR CETTE RECHERCHE !";
			} else {
				echo "METS LE MESSAGE QUI TE PLAIT !";
			}
		} else {
			
			if ( isset($_POST["recherche"])) {
				// Création des catégories
				$sql = "SELECT DISTINCT(c.nom) FROM categorie AS c LEFT JOIN image AS i ON c.idcategorie = i.idcategorie WHERE i.nom IS NOT NULL AND idutilisateur = :idutilisateur AND ( i.nom LIKE :recherche OR i.description LIKE :recherche OR i.lien LIKE :recherche)";
				$stm = $pdo->prepare($sql);
				$stm->execute(array(":idutilisateur" => $_SESSION['idutilisateur'], ":recherche" => "%".$_POST["recherche"]."%" ));
			} else {
				// Création des catégories
				$sql = "SELECT DISTINCT(c.nom) FROM categorie AS c LEFT JOIN image AS i ON c.idcategorie = i.idcategorie WHERE i.nom IS NOT NULL AND idutilisateur = :idutilisateur ";
				$stm = $pdo->prepare($sql);
				$stm->execute(array(":idutilisateur" => $_SESSION['idutilisateur']));	
			}
		
			// Création de la liste de catégorie
			echo "<ul>";
			while( $categorie = $stm->fetch(PDO::FETCH_ASSOC) ){
				echo "<li>".$categorie["nom"]."</li>";
			}
			echo "</ul><div>";
			
			// Création des catégories 
			$sql = "SELECT * FROM categorie WHERE idutilisateur = :idutilisateur";
			$stmCategorie = $pdo->prepare($sql);
			$stmCategorie->execute(array(":idutilisateur" => $_SESSION["idutilisateur"]));
			while( $categorie = $stmCategorie->fetch(PDO::FETCH_ASSOC) ){
				echo "<div class='".strtolower(format_dossier($categorie['nom']))."'>";

				// Récupération des images
				if ( isset($_POST["recherche"])) {
					$sql = "SELECT i.nom AS nom, i.description AS description, i.idimage AS idimage, i.lien AS lien FROM image as i LEFT JOIN tag AS t ON i.idimage = t.idimage WHERE idcategorie = :idcategorie AND ( nom LIKE :recherche OR description LIKE :recherche OR lien LIKE :recherche OR t.libelle LIKE :recherche) GROUP BY idimage ORDER BY ordre ASC" ;
					$stmImage = $pdo->prepare($sql);
					$stmImage->execute(array(":idcategorie" => $categorie["idcategorie"], ":recherche" => "%".$_POST["recherche"]."%" ));
				} else {
					$sql = "SELECT * FROM image WHERE idcategorie = :idcategorie ORDER BY ordre ASC";
					$stmImage = $pdo->prepare($sql);
					$stmImage->execute(array(":idcategorie" => $categorie["idcategorie"]));
				}
				while( $image = $stmImage->fetch(PDO::FETCH_ASSOC) ){
					echo "	<div>
								<img src='utilisateurs/".$_SESSION['utilisateur']."/".format_dossier($categorie['nom'])."/".$image['lien']."' alt=''/>
								<div>
									<span>".$image['nom']."</span>
									<p>".$image['description']."</p>";
					
									// Récupération des tags
									$sql = "SELECT * FROM tag WHERE idimage = :idimage ";
									$stmTag = $pdo->prepare($sql);
									$stmTag->execute(array(":idimage" => $image['idimage']));
									
									if ( $stmTag->rowCount() > 0 ){
										echo "<ul>";
										while( $tag = $stmTag->fetch(PDO::FETCH_ASSOC) ){
											echo "<li>".$tag["libelle"]."</li>";
										}
										echo "</ul>";
									}
					
					echo "		</div>
							</div>";
				}
				echo "</div>";
			}
			echo "</div>";
		}
							
	?>		
	</div>
</div>