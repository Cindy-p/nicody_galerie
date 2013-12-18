$(document).ready(function(){
	
	// Affichage d'un texte un cour instant
	function updateTips( t ) {
		tips.text( t ).addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
		
	// Vérification de la longueur d'un element
	function checkLength( o, n, min, max ) {
		if ( o.val().length > max || o.val().length < min ) {
			o.addClass( "ui-state-error" );
			updateTips( "Length of " + n + " must be between " + min + " and " + max + "." );
			return false;
		} else {
			return true;
		}
	}
	
	// Vérification du format d'un element
	function checkRegexp( o, regexp, n ) {
		if ( !( regexp.test( o.val() ) ) ) {
			o.addClass( "ui-state-error" );
			updateTips( n );
			return false;
		} else {
			return true;
		}
	}

	
	// Création du formulaire pour la connexion 
	$( "#formulaireDialogAncien" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: {
			"Connexion à cotre compte": function() {
				var estValide = true;
				allFields.removeClass( "ui-state-error" );
				
				// Test de longueur
				estValide = estValide && checkLength( login, "login", 3, 255 );
				estValide = estValide && checkLength( email, "email", 6, 255 );
				estValide = estValide && checkLength( password, "password", 6, 255 );
				
				// Test de format
				estValide = estValide && checkRegexp( login, "", "A VOIR" );
				//estValide = estValide && checkRegexp( login, /^[a-z]([0-9a-z_])+$/i, "A VOIR" );
				estValide = estValide && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, " prenom.nom@etu.univ-lyon1.fr" );
				estValide = estValide && checkRegexp( password,"", "A VOIR" );
				//estValide = estValide && checkRegexp( password, /^([0-9a-zA-Z])+$/, "A VOIR" );
				
				// Tous les test valides
				if ( estValide ) {
					
					$( this ).dialog( "close" );
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});

	// Ouverture de la dialog pour les anciens utilisateurs
	$( "#ancienUtilisateur" ).on("click",function() {
		var login = $("#login");
		var email = $("#email");
		var password = $("#password");
		var allFields = $( [] ).add( name ).add( email ).add( password );
		var tips = $( ".validateTips");
		$("#formulaireDialogAncien").dialog("open");
	});
	
});
