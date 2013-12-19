$(document).ready(function(){
	
	var prechargement = [];
	
	$(".precharge").each(function(){
		prechargement.push($(this).attr("src"));
	});
	
	// Réalise le "formatage" de la page 
	$("#galerie").genererGalerie(prechargement);
	
	// Signifie qu'aucun résultat n'est sélectionné au départ
	var selectedResult = -1;

	// Recherche dans les images
	$("#recherche").on("keyup", function(event) {

		if (event.which == 38 && selectedResult > -1) { // Touche haut
			$("#rechercheItems li").eq(selectedResult--).removeClass("focusLi");

			if (selectedResult > -1) {
				$("#rechercheItems li").eq(selectedResult).addClass("focusLi");
			}
		}
		else if (event.which == 40 && $("#rechercheItems li").length != 0 && selectedResult < $("#rechercheItems li").length-1) { // Touche bas
			$("#rechercheItems").show();

			if (selectedResult > -1) {
				$("#rechercheItems li").eq(selectedResult).removeClass("focusLi");
			}

			$("#rechercheItems li").eq(++selectedResult).addClass("focusLi");
		}
		else if (event.which == 13 && selectedResult > -1) { // Touche
			$("#recherche").val($(".focusLi").html());
			$("#rechercheItems").empty();
			$("#rechercheItems").hide();
			$("#recherche").trigger("keyup");
		} else {
		
		
			$("#rechercheItems").empty();
			
			// Partie affichage des résultats
			if ($(this).val() == ""){
				$.get("corps.php", function(html){
					$("#corps").replaceWith(html);
			        $("#galerie").genererGalerie();
				}); 
			} else {
				$.ajax({
		            type: "POST",
		            url: "corps.php",
		            async : false,
		            data: { recherche : $(this).val()  },
		            statusCode: {
		                404: function() {
		                alert( "La page est introuvable !");
		                }
		            },
		            success: function (html){
		            	$("#corps").replaceWith(html);
		            	$("#galerie").genererGalerie();
		            }
		        });
			}
			
			// Partie autocompletion
			if ($(this).val().length > 2) {
				$.ajax({
		            type: "GET",
		            url: "recherche.php",
		            async : false,
		            data: { recherche : $(this).val()  },
		            dataType: "json",
		            statusCode: {
		                404: function() {
		                alert( "La page est introuvable !");
		                }
		            },
		            success: function (data) {
		            	if(data.length > 0){
		            		if (data[0] != $("#recherche").val()){
				            	var string = "";
				            	data.forEach(function(item) {
				            		string += "<li class='curseur autocompleteItem'>" + item + "</li>";
				            	});
				            	$("#rechercheItems").append(string);
				            	$("#rechercheItems").show();
		            		}
		            	}
		            	else {
		            		$("#rechercheItems").hide();
		            	}
		            }
		        });
			}
			else {
				$("#rechercheItems").hide();
			}
		}

	});
	
	//
	$("#rechercheItems").on("click",".autocompleteItem", function() {
		$("#recherche").val($(this).html());
		$("#rechercheItems").empty();
		$("#rechercheItems").hide();
		$("#recherche").trigger("keyup");
	});
	
});
