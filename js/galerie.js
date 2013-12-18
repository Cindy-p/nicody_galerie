$(document).ready(function(){
	
	// Réalise le "formatage" de la page 
	$("#galerie").genererGalerie();
	
	// Recherche dans les images
	$("#recherche").on("keyup",function(){
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
	});
	
});
