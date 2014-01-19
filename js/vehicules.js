if (typeof Dtno === 'undefined') {
	window.Dtno = {};
}

(function($) {
	var vehic = {
		isFrame: false,

		listModels: {},
		listBrands: {},
		
		init: function() {
			this.isFrame = (window.self !== window.top);
			
			$("#btn-inclure").click(function() {vehic.validate('inclus');});
			$("#btn-exclure").click(function() {vehic.validate('exclus');});
			$("#btn-reset-form").click(function() {vehic.resetForm();});
			$('#btn-show-options').click(function() {vehic.toggleMoreOptions();});
			
			$("#marque").chosen({no_results_text: "No result for "}).change(function() {vehic.filterModels();});
			$("#modele").chosen({no_results_text: "No result for"});
			
			$("#pac-min").change(vehic.changeMinPrice);
			$("#parg-min").change(vehic.changeMinPrice);
			
			this.initData();
			this.load();
			
			$(".loading").hide();
		},
		
		/**
		 * Range les valeurs d'un critère intervalle (composé d'un min et d'un max) dans la liste des critères
		 * @param criteria list des critères
		 * @param id id du critère du dans la page
		 */
		serializeIntervalle: function(criteria, id) {
			var value;

			// min
			value = $.trim($("#" + id + "-min").val());
			if (value) {
				if (!Dtno.utils.isset(criteria[id])) {
					criteria[id] = {};
				}
				criteria[id]['min'] = value;
			}

			// max
			value = $.trim($("#" + id + "-max").val());
			if (value) {
				if (!Dtno.utils.isset(criteria[id])) {
					criteria[id] = {};
				}
				criteria[id]['max'] = value;
			}
		},

		/**
		 * Créé la liste des lignes de critères en fonction de la sélection faite sur le formulaire
		 * @returns {Array} liste des lignes
		 */
		serializeFormData: function() {
			var list = [],				// liste des lignes de critères
				criteriaRepeat = {},	// l'ensemble des critères à répéter dans le cas du multi-valué
				criteria = {},			// une ligne de critères
				listBrand = $("#marque").val() || [];	// les marques sélectionnées

			// critères non multi-valués qui seront répétés pour chaque couple (marque, [modele])
			vehic.serializeIntervalle(criteriaRepeat, 'mec');
			vehic.serializeIntervalle(criteriaRepeat, 'ddi');
			vehic.serializeIntervalle(criteriaRepeat, 'pac');
			vehic.serializeIntervalle(criteriaRepeat, 'parg');
			$("#formVehic select").each(function() {
				if (this.type !== 'select-multiple') {
					var $this = $(this),
						id = $this.attr('id'),
						value = $.trim($this.val());
	
					if (!Dtno.utils.isset(criteriaRepeat[id]) && !/-(min|max)$/.test(id)) {
						if (value) {
							criteriaRepeat[id] = {
								value: value,
								label: $(this).children('option:selected').text()
							};
						}
					}
				}
			});
			
			// gestion du multi-valué
			// les modèles
			$("#modele option:selected").each(function() {
				var $this = $(this),
					brand = $this.data("parent-entity");

				// supprime la marque de la liste si on a sélectionné un modèle de cette marque
				if (Dtno.utils.isset(brand) && $.inArray(brand, listBrand) >= 0) {
					listBrand.splice($.inArray(brand, listBrand), 1);
				}
				
				criteria = $.extend({}, criteriaRepeat, {
					marque: {
						value: brand,
						label: vehic.listBrands[brand]
					},
					modele: {
						value: this.value,
						label: this.text}
					}
				);
				list.push(criteria);
			});
			// les marques sans modèle
			if (listBrand.length > 0) {
				$("#marque option:selected").each(function() {
					if ($.inArray(this.value, listBrand) >= 0) {
						criteria = $.extend({}, criteriaRepeat, {
							marque: {
								value: this.value,
								label: this.text}
							}
						);
						list.push(criteria);
					}
				});
			}
			
			// si ni modèle, ni marque => une seule ligne de critères
			if (list.length == 0 && !$.isEmptyObject(criteriaRepeat)) {
				list.push(criteriaRepeat);
			}

			return list;
		},

		/**
		 * Sélectionne les valeurs dans le formulaire suivant la liste de critères donnée
		 * @param criteria liste de critères
		 */
		unserializeFormData: function(criteria) {
			if (Dtno.utils.isset(criteria.modele) && Dtno.utils.isset(criteria.marque)) {
				$("#modele").prop("disabled", "");
				vehic.unserializeFormInput('marque', criteria.marque.value);
				this.filterModels();
			}
			else {
				$("#modele").prop("disabled", "disabled");
			}
			
			$.each(criteria, function(crit, val) {
				if (crit == 'mec' || crit == 'ddi' || crit == 'pac' || crit == 'parg') {
					if (Dtno.utils.isset(val.min)) {
						vehic.unserializeFormInput(crit + '-min', val.min);
					}
					if (Dtno.utils.isset(val.max)) {
						vehic.unserializeFormInput(crit + '-max', val.max);
					}
				}
				else {
					vehic.unserializeFormInput(crit, val.value);
				}
			});
			
			$("#marque").trigger("liszt:updated");
			$("#modele").trigger("liszt:updated");
		},

		/**
		 * Initialise un input
		 * @param id id de l'input
		 * @param value valeur
		 */
		unserializeFormInput: function(id, value) {
			document.getElementById(id).value = value;
			/*var $elt = $('#' + id);

			$elt.val(value);*/
		},

		/**
		 * Met à jour la combo max correspondante
		 * Déclenché sur le onchange des combo prix mini
		 */
		changeMinPrice: function() {
			var part = this.id.match("(.+)-min$");

			if (part !== null) {
				vehic.setMaxCombo(part[1] + "-max", this.value);
			}
		},

		/**
		 * Désactive les options d'une combo dont la valeur est supérieure au seuil donné
		 * @param id id de la combo
		 * @param minVal seuil
		 */
		setMaxCombo: function(id, minVal) {
			var $elt = $("#" + id);

			minVal = parseInt(minVal);
			if ($elt.val() <= minVal) {
				$elt.val("");
			}
			$.each($("#" + id + " option"), function() {
				if (this.value !== "") {
					if (this.value <= minVal) {
						this.disabled = "disabled";
					}
					else {
						this.disabled = "";
					}
				}
			});
		},

		resetForm: function() {
			var $modele = $("#modele");
			
			$("#formVehic").get(0).reset();
			$("#pac-max option:disabled").prop("disabled", "");
			$("#parg-max option:disabled").prop("disabled", "");
			
			$modele.empty();
			$modele.prop("disabled", "disabled");
			
			$("#marque").trigger("liszt:updated");
			$modele.trigger("liszt:updated");
		},
		
		/**
		 * Sérialise les critères, poste les données et ajoute aux listes
		 * Déclenché sur le clic d'un bouton d'ajout (inclure, exclure)
		 * @param listName nom de la liste (inclus|exclus)
		 */
		validate: function(listName) {
			var listCriteria,
				$panier;

			listCriteria = vehic.serializeFormData();
			if (listCriteria.length > 0) {
				Dtno.utils.doPost(
					'add',
					{list: listName, listCriteria: listCriteria},
					function(data) {
						$.each(data, function(i, d) {
							vehic.addToList(listName, d);
						});
						
						$panier = $("#vehic-" + listName).parent(".panier");
						if ($panier.is(":hidden")) {
							$panier.show();
						}
						$panier.children(".placeholder").hide();
						
						if (vehic.isFrame) {
							vehic.resizeFrame();
						}
					},
					null,
					function() {
						alert("An unknown error was raised");
					})
			}
		},

		/**
		 * Déserialise les critères d'une ligne
		 * Déclenché sur le double clic d'une ligne
		 */
		callbackDuplicate: function() {
			var $elt = $(this);

			vehic.resetForm();

			data = $elt.data("criteria");
			if (Dtno.utils.isset(data) && !$.isEmptyObject(data)) {
				vehic.unserializeFormData(data);
				vehic.showMoreOptionsOnDuplicate(data);
			}
		},
		
		/**
		 * Créé un nouveau list item et l'ajoute à la liste
		 * @param listName nom de la liste
		 * @param d liste de critères
		 */
		addToList: function(listName, d) {
			var $elt,
				$btn,
				$list = $("#vehic-" + listName);

			$elt = $('<li title="Double click on the line to copy the criteria in the filter list"/>')
				.attr("id", "vehic-" + listName + "-" + d.id)
				.html("<span>" + d.label + "</span>")
				.data("criteria", d.criteria)
				.dblclick(vehic.callbackDuplicate);

			$btn = $('<button type="button" title="delete" class="btn btn-reset pull-right delete"/>')
				.click(function() {
					vehic.remove(listName, d.id);
				})
				.html('<i class="icon-remove-circle"></i>')
				.on("mouseover", function(event) {
					$(this).siblings("span").addClass("todelete");
				})
				.on("mouseout", function(event) {
					$(this).siblings("span").removeClass("todelete");
				});

			$elt.appendTo($list);
			$btn.appendTo($elt);
		},
		
		/**
		 * Construit les options de la combo modele en fonction des marques sélectionnées
		 * Déclenché sur onchange de la combo marque
		 */
		filterModels: function() {
			var $brand = $("#marque"),
				$model = $("#modele"),
				listBrands = $brand.val() || [],
				listSelectedModels = $model.val() || [],
				hasModels = false;

			// reconstruit la liste des modèles
			$model.empty();
			$.each(listBrands, function(i, brand) {
				if (Dtno.utils.isset(vehic.listModels[brand]) && !$.isEmptyObject(vehic.listModels[brand])) {
					var curOptGrp = $('<optgroup label="' + brand + '"/>');
					$model.append(curOptGrp);
					
					$.each(vehic.listModels[brand], function(i, d) {
						var $opt;

						$opt = $('<option value="' + d  + '" data-parent-entity="' + brand + '">' + d + '</option>');
						// si modèle déjà sélectionné
						if ($.inArray(d, listSelectedModels) >= 0) {
							$opt.prop('selected', 'selected');
						}
						$opt.appendTo(curOptGrp);
					});
					
					hasModels = true;
				}
			});

			if (hasModels) {
				$model.prop("disabled", "");
			}
			else {
				$model.prop("disabled", "disabled");
			}
			$model.trigger("liszt:updated");
		},
		
		/**
		 * Affiche les options étendues du formulaire quand on reprend les critères d'une ligne
		 * @param criteria
		 */
		showMoreOptionsOnDuplicate: function(criteria) {
			if ($('#vehicules form .collapse:first').css('display') == 'none' && (
					Dtno.utils.isset(criteria.genre) ||
					Dtno.utils.isset(criteria.segment) ||
					Dtno.utils.isset(criteria.energie) ||
					Dtno.utils.isset(criteria.ddi) ||
					Dtno.utils.isset(criteria.pac) ||
					Dtno.utils.isset(criteria.parg))
			) {
				this.toggleMoreOptions();
			}
		},
		
		/**
		 * Affiche/cache les options étendues du formulaire
		 */
		toggleMoreOptions: function() {
			$('#vehicules form .collapse').each(function() {
				if ($(this).css('display') == 'none') {
					$(this).css({opacity: 0, display: 'inline-block'}).animate({opacity: 1});
					if (vehic.isFrame) {
						vehic.resizeFrame(+20);
					}
				}
				else {
					$(this).animate({opacity: 0}, {complete: function() {
						$(this).css('display', 'none');
						if (vehic.isFrame) {
							vehic.resizeFrame(-40);
						}
					}});
				}
			});
		},
		
		/**
		 * Redimensionne la colorbox
		 * @param deltaHeight delta à appliquer sur la hauteur
		 */
		resizeFrame: function(deltaHeight) {
			var frameWidth = $(document).width();
			var frameHeight = jQuery(document).height();
			
			if (Dtno.utils.isset(deltaHeight)) {
				frameHeight += deltaHeight;
			}
			parent.$.fn.colorbox.myResize(frameWidth, frameHeight);
		},
		
		/**
		 * Récupère toutes les lignes de critères en ajax et lance la mise à jour le DOM
		 */
		load: function() {
			Dtno.utils.doPost(
				'load',
				null,
				function(data) {

					$.each(data.inclus, function(index, d) {
						vehic.addToList('inclus', d);
					});
					$.each(data.exclus, function(index, d) {
						vehic.addToList('exclus', d);
					});
					
					if (data.inclus.length > 0) {
						$("#vehic-inclus").siblings(".placeholder").hide();
					}
					if (data.exclus > 0) {
						$("#vehic-exclus").parent(".panier").show();
					}
					
					if (vehic.isFrame) {
						vehic.resizeFrame(+20);
					}
				},
				null,
				function() {
					alert("An unknown error was raised");
				}
			);
		},

		/**
		 * Supprime une ligne de critère en ajax et met à jour le DOM
		 * @param listName nom de la liste
		 * @param id identifiant de la ligne dans la persistance
		 */
		remove: function(listName, id) {
			Dtno.utils.doPost(
				'remove',
				{list: listName, id: id},
				function(data) {
					$("#vehic-" + listName + "-" + id).remove();
				},
				null,
				function() {
					alert("An unknown error was raised");
				}
			);
		},
		
		/**
		 * Récupère les listes de données en ajax (liste des modèles, liste des marques)
		 */
		initData: function() {
			Dtno.utils.doPost(
				'getLists',
				null,
				function(data) {
					vehic.listModels = data.models;
					vehic.listBrands = data.brands;
				}
			);
		}
	}

	Dtno.vehicules = vehic;
})(jQuery);
