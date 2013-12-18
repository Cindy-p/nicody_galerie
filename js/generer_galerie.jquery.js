(function($) {
	$.fn.genererGalerie = function(parametres) {
		
		return this.each(function() {
			function precharger(listeImages) {
			    $(listeImages).each(function() {
					(new Image()).src = this;
			    });
			}

			precharger([
				'img/utilisateur1/noel/noel1.jpg',
				'img/utilisateur1/noel/noel2.jpg',
				'img/utilisateur1/noel/noel3.jpg',
				'img/utilisateur1/chats/chat1.jpg',
				'img/utilisateur1/chats/chat2.jpg',
				'img/utilisateur1/chats/chat3.jpg'
			]);

			var nomCategorie = "tous";

			var divConteneurImage = $("#galerie img").parent();
			divConteneurImage.addClass("conteneurImage");

			$("#galerie>div").attr('id', 'listeImages');

			$("#listeImages>div").css("display", "inline");
			$("#listeImages>div").addClass("categorie");

			// On définit un id à la liste des catégories et des images
			$("#galerie>ul").attr("id", "categories");

			// On ajoute la catégorie qui affichera toutes les images
			$("#categories").prepend('<li>Tous</li>');

			$("#galerie .conteneurImage span").addClass("titre");
			$(".conteneurImage .titre").css('width', $(".conteneurImage img").width());
			
			$("#galerie .conteneurImage ul").addClass("tags");
			$(".conteneurImage .tags").css('width', $(".conteneurImage img").width());
			
			// Affichage des images en fonction des catégories
			var precedCategorie = $("#categories li").first();
			precedCategorie.addClass("select");
			
			$("#categories li").each(function() {
				$(this).click(function() {
					var selectCategorie = $(this);
					selectCategorie.addClass("select");
					if (selectCategorie.text() != precedCategorie.text()) {
						precedCategorie.removeClass("select");
						precedCategorie = selectCategorie;
						$(".conteneurImage").hide();
						nomCategorie = $(this).text().toLowerCase().replace(/[èéêë]/g, "e");
						// Pb IE au niveau du hasClass (fonctionne avec chaine en dur)
						if (nomCategorie != "tous") {
							$(".conteneurImage").each(function() {
								if ($(this).parent().hasClass(nomCategorie)) {
									$(this).fadeIn("slow");
								}
							});
						}
						else {
							$(".conteneurImage").fadeIn("slow");
						}
					}
				});
			});

			// Description qui s'affiche au survol de la souris
			$(".conteneurImage p").addClass("description");

			$(".conteneurImage img").each(function() {
				var description = $(this).siblings("div").children(".description");
				$(this).mouseenter(function() {
					description.fadeIn("fast");
				});

				$(this).mouseleave(function() {
					description.fadeOut("fast");
				});

				$(this).mousemove(function(event) {
					description.css("left", event.pageX);
					description.css("top", event.pageY);
					description.show();
				});
			});

			var centrer = function(objet) {
				objet.css("top", Math.max(0, (($(window).height() - objet.outerHeight()) / 2)) + "px");
			    objet.css("left", Math.max(0, (($(window).width() - objet.outerWidth()) / 2)) + "px");
			};

			// Permet de générer la popup
			var popup = function(img) {
				// On supprime la pop-up si elle existait déjà
				if ($("#popup").length)
					$("#popup").remove();

				// On crée la pop-up
				$("#galerie").append('<div id="popup"><img src="' + img.attr("src") + '"/></div>');
				
				// Taille de l'image se situant dans la pop-up
				$("#popup img").css("width", $(window).width()*0.5);

				// $("#popup").append('<p class="fermer"><img src="img/croix1.png"/></p>');

				// Comportement des liens précédent et suivant
				// Lien précédent
				var divCategorie = img.parent().parent();
				if ((nomCategorie == "tous" && img.parent().get(0) == $('.conteneurImage').get(0)) ||
					(nomCategorie != "tous" && img.parent().get(0) == divCategorie.find('.conteneurImage').get(0))) {
					var imgPrec = ""; // Pas de lien précédent
				}
				else if (nomCategorie == "tous" && img.parent().get(0) == divCategorie.find('.conteneurImage').get(0) &&
						img.parent().get(0) != $('.conteneurImage').get(0)) {
					/* Si on se situe dans la catégorie "tous", que l'image est la première d'une div catégorie
					   mais pas la première de la catégorie "tous" */
					var imgPrec = divCategorie.prev().find('.conteneurImage img').last();
				}
				else {
					var imgPrec = img.parent().prev().find("img");
				}

				// Lien suivant
				if ((nomCategorie != "tous" && img.parent().get(0) == divCategorie.find('.conteneurImage').last().get(0)) ||
					(nomCategorie == "tous" && img.parent().get(0) == $('.conteneurImage').last().get(0))) {
					// Si l'image est la dernière de la catégorie sélectionnée
					var imgSuiv = ""; // Pas de lien suivant
				}
				else if (nomCategorie == "tous" && img.parent().get(0) == divCategorie.find('.conteneurImage').last().get(0) &&
						img.parent().get(0) != $('.conteneurImage').last().get(0)) {
					/* Si on se situe dans la catégorie "tous", que l'image est la dernière d'une div catégorie
					   mais pas la dernière de la catégorie "tous" */
					var imgSuiv = divCategorie.next().find('.conteneurImage img').first();
				}
				else {
					var imgSuiv = img.parent().next().find("img");
				}

				// Affichage des images associés aux liens précédent et suivant
				$("#popup img").mousemove(function(event) {
					var positionSourisImgX = event.pageX - $(this).parent().position().left - $(this).position().left;
					if (positionSourisImgX > 0 && positionSourisImgX < $(this).width()+1) {
						if ((positionSourisImgX < $(this).width()/2) && (imgPrec != "")) {
							$("#imgSuiv").fadeOut('fast');
							$("#popup").append('<span id="imgPrec"><img src="img/fleche_precedente.png"/></span>');
							$("#imgPrec").css("top", ($("#popup").height()/2) - 25 + 'px');
							$("#imgPrec").fadeIn('fast');
							$("#imgPrec").click(function() {
								popup(imgPrec);
							});
						}
						else if ((positionSourisImgX >= $(this).width()/2) && (imgSuiv != "")) {
							$("#imgPrec").fadeOut('fast');
							$("#popup").append('<span id="imgSuiv"><img src="img/fleche_suivante.png"/></span>');
							$("#imgSuiv").css('top', ($("#popup").height()/2) - 25 + 'px');
							$("#imgSuiv").fadeIn('fast');
							$("#imgSuiv").click(function() {
								popup(imgSuiv);
							});
						}
						else {
							$("#imgPrec").fadeOut('fast');
							$("#imgSuiv").fadeOut('fast');
						}
					}
				});

				// Affichage du titre et de la description dans la pop-up
				var tailleMax = $("#popup").width();
				$("#popup").append('<p class="titre">' + img.parent().find('.titre').text() + '</span>');
				$("#popup").append('<p class="description">' + img.parent().find('.description').text() + '</span>');
				// Pour que le titre et la description s'adapte à la taille de la pop-up
				$("#popup .titre").css('width', tailleMax);
				$("#popup .description").css('width', tailleMax);

				// Passage à l'image précédente ou suivante en cliquant sur la moitié droite ou gauche de l'image
				$("#popup img").click(function(event) {
					var positionSourisImgX = event.pageX - $(this).parent().position().left - $(this).position().left;
					if (positionSourisImgX > 0 && positionSourisImgX < $(this).width()+1) {
						if ((positionSourisImgX < $(this).width()/2) && (imgPrec != "")) {
							popup(imgPrec);
						}
						else if ((positionSourisImgX >= $(this).width()/2) && (imgSuiv != "")) {
							popup(imgSuiv);
						}
					}
				});

				// Positionnement de la pop-up dans la page et affichage
				centrer($("#popup"));
				$("#popup").fadeIn("fast");
			};

			$("#galerie img").click(function() {
				popup($(this));
				
				$("body").append('<div id="fade"></div>').show();
				$('#fade').css({'filter' : 'alpha(opacity=30)'}).show(); // Pour IE
			});


			$('body').on('click', '#fade', function() {
				$('#fade, #popup').fadeOut("fast");
			});
		});
	};
})(jQuery);