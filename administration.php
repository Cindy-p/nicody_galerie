<?php
$page = array(
        "titre" => "Nicody Galerie - Administration",
        "script" => array( "js/administration.js", "js/jquery.form.js")
        );
include("include/header.php");

include("include/connexion.php");

	if ( isset($_POST["idCategorie"])){
		$idCategorie = $_POST["idCategorie"];
		echo "<input id='currentCategorie' type='hidden' value='".$idCategorie."'/>";
	} else {
		echo "<input id='currentCategorie' type='hidden'/>";
	}

?>
<div id="corps">
	<div id="listCategorie">
		<ul>
			<li><a href="#nouvelleCategorie">Nouvelle catégorie</a></li>
			<?php 
				$sql = "SELECT * FROM categorie WHERE idutilisateur = :idutilisateur ";
				$stm = $pdo->prepare($sql);
				$stm->execute(array(":idutilisateur" => $_SESSION['idutilisateur']));
				while($categorie = $stm->fetch(PDO::FETCH_ASSOC)){
					echo "<li class='curseur'><a href='#categorie-".$categorie["idcategorie"]."' class='categorie-".$categorie["idcategorie"]."'>".$categorie["nom"]."</a></li>";
				}
			?>
		</ul>
		<div id="nouvelleCategorie">
			<label for="nomCategorie">Nom de la catégorie *</label>
			<input type="text" name="nomCategorie" id="nomCategorie" class="text ui-widget-content ui-corner-all">
			<br/>
			<button id="confirmNomCategorie" class="ui-button ui-corner-all ui-button-text-only">Valider</button>
			<p class="validateTips"></p>
		</div>
		<?php 
			// Récupération des catégories
			$sql = "SELECT * FROM categorie WHERE idutilisateur = :idutilisateur";
			$stmCategorie = $pdo->prepare($sql);
			$stmCategorie->execute(array(":idutilisateur" => $_SESSION["idutilisateur"]));
			while( $categorie = $stmCategorie->fetch(PDO::FETCH_ASSOC) ){
				echo "
				<div id='categorie-".$categorie["idcategorie"]."'>
					<h2><span id='nomCategorie-".$categorie["idcategorie"]."'>".$categorie["nom"]."</span>
						<img id='supprimerCategorie-".$categorie["idcategorie"]."' src='img/croix.png' class='supprimerCategorie right curseur'/>
						<img id='editCategorie-".$categorie["idcategorie"]."' src='img/editer.png' class='editCategorie center curseur'/>
					</h2><br/>
					<div id='formImage'></div>
						<button class='nouvelleImage ui-button ui-corner-all ui-button-text-only'>Nouvelle image</button>
						<br/><br/>
						<ul class='listImage'>
					";
						// Récupération des images
						$sql = "SELECT idimage, nom FROM image WHERE idcategorie = :idcategorie ORDER BY ordre ASC";
						$stmImage = $pdo->prepare($sql);
						$stmImage->execute(array(":idcategorie" => $categorie["idcategorie"]));
						while( $image = $stmImage->fetch(PDO::FETCH_ASSOC) ){
		  					echo "<li id='idImage-".$image["idimage"]."' class='ui-state-default ui-corner-right'>
		  							<img class='deplacerImage' src='img/move.png'/>
		  							<span class='editImage'>".$image["nom"]."</span>
		  							<img id='supprimerImage-".$image["idimage"]."' src='img/croix_blanche.png' class='supprimerImage right curseur'/>
		  						</li>";
						}
	  				echo "</ul>
				</div>
				" ;
			}
		?>
	</div>
</div>



<?php
include("include/footer.php");
include("include/deconnexion.php");
?>