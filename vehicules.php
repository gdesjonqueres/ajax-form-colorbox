<?php
require_once 'inc/define.inc.php';

session_start();

require_once 'inc/functions.inc.php';
require_once 'inc/data.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Vehicules</title>
	<link rel="shortcut icon" href="../../favicon.ico" type="image/x-icon" />
	<meta name="description" content="Réalisez votre achat de fichiers directement en ligne sur Dataneo Direct. Dataneo direct est un service de Dataneo, soci&eacute;t&eacute; sp&eacute;cialiste des solutions marketing direct de prospection. " />
	<meta name="keywords" lang="fr" content="dataneo, dataneo direct, dataneo.fr, marketing direct, fichier, location fichier, telemarketing,tyrode, debray, base de donnees, complex systems, amabis, family service, neo+, neoplus, fichier CSP+, enrichissement de fichier, RNVP, Deduplication ,dedoublonnage, datamining, scoring, mailing, postal, phoning,telemarketing,tyrode, debray, base de donnees, family service, neo+" />
	<link href="../../dataneoDirect/css/datepicker.css" rel="stylesheet" type="text/css" />
	<link href="css/html.css" rel="stylesheet" type="text/css" />
	<link href="css/presentation.css" rel="stylesheet" type="text/css" />
	<link href="css/vehicules.css" rel="stylesheet" type="text/css" />
	<link href="css/chosen.gil-fork.css" rel="stylesheet" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Voces' rel='stylesheet' type='text/css'>
</head>

<body id="vehicules">

	<div class="container">

		<form id="formVehic" class="gradient-grey">

			<div class="inline">

				<div class="control-group">
		        	<label class="control-label" for="marque">Marque(s)</label>
		        	<div class="controls">
		        		<?=displaySelect('marque', $tabMarque, 'input-xlarge', false, 'multiple="multiple" data-placeholder="Choisissez une marque"')?>
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="modele">Modèle(s)</label>
		        	<div class="controls">
		        		<select id="modele" name="marque" class="input-xlarge" multiple="multiple" data-placeholder="Choisissez un modèle" disabled="disabled"></select>
		        	</div>
		  		</div>

		  		<div class="control-group" style="min-width: auto;">
		        	<label class="control-label" for="mec-min"><abbr title="Mois de mise en circulation">Mois MEC</abbr></label>
		        	<div class="controls">
		        		<input type="text" id="mec-min" name="mec-min" class="date input-mini" placeholder="Du" /> -
		        		<input type="text" id="mec-max" name="mec-max" class="date input-mini" placeholder="Au" />
		        	</div>
		  		</div>

		  		<div class="control-group" style="min-width: auto;">
		        	<label class="control-label" for="etat">Etat</label>
		        	<div class="controls">
		        		<?=displaySelect('etat', $tabEtat, 'input-small')?>
		        	</div>
		  		</div>

			</div><!--col-->

			<div class="inline collapse" style="margin-top: 15px; border-top: 1px dashed #aaa;">

				<div class="control-group">
		        	<label class="control-label" for="genre">Genre</label>
		        	<div class="controls">
		        		<?=displaySelect('genre', $tabGenre, 'input-medium')?>
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="segment">Segment</label>
		        	<div class="controls">
		        		<?=displaySelect('segment', $tabSegment, 'input-medium')?>
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="energie">Energie</label>
		        	<div class="controls">
		        		<?=displaySelect('energie', $tabEnergie, 'input-medium')?>
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="carrosserie">Carrosserie</label>
		        	<div class="controls">
		        		<?=displaySelect('carrosserie', $tabCarrosserie, 'input-medium')?>
		        	</div>
		  		</div>

			</div><!--col-->

			<div class="inline collapse">

		  		<div class="control-group">
		        	<label class="control-label" for="ddi-min"><abbr title="Mois de dernière immatriculation">Mois der. immat.</abbr></label>
		        	<div class="controls">
		        		<input type="text" id="ddi-min" name="ddi-min" class="date input-mini" placeholder="Du" /> -
		        		<input type="text" id="ddi-max" name="ddi-max" class="date input-mini" placeholder="Au" />
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="pac-min">Prix achat</label>
		        	<div class="controls">
		        		<?=displaySelect('pac-min', $tabPrix, 'input-small')?> -
		        		<?=displaySelect('pac-max', $tabPrix, 'input-small')?>
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="parg-min">Prix argus</label>
		        	<div class="controls">
		        		<?=displaySelect('parg-min', $tabPrix, 'input-small')?> -
		        		<?=displaySelect('parg-max', $tabPrix, 'input-small')?>
		        	</div>
		  		</div>

  			</div><!--col-->

	  		<div class="form-actions">
		    	<button type="button" id="btn-inclure" class="btn" title="Inclure ces véhicules"><i class="icon-tick"></i> Sélectionner</button>
		    	<button type="button" id="btn-exclure" class="btn" title="Exclure ces véhicules"><i class="icon-ban-circle"></i> Exclure</button>
		    	<button type="button" id="btn-show-options" class="btn" title="Montrer plus de critères de sélection"><i class="icon-plus"></i> Critères</button>
		    	<button type="button" id="btn-reset-form" class="btn btn-reset link" tabindex="-1">Effacer vos critères</button>
		    </div>

		</form>

	</div><!--container-->

	<div class="container">

		<div class="panier gradient-green border-round shadow">
			<div class="panier-label border-round shadow" title="Véhicules inclus dans votre sélection"><i class="icon-tick"></i></div>
			<div class="placeholder">Ici apparaissent les véhicules de votre sélection</div>
			<div class="loading"></div>
			<ul id="vehic-inclus"></ul>
		</div>

		<div class="panier gradient-grey border-round shadow" style="display: none;">
			<div class="panier-label border-round shadow" title="Véhicules exclus de votre sélection"><i class="icon-ban-circle"></i></div>
			<div class="loading"></div>
			<ul id="vehic-exclus"></ul>
		</div>

	</div><!--container-->

	<div style="text-align: left">
		<pre>
			<?php /*if (isset($_SESSION['_vehicules'])) {
				 print formatHtmlDump($_SESSION['_vehicules']);
			}*/?>
		</pre>
	</div>

	<script src="js/vendor/jquery.js" type="text/javascript"></script>
	<script src="js/vendor/jquery.mtz.monthpicker.js" type="text/javascript"></script>
	<script src="js/vendor/chosen.gil-fork.jquery.min.js" type="text/javascript"></script>
	<script src="js/base.js" type="text/javascript"></script>
	<script type="text/javascript">
	//<![CDATA[
		Dtno.config.urlAjax = '<?=URL_ROOT?>aaa/ajax/index.php';
	//]]>
	</script>
	<script src="js/vehicules.js" type="text/javascript"></script>
	<script type="text/javascript">
	//<![CDATA[
		jQuery(document).ready(function($) {

			$(".date").monthpicker({
				startYear: <?=(date('Y') - 25)?>,
				finalYear: <?=date('Y')?>,
				selectedYear: <?=(date('Y') - 3)?>,
				monthNames: ['Jan', 'Fev', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Dec'],
				pattern: 'mm/yyyy'
			});

			Dtno.vehicules.init();
		});
	//]]>
	</script>

</body><!--vehicules-->

</html>