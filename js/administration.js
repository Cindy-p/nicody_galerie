var tips = "";

$(document).ready(function(){

	var nom = "";
	var description = "";
	var file = "";
	var allFields = "";
	

/***************************************Catégorie**************************************/
	
    // Création de la liste de catégories
	$( "#listCategorie" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#listCategorie li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
  
    // Création d'une nouvelle catégorie
    $("#confirmNomCategorie").on("click",function(){ 
    	var estValide = true;
    	var nomCategorie = $("#nomCategorie");
    	tips = $(".validateTips");
    	
    	estValide = estValide && checkLength( nomCategorie, "nom de la catégorie", 1, 255 );
    	estValide = estValide && checkRegexp( nomCategorie, /^[a-zA-Z0-9 áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/, "Le nom de dossier peut être composé de chiffre, lettre minuscule et majuscule uniquement!" );
        
    	if ( estValide ){
    			
	    	$.ajax({
	            type: "POST",
	            url: "nouvelle_categorie.php",
	            async : false,
	            data: { nomCategorie : nomCategorie.val() },
	            dataType : "json",
	            statusCode: {
	                404: function() {
	                alert( "La page est introuvable !");
	                }
	            },
	            success: function (data){
	                if( data.msg != "ok"){
	                	 console.log(data);
	               } else {
	            	   $(location).attr('href',"administration.php");
	               }
	            }
	        });
    	}
    });
    
    // Modification d'une catégorie
    $(".editCategorie").on("click", function(){
    	
    	var idCategorie = ($(this).attr("id").split("editCategorie-"))[1];
    	var bouton = $(this);
    	
    	// Sauvegarde du nouveau nom
    	if ( $(this).hasClass("encours")){
    		var nomCategorie = $("#nomCategorie-"+idCategorie).val();
    		$.ajax({
	            type: "POST",
	            url: "modification_categorie.php",
	            async : false,
	            data: { idCategorie : idCategorie, nomCategorie: nomCategorie  },
	            dataType : "json",
	            statusCode: {
	                404: function() {
	                alert( "La page est introuvable !");
	                }
	            },
	            success: function (data){
	                if( data.msg != "ok"){
	                	 console.log(data);
	               } else {
	            	   $("#nomCategorie-"+idCategorie).replaceWith("<span id='nomCategorie-"+idCategorie+"'>"+nomCategorie+"</span>");
	            	   $(".categorie-"+idCategorie).html(nomCategorie);
	            	   console.log(bouton.attr("class"));
	            	   bouton.removeClass("encours");
	            	   console.log(bouton.attr("class"));
	               }
	            }
	        });
    	
    	// Création d'un input pour la modification
    	} else {
    	
	    	$(this).addClass("encours");
	    	var nom = $("#nomCategorie-"+idCategorie).html();
	    	$("#nomCategorie-"+idCategorie).replaceWith("<input id='nomCategorie-"+idCategorie+"' type='text' value='"+nom+"'/>");
	    	
    	}
    	
    	
    });
    
    
    // Suppression d'une catégorie
    $(".supprimerCategorie").on("click",function(){
    	if(confirm("Voulez-vous réellement supprimer une catégorie ?\n(L'intégralité des images de son contenu sera supprimé)")){
    		var idCategorie = ($(this).attr("id").split("supprimerCategorie-"))[1];
    		var nomCategorie = $("#nomCategorie-"+idCategorie).html();
    		$.ajax({
	            type: "POST",
	            url: "supprimer_categorie.php",
	            async : false,
	            data: { idCategorie : idCategorie, nomCategorie: nomCategorie  },
	            dataType : "json",
	            statusCode: {
	                404: function() {
	                alert( "La page est introuvable !");
	                }
	            },
	            success: function (data){
	                if( data.msg != "ok"){
	                	 console.log(data);
	               } else {
	            	   $(location).attr('href',"administration.php");
	               }
	            }
	        });
    	}
    });
    
    // Ouvriri la bonne catégorie
    $(".categorie-"+$("#currentCategorie").val()).trigger("click");
    
    // Stock la catégorie en cours
    $("a[role=presentation]").on("click",function(){
    	$("#currentCategorie").val(($(this).attr('href').split("#categorie-"))[1]);
    });

 /***************************************Images**************************************/
    
    // La liste d'image et la sauvegarde de l'ordre
    $( ".listImage" ).sortable( {
    		connectWith: ".listImage",
    	    stop: function (event, ui) {
    			var listImage = [];
    			$(".listImage > li").each(function(){
    				listImage.push(($(this).attr("id").split("idImage-"))[1]);
    			});
    			
    			$.ajax({
    	            type: "GET",
    	            url: "modification_ordre.php",
    	            async : true,
    	            data: { listImage : listImage },
    	            dataType : "json",
    	            statusCode: {
    	                404: function() {
    	                alert( "La page est introuvable !");
    	                }
    	            },
    	            success: function (data){
    	            	console.log(data.msg);
    	            	if( data.msg != "ok"){
    	            		console.log(data.msg);
    	            	}
    	            }
    	        });
    			
    		}
    }).disableSelection();
    
    
    // Création d'une nouvelle image
    $(".nouvelleImage").on("click", function(){
    	var idCategorie = ($(this).parent().attr("id").split("categorie-"))[1];
    	var url = "formImage.php?idCategorie="+idCategorie;
    	
    	// Chargement du formulaire
    	$("#formImage").load(url);
    	
    	// Création de la dialog
		$("#formImage").dialog({
			autoOpen: true,
			height: 350,
			width: 350,
			modal: true,
			buttons: {
				"Création de votre image":{
	                text: "Création de votre image",
	                id: "creationImage",
	                click: function() {
			
						$("#formulaireImage").ajaxSubmit({
							success: function(data) {
								var data = $.parseJSON(data);
								if( data.msg != "ok"){
									console.log(data);
								} else {
									var post = new Array();
									post["idCategorie"] = idCategorie ;
									locationPost("administration.php", post );
								}
							}
			            });
					}
				},
				Annuler: function() {
					// Suppression du formulaire
                	$(this).html();
					$(this).dialog("close");
				}
			},
			Fermer: function() {
				allFields.val("").removeClass("ui-state-error");
			}
		});
		
    });
    
 // Modificaton d'une image
    $(".editImage").on("click", function(){
    	var idCategorie = ($(this).parent().parent().parent().attr("id").split("categorie-"))[1];
    	var idImage = ($(this).parent().attr("id").split("idImage-"))[1];
    	var url = "formImage.php?idCategorie="+idCategorie+"&idImage="+idImage;
    	
    	// Chargement du formulaire
    	$("#formImage").load(url);
    	
    	// Création de la dialog
		$("#formImage").dialog({
			autoOpen: false,
			height: 350,
			width: 350,
			modal: true,
			buttons: {
				"Modification de votre image":{
	                text: "Modification de votre image",
	                id: "modifierImage",
	                click: function() {
					
						$("#formulaireImage").ajaxSubmit({
							success: function(data) {
								var data = $.parseJSON(data);
								if( data.msg != "ok"){
									console.log(data);
								} else {
									var post = new Array();
									post["idCategorie"] = idCategorie ;
									locationPost("administration.php", post );
								}
							}
			            });
					}
				},
				Annuler: function() {
					// Suppression du formulaire
                	$(this).html();
					$(this).dialog("close");
				}
			},
			Fermer: function() {
				allFields.val("").removeClass("ui-state-error");
			}
		});
		
		// Lancement de la dialog
		$("#formImage").dialog("open");
		
    });
    
    // Suppression d'une image
    $(".supprimerImage").on("click",function(){
    	var idCategorie = ($(this).parent().parent().parent().attr("id").split("categorie-"))[1];
		var idImage = ($(this).attr("id").split("supprimerImage-"))[1];
		$.ajax({
            type: "POST",
            url: "supprimer_image.php",
            async : false,
            data: { idImage : idImage },
            dataType : "json",
            statusCode: {
                404: function() {
                alert( "La page est introuvable !");
                }
            },
            success: function (data){
              if( data.msg != "ok"){
                	 console.log(data.msg);
               } else {
					var post = new Array();
					post["idCategorie"] = idCategorie;
					locationPost("administration.php", post );
               }
            }
        });
    });
    
});

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
	
	// Fonction de post et location
	function locationPost(url,post){
		var content = "";
		for (var key in post){
			if (post.hasOwnProperty(key)) {
				content = content + "<input type='text' name='"+key+"' value='"+post[key]+"'/>";
		    }
		}
		var form = $('<form action="' + url + '" method="post">'+content+'></form>');
		$('body').append(form);
		$(form).submit();
	}