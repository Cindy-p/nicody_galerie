<?php
	  $page = array(
        "titre" => "Nicody Galerie - Connexion",
        "link" => array( "css/authentification.css"),
        "script" => array( "js/authentification.js" , "js/centrer.js" ),
        "no_redirect" => true
        );
	include ("include/header.php");
?>
		
		<div id="selectionConnexion">
			<button id="nouvelUtilisateur" class="max_width ui-button ui-widget ui-state-default ui-corner-all" >Créer un nouveau compte</button>
			<br/><br/><br/><br/>
			<button id="ancienUtilisateur" class="max_width ui-button ui-widget ui-state-default ui-corner-all" >Connectez-vous à votre compte</button>
		</div>
		
		<div id="formulaireDialogNouveau" title="Création de votre compte">
			<p id="textNouveau"  class="validateTips">Tous les champs sont requis !</p>
			<form>
				<fieldset>
					<label for="login">Identifiant</label>
					<input type="text" name="nouveauLogin" id="nouveauLogin" class="text ui-widget-content ui-corner-all">
					<label for="email">Email</label>
					<input type="text" name="nouvelEmail" id="nouvelEmail" value="" class="text ui-widget-content ui-corner-all">
					<label for="password">Password</label>
					<input type="password" name="nouveauPassword" id="nouveauPassword" value="" class="text ui-widget-content ui-corner-all">
				</fieldset>
			</form>
		
		<div id="formulaireDialogAncien" title="Connexion à votre compte">
			<p id="textAncien" class="validateTips"></p>
			<form>
				<fieldset>
					<label for="login">Identifiant</label>
					<input type="text" name="login" id="login" class="text ui-widget-content ui-corner-all">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all">
				</fieldset>
			</form>
		</div>
		
		</div>
		
<?php
    include("include/footer.php");
?>
