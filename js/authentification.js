$(document).ready(function(){
	
	$("#selectionConnexion").centrer();
	
/*********************************************************************************************************/
/*													Variable											 */
/*********************************************************************************************************/	
	
	var login = "";
	var email = "";
	var password = "";
	var allFields = "";
	var tips = "";

/*********************************************************************************************************/
/*												Fonction											 */
/*********************************************************************************************************/	

	
	// Affichage d'un texte un cour instant
	function updateTips(t) {
		tips.text( t ).addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	};



	// Vérification de la longueur d'un élément
	function checkLength( o, n, min, max ) {
		if ( o.val().length > max || o.val().length < min ) {
			o.addClass( "ui-state-error" );
			updateTips( "La taille de " + n + " doit être entre " + min + " et " + max + "." );
			return false;
		} else {
			return true;
		}
	};

	// Vérification du format d'un élément
	function checkRegexp( o, regexp, n ) {
		if ( !( regexp.test( o.val() ) ) ) {
			o.addClass( "ui-state-error" );
			updateTips( n );
			return false;
		} else {
			return true;
		}
	};

	
/*********************************************************************************************************/
/*												Nouveau dialog											 */
/*********************************************************************************************************/	
	
	// Création du formulaire pour la connexion 
	$( "#formulaireDialogNouveau" ).dialog({
		autoOpen: false,
		height: 350,
		width: 350,
		modal: true,
		buttons: {
			"Création de votre compte":{
                text: "Création de votre compte",
                id: "nouveauValidation",
                click: function() {
                    var estValide = true;
                    allFields.removeClass( "ui-state-error" );
                    
                    // Test de longueur
                    estValide = estValide && checkLength( login, "login", 3, 255 );
                    estValide = estValide && checkLength( email, "email", 6, 255 );
                    estValide = estValide && checkLength( password, "password", 6, 255 );
                    
                    // Test de format
                    estValide = estValide && checkRegexp( login, /^[a-z]([0-9a-zA-Z_])+$/i, "Le login peut être composé de chiffre, lettre minuscule et majuscule!" );
                    estValide = estValide && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, " prenom.nom@etu.univ-lyon1.fr" );
                    estValide = estValide && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Le mot de passe peut être composé de chiffre, lettre minuscule et majuscule" );
                    
                    // Tous les test valides
                    if ( estValide ) {
                        $.ajax({
                            type: "POST",
                            url: "nouveau_utilisateur.php",
                            async : false,
                            data: { login: login.val(), email: email.val(), password: password.val() },
                            dataType : "json",
                            statusCode: {
                                404: function() {
                                alert( "La page est introuvable !");
                                }
                            },
                            success: function (data){
                                if( data.msg != "ok"){
                                    $("#textAncien").text(data.msg);
                               } else {
                                    $(location).attr('href',"index.php");
                               }
                            }
                        });
                        $( this ).dialog( "close" );
                    }
                }
			},
			Annuler: function() {
				$( this ).dialog( "close" );
			}
		},
		Fermer: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});

	// Ouverture de la dialog pour les nouveaux utilisateurs
	$( "#nouvelUtilisateur" ).on("click",function() {
		login = $("#nouveauLogin");
		email = $("#nouvelEmail");
		password = $("#nouveauPassword");
		allFields = $( [] ).add( login ).add( email ).add( password );
		tips = $( ".validateTips");
		$("#formulaireDialogNouveau").dialog("open");
	});
	
/*********************************************************************************************************/
/*												Ancien dialog											 */
/*********************************************************************************************************/	
	
	// Création du formulaire pour la connexion 
	$( "#formulaireDialogAncien" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: {
			"Connexion à votre compte": {
                text: "Connexion à votre compte",
                id: "validation",
                click: function() {
                    var estValide = true;
                    allFields.removeClass( "ui-state-error" );
                    
                    // Test de longueur
                    estValide = estValide && checkLength( login, "login", 3, 255 );
                    estValide = estValide && checkLength( password, "password", 6, 255 );
                    
                    // Test de format
                    estValide = estValide && checkRegexp( login, /^[a-z]([0-9a-zA-Z_])+$/i, "Le login de passe peut être composé de chiffre, lettre minuscule et majuscule" );
                    estValide = estValide && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Le mot de passe peut être composé de chiffre, lettre minuscule et majuscule" );
                    
                    // Tous les test valides
                    if ( estValide ) {
                        $.ajax({
                            type: "POST",
                            url: "ancien_utilisateur.php",
                            async : false,
                            data: { login: login.val(),password: password.val() },
                            dataType : "json",
                            statusCode: {
                                404: function() {
                                alert( "La page est introuvable !");
                                }
                            },
                            success: function (data){
                                if( data.msg != "ok"){
                                    $("#textAncien").text(data.msg);
                               } else {
                                    $(location).attr('href',"index.php");
                               }
                            }
                        });
                        $( this ).dialog( "close" );
                    }
                }
			},
			Annuler: function() {
				$( this ).dialog( "close" );
			}
		},
		Fermer: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});

	// Ouverture de la dialog pour les anciens utilisateurs
	$( "#ancienUtilisateur" ).on("click",function() {
		login = $("#login");
		password = $("#password");
		allFields = $( [] ).add( login ).add( password );
		tips = $( ".validateTips");
		$("#formulaireDialogAncien").dialog("open");
	});
	
    
/*********************************************************************************************************/
/*											Personnaliser											 */
/*********************************************************************************************************/	
    
	// Valider le formulaire avec un entrer
    $('#nouveauLogin,#nouveauPassword').keyup(function(e) { 
        if(e.keyCode == 13) {
            $("#nouveauValidation").trigger("click");
        }
    });
    
    // Valider le formulaire avec un entrer
    $('#login, #password').keyup(function(e) { 
        if(e.keyCode == 13) {
            $("#validation").trigger("click");
        }
    });
    
});
