$(document).ready(function(){
	
	// Réalise le "formatage" de la page 
	$("#galerie").genererGalerie();
	
	// Recherche dans les images
	$("#recherche").on("keyup",function(){
		
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
		if ( $(this).val().length > 3 ){
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
	            success: function (data){
	            	
	            	if ( data.length > 1){
		            	var string = "";
		            	data.forEach(function(item){
		            		string += "<li class='curseur autocompleteItem'>"+item+"</li>";
		            	});
		            	$("#rechercheItems").append(string);
	            	}
	            }
	        });
		}
	});
	
	//
	$("#rechercheItems").on("click",".autocompleteItem",function(){
		$("#recherche").val($(this).html());
		$("#rechercheItems").empty();
		$("#recherche").trigger("keyup");
	});
	
});
